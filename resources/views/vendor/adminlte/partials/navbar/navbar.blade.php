@php
    // Consulta para el cierre de caja
    $lastCashHistory = \App\Models\CashHistory::with('user')->where('estado', 'Apertura')->latest()->first();

    // Fecha actual y mes actual
    $hoy = now();
    $mesActual = $hoy->format('m');
    $mesAnoActual = $hoy->format('Y-m');
    
    // Consulta para cumpleaños sin mensajes enviados en el mes actual
    $cumpleañerosPendientes = \App\Models\HistorialClinico::whereRaw('MONTH(fecha_nacimiento) = ?', [$mesActual])
        ->whereNotNull('celular')
        ->whereRaw('CONCAT(nombres, " ", apellidos) NOT IN (
            SELECT CONCAT(hc.nombres, " ", hc.apellidos) 
            FROM historiales_clinicos hc
            INNER JOIN mensajes_enviados me ON hc.id = me.historial_id
            WHERE me.tipo = "cumpleanos"
            AND DATE_FORMAT(me.fecha_envio, "%Y-%m") = ?
        )', [$mesAnoActual])
        ->count();

    // Consulta para recordatorios de consulta sin mensajes enviados en el mes actual
    $inicioMes = $hoy->copy()->startOfMonth();
    $finMes = $hoy->copy()->endOfMonth();
    
    $consultasPendientes = \App\Models\HistorialClinico::whereNotNull('proxima_consulta')
        ->whereNotNull('celular')
        ->whereDate('proxima_consulta', '>=', $inicioMes)
        ->whereDate('proxima_consulta', '<=', $finMes)
        ->whereRaw('CONCAT(nombres, " ", apellidos) NOT IN (
            SELECT CONCAT(hc.nombres, " ", hc.apellidos)
            FROM historiales_clinicos hc
            INNER JOIN mensajes_enviados me ON hc.id = me.historial_id
            WHERE me.tipo = "consulta"
            AND DATE_FORMAT(me.fecha_envio, "%Y-%m") = ?
        )', [$mesAnoActual])
        ->count();
@endphp

<nav class="main-header navbar
    {{ config('adminlte.classes_topnav_nav', 'navbar-expand') }}
    {{ config('adminlte.classes_topnav', 'navbar-white navbar-light') }}">

    {{-- Navbar left links --}}
    <ul class="navbar-nav">
        {{-- Left sidebar toggler link --}}
        @include('adminlte::partials.navbar.menu-item-left-sidebar-toggler')

        {{-- Lista de Empresas en fila --}}
        <li class="nav-item d-flex align-items-center">
            <i class="fas fa-building ml-2 mr-2 text-secondary"></i>
            @if(Auth::user() && Auth::user()->is_admin)
                {{-- Si es admin, mostrar TODAS LAS SUCURSALES --}}
                @foreach(\App\Models\Empresa::all() as $empresa)
                    <span class="badge badge-info mr-2" style="font-size: 0.9rem; padding: 8px 12px;">
                        {{ $empresa->nombre }}
                    </span>
                @endforeach
            @elseif(Auth::user() && Auth::user()->empresa_id)
                {{-- Si no es admin, mostrar solo su empresa --}}
                @php
                    $userEmpresa = \App\Models\Empresa::find(Auth::user()->empresa_id);
                @endphp
                @if($userEmpresa)
                    <span class="badge badge-info mr-2" style="font-size: 0.9rem; padding: 8px 12px;">
                        {{ $userEmpresa->nombre }}
                    </span>
                @else
                    <span class="badge badge-secondary mr-2" style="font-size: 0.9rem; padding: 8px 12px;">
                        SIN EMPRESA ASIGNADA
                    </span>
                @endif
            @endif
        </li>

        {{-- Mensaje de advertencia de cierre de caja --}}
        @if(Auth::user() && Auth::user()->empresa_id)
            @php
                $cajaAbierta = \App\Models\CashHistory::where('empresa_id', Auth::user()->empresa_id)
                                                    ->where('estado', 'Apertura')
                                                    ->latest()
                                                    ->first();
            @endphp
            @if($cajaAbierta && (!Auth::user()->is_admin || (Auth::user()->is_admin && $cajaAbierta->user_id == Auth::id())))
                <li class="nav-item d-none d-md-block">
                    <div class="alert alert-danger py-1 px-3 mb-0 ml-3 d-flex align-items-center" style="font-size: 0.9rem;">
                        <i class="fas fa-exclamation-triangle mr-2"></i>
                        Debe cerrar caja de {{ \App\Models\Empresa::find($cajaAbierta->empresa_id)->nombre ?? 'EMPRESA' }} antes de salir
                    </div>
                </li>
            @endif
        @endif

        {{-- Notificaciones ahora están en el icono de campana en la derecha --}}

        {{-- Configured left links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-left'), 'item')

        {{-- Custom left links --}}
        @yield('content_top_nav_left')
    </ul>

    {{-- Navbar right links --}}
    <ul class="navbar-nav ml-auto">
        {{-- Notificaciones con icono de campana para todos los dispositivos --}}
        @if($cumpleañerosPendientes > 0 || $consultasPendientes > 0)
            <li class="nav-item dropdown">
                <a class="nav-link" data-toggle="dropdown" href="#" aria-expanded="false">
                    <i class="far fa-bell"></i>
                    <span class="badge badge-danger navbar-badge">{{ $cumpleañerosPendientes + $consultasPendientes }}</span>
                </a>
                <div class="dropdown-menu dropdown-menu-lg dropdown-menu-right" style="left: inherit; right: 0px;">
                    <span class="dropdown-item dropdown-header">{{ $cumpleañerosPendientes + $consultasPendientes }} NOTIFICACIONES</span>
                    
                    @if($cumpleañerosPendientes > 0)
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('mensajes.cumpleanos') }}" class="dropdown-item">
                            <i class="fas fa-birthday-cake mr-2"></i> {{ $cumpleañerosPendientes }} CUMPLEAÑEROS PENDIENTES
                        </a>
                    @endif
                    
                    @if($consultasPendientes > 0)
                        <div class="dropdown-divider"></div>
                        <a href="{{ route('mensajes.recordatorios') }}" class="dropdown-item">
                            <i class="fas fa-calendar-check mr-2"></i> {{ $consultasPendientes }} RECORDATORIOS PENDIENTES
                        </a>
                    @endif
                </div>
            </li>
        @endif

        {{-- Botón de cierre de caja (solo para no administradores) o cierre de sesión (para administradores) --}}
        @auth
            @if(Auth::user()->is_admin)
                {{-- Botón de cierre de sesión para administradores --}}
                <li class="nav-item">
                    <a href="{{ route('logout') }}" 
                       onclick="event.preventDefault(); document.getElementById('logout-form').submit();"
                       class="btn btn-outline-danger nav-link d-flex align-items-center" 
                       style="border-radius: 20px; padding: 8px 20px; transition: all 0.3s ease;">
                        <i class="fas fa-sign-out-alt mr-2" style="font-size: 1.1em;"></i>
                        <span class="d-none d-sm-inline" style="font-weight: 500;">CERRAR SESIÓN</span>
                        <span class="d-inline d-sm-none" style="font-weight: 500;">SALIR</span>
                    </a>
                    <form id="logout-form" action="{{ route('logout') }}" method="POST" style="display: none;">
                        @csrf
                    </form>
                </li>
            @else
                {{-- Verificar si hay caja abierta para la empresa del usuario --}}
                @php
                    $cajaAbierta = null;
                    if(Auth::user()->empresa_id) {
                        $cajaAbierta = \App\Models\CashHistory::where('empresa_id', Auth::user()->empresa_id)
                                                            ->where('estado', 'Apertura')
                                                            ->latest()
                                                            ->first();
                    }
                @endphp

                @if($cajaAbierta)
                    {{-- Botón de cierre de caja para usuarios no administradores --}}
                    <li class="nav-item">
                        <form action="{{ route('show-closing-card') }}" method="POST" style="display: inline;">
                            @csrf
                            <button type="submit" class="btn btn-outline-danger nav-link d-flex align-items-center" 
                                    style="border-radius: 20px; padding: 8px 20px; transition: all 0.3s ease;">
                                <i class="fas fa-cash-register mr-2" style="font-size: 1.1em;"></i>
                                <span class="d-none d-sm-inline" style="font-weight: 500;">CERRAR CAJA</span>
                                <span class="d-inline d-sm-none" style="font-weight: 500;">CERRAR</span>
                            </button>
                        </form>
                    </li>
                @else
                    {{-- Botón de cierre de sesión cuando no hay caja abierta --}}
                    <li class="nav-item">
                        <a href="{{ route('logout') }}" 
                           onclick="event.preventDefault(); document.getElementById('logout-form-alt').submit();"
                           class="btn btn-outline-danger nav-link d-flex align-items-center" 
                           style="border-radius: 20px; padding: 8px 20px; transition: all 0.3s ease;">
                            <i class="fas fa-sign-out-alt mr-2" style="font-size: 1.1em;"></i>
                            <span class="d-none d-sm-inline" style="font-weight: 500;">CERRAR SESIÓN</span>
                            <span class="d-inline d-sm-none" style="font-weight: 500;">SALIR</span>
                        </a>
                        <form id="logout-form-alt" action="{{ route('logout') }}" method="POST" style="display: none;">
                            @csrf
                        </form>
                    </li>
                @endif
            @endif
        @else
            {{-- Botón para usuarios no autenticados --}}
            <li class="nav-item">
                <a href="{{ route('login') }}" class="btn btn-outline-danger nav-link d-flex align-items-center" 
                   style="border-radius: 20px; padding: 8px 20px; transition: all 0.3s ease;">
                    <i class="fas fa-sign-in-alt mr-2" style="font-size: 1.1em;"></i>
                    <span class="d-none d-sm-inline" style="font-weight: 500;">INICIAR SESIÓN</span>
                    <span class="d-inline d-sm-none" style="font-weight: 500;">LOGIN</span>
                </a>
            </li>
        @endauth

        {{-- Custom right links --}}
        @yield('content_top_nav_right')

        {{-- Configured right links --}}
        @each('adminlte::partials.navbar.menu-item', $adminlte->menu('navbar-right'), 'item')

        {{-- Right sidebar toggler link --}}
        @if(config('adminlte.right_sidebar'))
            @include('adminlte::partials.navbar.menu-item-right-sidebar-toggler')
        @endif
    </ul>
</nav>

<style>
    .btn-outline-danger {
        border: 2px solid #dc3545;
        background-color: transparent;
        color: #dc3545;
        text-transform: uppercase;
    }

    .btn-outline-danger:hover {
        background-color: #dc3545;
        color: white;
        transform: translateY(-2px);
        box-shadow: 0 4px 6px rgba(220, 53, 69, 0.2);
    }

    .btn-outline-danger:active {
        transform: translateY(0);
    }

    .btn-outline-danger i {
        transition: transform 0.3s ease;
    }

    .btn-outline-danger:hover i {
        transform: rotate(-15deg);
    }
    
    /* Estilos para las alertas de notificación */
    .alert-info, .alert-warning {
        transition: all 0.3s ease;
    }
    
    .alert-info:hover, .alert-warning:hover {
        transform: translateY(-2px);
        box-shadow: 0 2px 5px rgba(0,0,0,0.1);
    }

    /* Ajustes responsivos para el botón */
    @media (max-width: 576px) {
        .btn-outline-danger {
            padding: 8px 15px !important;
        }
    }
</style>
