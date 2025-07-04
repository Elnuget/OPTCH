@extends('adminlte::page')

@section('title', 'Agregar venta')

@section('content_header')
    @if (session('error'))
        <div class="alert {{ session('tipo') }} alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong> {{ session('mensaje') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@stop

@section('content')
    <style>
        /* Convertir todo el texto a mayúsculas */
        .card-title,
        .card-header h3,
        .form-label,
        label,
        .list-group-item,
        .input-group-text,
        .custom-select option,
        .btn,
        input::placeholder,
        select option,
        .text-muted,
        strong,
        p,
        h1, h2, h3, h4, h5, h6 {
            text-transform: uppercase !important;
        }

        /* Estilos para hacer clickeable el header completo */
        .card-header {
            cursor: pointer;
        }
        .card-header:hover {
            background-color: rgba(0,0,0,.03);
        }

        /* Convertir inputs a mayúsculas */
        input[type="text"],
        input[type="email"],
        input[type="number"],
        textarea,
        select,
        .form-control {
            text-transform: uppercase !important;
        }

        /* Asegurar que los placeholders también estén en mayúsculas */
        input::placeholder,
        textarea::placeholder {
            text-transform: uppercase !important;
        }

        /* Asegurar que las opciones de datalist estén en mayúsculas */
        datalist option {
            text-transform: uppercase !important;
        }

        /* Asegurar que las opciones del selectpicker estén en mayúsculas */
        .bootstrap-select .dropdown-menu li a {
            text-transform: uppercase !important;
        }

        /* Mejorar la experiencia de los datalist - SIMPLIFICADO */
        input[list]:focus {
            outline: 2px solid #007bff;
            outline-offset: 2px;
            border-color: #007bff !important;
        }
    </style>

    {{-- Mostrar mensajes de error --}}
    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif

    {{-- Mostrar mensajes de error específicos de la base de datos --}}
    @if (session('db_error'))
        <div class="alert alert-danger">
            {{ session('db_error') }}
        </div>
    @endif

    <br>

    <div class="card">
        <div class="card-header">
            <h3 class="card-title">Añadir Pedido</h3>

            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip"
                    title="Ocultar/Mostrar">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Cerrar">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>

        <div class="card-body">
            <div class="col-md-12">
                <form action="{{ route('pedidos.store') }}" method="POST" id="pedidoForm" enctype="multipart/form-data">
                    @csrf

                    {{-- Información Básica --}}
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Información Básica</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Fila 1 --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="fecha" class="form-label">Fecha</label>
                                    <input type="date" class="form-control" id="fecha" name="fecha"
                                           value="{{ old('fecha', $currentDate) }}">
                                </div>
                                <div class="col-md-6">
                                    <label for="numero_orden" class="form-label">Orden</label>
                                    <input type="number" class="form-control" id="numero_orden" name="numero_orden"
                                           value="{{ old('numero_orden', $nextOrderNumber) }}">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Datos Personales --}}
                    <div class="card">
                        <div class="card-header">
                            <h3 class="card-title">Datos Personales</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-minus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Fila 2 --}}
                            <div class="row mb-3">
                                <div class="col-md-12 mb-3">
                                    <label for="buscar_historial_clinico" class="form-label">Buscar Historial Clínico</label>
                                    <select class="form-control selectpicker" data-live-search="true" id="buscar_historial_clinico" data-size="10">
                                        <option value="">Seleccione un paciente del historial clínico</option>
                                        {{-- Solo Historial Clínico - Últimos registros únicos --}}
                                        @if(isset($historiales))
                                            @php
                                                $historialesUnicos = collect($historiales)->groupBy(function($historial) {
                                                    return strtolower(trim($historial->nombres . ' ' . $historial->apellidos));
                                                })->map(function($group) {
                                                    return $group->sortByDesc('fecha')->first(); // Último registro por fecha
                                                });
                                            @endphp
                                            
                                            @foreach($historialesUnicos as $historial)
                                                <option value="{{ $historial->nombres }} {{ $historial->apellidos }}" 
                                                        data-cedula="{{ $historial->cedula }}"
                                                        data-celular="{{ $historial->celular }}"
                                                        data-correo="{{ $historial->correo }}"
                                                        data-direccion="{{ $historial->direccion }}"
                                                        data-sucursal="{{ $historial->empresa ? strtoupper($historial->empresa->nombre) : 'SIN EMPRESA' }}"
                                                        data-fecha="{{ $historial->fecha ? $historial->fecha->format('d/m/Y') : 'Sin fecha' }}">
                                                    {{ $historial->nombres }} {{ $historial->apellidos }} 
                                                    ({{ $historial->empresa ? strtoupper($historial->empresa->nombre) : 'SIN EMPRESA' }} - {{ $historial->fecha ? $historial->fecha->format('d/m/Y') : 'Sin fecha' }})
                                                </option>
                                            @endforeach
                                        @endif
                                    </select>
                                    <small class="form-text text-muted">
                                        Busque en el historial clínico. Se muestran solo los últimos registros únicos.
                                        <br><strong>Formato:</strong> Nombre (Empresa - Fecha del historial)
                                    </small>
                                </div>
                                <div class="col-md-6">
                                    <label for="fact" class="form-label">Factura</label>
                                    <input type="text" class="form-control" id="fact" name="fact"
                                           value="Pendiente">
                                </div>
                                <div class="col-<md-6">
                                    <label for="cliente" class="form-label">Cliente</label>
                                    <input type="text" class="form-control" id="cliente" name="cliente" required>
                                </div>
                            </div>

                            {{-- Nueva fila para cédula --}}
                            <div class="row mb-3">                                <div class="col-md-6">
                                    <label for="cedula" class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="cedula" name="cedula" list="cedulas_existentes" placeholder="Seleccione o escriba una cédula" autocomplete="off">
                                    <datalist id="cedulas_existentes">
                                        @foreach($cedulas as $cedula)
                                            <option value="{{ $cedula }}">
                                        @endforeach
                                    </datalist>
                                </div>
                                <div class="col-md-6">
                                    <label for="paciente" class="form-label">Paciente</label>
                                    <input type="text" class="form-control" id="paciente" name="paciente">
                                </div>
                            </div>

                            {{-- Fila 3 --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="examen_visual" class="form-label">Examen Visual</label>
                                    <input type="number" class="form-control form-control-sm" id="examen_visual" name="examen_visual" step="0.01" oninput="calculateTotal()">
                                </div>                                <div class="col-md-3">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="text" class="form-control" id="celular" name="celular" placeholder="Escriba el número de celular" autocomplete="off">
                                </div>                                <div class="col-md-3">
                                    <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico" placeholder="Escriba el correo electrónico" autocomplete="off">
                                </div>
                                <div class="col-md-3">
                                    <label for="empresa_id" class="form-label">Empresa</label>
                                    <select name="empresa_id" id="empresa_id" class="form-control" {{ !$isUserAdmin && $userEmpresaId ? 'disabled' : '' }}>
                                        <option value="">Seleccione una empresa...</option>
                                        @foreach($empresas as $empresa)
                                            <option value="{{ $empresa->id }}" {{ ($userEmpresaId == $empresa->id) ? 'selected' : '' }}>{{ $empresa->nombre }}</option>
                                        @endforeach
                                    </select>
                                    @if(!$isUserAdmin && $userEmpresaId)
                                        <input type="hidden" name="empresa_id" value="{{ $userEmpresaId }}">
                                    @endif
                                </div>
                            </div>

                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="direccion" class="form-label">Dirección</label>
                                    <input type="text" class="form-control" id="direccion" name="direccion" placeholder="Escriba la dirección" autocomplete="off">
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Armazón --}}
                    <div id="armazon-container" class="card collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Armazón</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label>Armazón (Inventario)</label>
                                    <select class="form-control selectpicker" data-live-search="true" name="a_inventario_id[]">
                                        <option value="">Seleccione un armazón</option>
                                        @foreach($armazones as $armazon)
                                            <option value="{{ $armazon->id }}">
                                                {{ $armazon->codigo }} - {{ $armazon->lugar }} - {{ $armazon->fecha ? \Carbon\Carbon::parse($armazon->fecha)->format('d/m/Y') : 'Sin fecha' }} - {{ $armazon->empresa ? $armazon->empresa->nombre : 'Sin empresa' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                            </div>

                            {{-- Fila 5 --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label for="a_precio" class="form-label">Precio Armazón</label>
                                    <input type="number" class="form-control form-control-sm precio-armazon" id="a_precio" name="a_precio[]" step="0.01" oninput="calculateTotal()">
                                </div>
                                <div class="col-md-3">
                                    <label for="a_precio_descuento" class="form-label">Desc. Armazón (%)</label>
                                    <input type="number" class="form-control form-control-sm descuento-armazon" id="a_precio_descuento"
                                           name="a_precio_descuento[]" min="0" max="100" value="0" oninput="calculateTotal()">
                                </div>
                                <div class="col-md-6">
                                    <label for="a_foto" class="form-label">Foto Armazón (Opcional)</label>
                                    <input type="file" class="form-control form-control-sm" id="a_foto" name="a_foto[]" accept="image/*">
                                    <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success" onclick="duplicateArmazon()">Agregar más Armazón</button>
                        </div>
                    </div>

                    {{-- Lunas --}}
                    <div id="lunas-container" class="card collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Lunas</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Fila 6 --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="l_medida" class="form-label">Lunas Medidas</label>
                                    <input type="text" class="form-control" id="l_medida" name="l_medida[]">
                                </div>
                                <div class="col-md-6">
                                    <label for="l_detalle" class="form-label">Lunas Detalle</label>
                                    <input type="text" class="form-control" id="l_detalle" name="l_detalle[]">
                                </div>
                            </div>
                            {{-- Fila nueva para tipo de lente, material y filtro --}}
                            <div class="row mb-3">
                                <div class="col-md-4">
                                    <label for="tipo_lente" class="form-label">Tipo de Lente</label>
                                    <input type="text" class="form-control" id="tipo_lente" name="tipo_lente[]" list="tipo_lente_options" placeholder="Seleccione o escriba un tipo de lente">
                                    <datalist id="tipo_lente_options">
                                        <option value="Monofocal">
                                        <option value="Bifocal">
                                        <option value="Progresivo">
                                        <option value="Ocupacional">
                                        <option value="Contacto">
                                    </datalist>
                                </div>
                                <div class="col-md-4">
                                    <label for="material" class="form-label">Material</label>
                                    <input type="text" class="form-control" id="material" name="material[]" list="material_options" placeholder="Seleccione o escriba un material">
                                    <datalist id="material_options">
                                        <option value="Policarbonato">
                                        <option value="CR-39">
                                        <option value="Cristal">
                                        <option value="1.56">
                                        <option value="1.61">
                                        <option value="1.67">
                                        <option value="1.74">
                                        <option value="GX7">
                                        <option value="Crizal">
                                    </datalist>
                                </div>
                                <div class="col-md-4">
                                    <label for="filtro" class="form-label">Filtro</label>
                                    <input type="text" class="form-control" id="filtro" name="filtro[]" list="filtro_options" placeholder="Seleccione o escriba un filtro">
                                    <datalist id="filtro_options">
                                        <option value="Antireflejo">
                                        <option value="UV">
                                        <option value="Filtro azul AR verde">
                                        <option value="Filtro azul AR azul">
                                        <option value="Fotocromatico">
                                        <option value="Blancas">
                                        <option value="Fotocromatico AR">
                                        <option value="Fotocromatico filtro azul">
                                        <option value="Fotocromatico a colores">
                                        <option value="Tinturado">
                                        <option value="Polarizado">
                                        <option value="Transitions">
                                    </datalist>
                                </div>
                            </div>
                            {{-- Fila nueva para precio y descuento de lunas --}}
                            <div class="row mb-3">
                                <div class="col-md-3">
                                    <label class="form-label">Precio Lunas</label>
                                    <input type="number" class="form-control input-sm" name="l_precio[]" step="0.01" oninput="calculateTotal()">
                                </div>
                                <div class="col-md-3">
                                    <label class="form-label">Desc. Lunas (%)</label>
                                    <input type="number" class="form-control input-sm" name="l_precio_descuento[]" min="0" max="100" value="0" oninput="calculateTotal()">
                                </div>
                                <div class="col-md-6">
                                    <label class="form-label">Foto Lunas (Opcional)</label>
                                    <input type="file" class="form-control form-control-sm" name="l_foto[]" accept="image/*">
                                    <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success" onclick="duplicateLunas()">Agregar más Lunas</button>
                        </div>
                    </div>

                    {{-- Accesorios --}}
                    <div id="accesorios-container" class="card collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Accesorios</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Fila 7 --}}
                            <div class="row mb-3 accesorio-item">
                                <div class="col-md-6">
                                    <label for="d_inventario_id[]" class="form-label">Accesorio (Inventario)</label>
                                    <select class="form-control selectpicker" data-live-search="true" id="d_inventario_id[]" name="d_inventario_id[]">
                                        <option value="">Seleccione un Item del Inventario</option>
                                        @foreach ($accesorios as $item)
                                            <option value="{{ $item->id }}">
                                                {{ $item->codigo }} - {{ $item->lugar }} - {{ $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : 'Sin fecha' }} - {{ $item->empresa ? $item->empresa->nombre : 'Sin empresa' }}
                                            </option>
                                        @endforeach
                                    </select>
                                </div>
                                <div class="col-md-3">
                                    <label for="d_precio[]" class="form-label">Precio Accesorio</label>
                                    <input type="number" class="form-control input-sm" id="d_precio[]" name="d_precio[]" step="0.01" oninput="calculateTotal()">
                                </div>
                                <div class="col-md-3">
                                    <label for="d_precio_descuento[]" class="form-label">Desc. Accesorio (%)</label>
                                    <input type="number" class="form-control input-sm" id="d_precio_descuento[]" name="d_precio_descuento[]" min="0" max="100" value="0" oninput="calculateTotal()">
                                </div>
                            </div>
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="d_foto[]" class="form-label">Foto Accesorio (Opcional)</label>
                                    <input type="file" class="form-control form-control-sm" id="d_foto[]" name="d_foto[]" accept="image/*">
                                    <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF</small>
                                </div>
                            </div>
                        </div>
                        <div class="card-footer">
                            <button type="button" class="btn btn-success" onclick="duplicateAccesorios()">Agregar más Accesorios</button>
                        </div>
                    </div>

                    {{-- Compra Rápida --}}
                    <div class="card collapsed-card">
                        <div class="card-header">
                            <h3 class="card-title">Compra Rápida</h3>
                            <div class="card-tools">
                                <button type="button" class="btn btn-tool" data-card-widget="collapse">
                                    <i class="fas fa-plus"></i>
                                </button>
                            </div>
                        </div>
                        <div class="card-body">
                            {{-- Nueva fila para compra rápida --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="valor_compra" class="form-label">Valor de Compra</label>
                                    <input type="number" class="form-control input-sm" id="valor_compra" name="valor_compra" step="0.01">
                                </div>
                                <div class="col-md-6">
                                    <label for="motivo_compra" class="form-label">Motivo de Compra</label>
                                    <input type="text" class="form-control" id="motivo_compra" name="motivo_compra" 
                                           list="motivo_compra_options" placeholder="Seleccione o escriba un motivo">
                                    <datalist id="motivo_compra_options">
                                        <option value="Líquidos">
                                        <option value="Accesorios">
                                        <option value="Estuches">
                                        <option value="Otros">
                                    </datalist>
                                </div>
                            </div>
                        </div>
                    </div>

                    {{-- Total y Botones --}}
                    <div class="card">
                        <div class="card-body">
                            {{-- Fila Total --}}
                            <div class="row mb-3">
                                <div class="col-md-12">
                                    <label for="total" class="form-label" style="color: red;">Total</label>
                                    <input type="number" class="form-control input-sm" id="total" name="total" step="0.01" readonly>
                                </div>
                            </div>

                            {{-- Fila oculta (Saldo) --}}
                            <div class="row mb-3" style="display: none;">
                                <div class="col-md-12">
                                    <label for="saldo" class="form-label">Saldo</label>
                                    <input type="number" 
                                           class="form-control" 
                                           id="saldo" 
                                           name="saldo" 
                                           value="0"
                                           step="0.01"
                                           required>
                                </div>
                            </div>

                            {{-- Botones y Modal --}}
                            <div class="d-flex justify-content-start">
                                <button type="button" class="btn btn-primary mr-2" data-toggle="modal" data-target="#modal">
                                    Crear pedido
                                </button>
                                <a href="{{ route('pedidos.index') }}" class="btn btn-secondary">
                                    Cancelar
                                </a>
                            </div>

                            <div class="modal fade" id="modal">
                                <div class="modal-dialog">
                                    <div class="modal-content">
                                        <div class="modal-header">
                                            <h4 class="modal-title">Crear pedido</h4>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>
                                            </button>
                                        </div>
                                        <div class="modal-body">
                                            <p>¿Estás seguro que desea crear el pedido?</p>
                                        </div>
                                        <div class="modal-footer">
                                            <button type="button" class="btn btn-default pull-left"
                                                    data-dismiss="modal">Cancelar
                                            </button>
                                            <button type="submit" class="btn btn-primary">Crear pedido</button>
                                        </div>
                                    </div>
                                    <!-- /.modal-content -->
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
        <!-- /.card-body -->

        <div class="card-footer">
            Añadir Pedido
        </div>
        <!-- /.card-footer-->
    </div>

@stop

@section('js')
    <script>
        // Hacer que todo el header sea clickeable
        document.addEventListener('DOMContentLoaded', function() {
            document.querySelectorAll('.card-header').forEach(header => {
                header.addEventListener('click', function(e) {
                    // Si el clic no fue en un botón dentro del header
                    if (!e.target.closest('.btn-tool')) {
                        const collapseButton = this.querySelector('.btn-tool');
                        if (collapseButton) {
                            collapseButton.click();
                        }
                    }
                });
            });

            // Búsqueda simplificada solo para historial clínico con selectpicker
            $('#buscar_historial_clinico').on('changed.bs.select', function() {
                const valor = $(this).val();
                
                if (!valor) {
                    limpiarCamposAutocompletado();
                    return;
                }
                
                // Obtener datos del option seleccionado
                const selectedOption = $(this).find('option:selected');
                const cedula = selectedOption.data('cedula');
                const celular = selectedOption.data('celular');
                const correo = selectedOption.data('correo');
                const direccion = selectedOption.data('direccion');
                const empresa = selectedOption.data('sucursal');
                const fecha = selectedOption.data('fecha');
                
                // Mostrar información del registro seleccionado
                mostrarInformacionHistorial(empresa, fecha);
                
                // Llenar campos básicos
                $('#cliente').val(extraerNombreLimpio(valor));
                $('#paciente').val(extraerNombreLimpio(valor));
                
                // Llenar campos adicionales si existen
                if (celular) $('#celular').val(celular);
                if (correo) $('#correo_electronico').val(correo);
                if (direccion) $('#direccion').val(direccion);
                
                // Buscar datos completos del historial clínico
                if (cedula) {
                    buscarHistorialClinicoPorCedula(cedula);
                } else {
                    buscarHistorialClinicoPorNombreCompleto(extraerNombreLimpio(valor));
                }
            });

            // Manejar cédula - buscar en historial clínico
            $('#cedula').on('input', function() {
                const valor = this.value.trim();
                if (valor.length >= 3) {
                    setTimeout(() => {
                        const valorActual = $('#cedula').val();
                        if (valorActual === valor) {
                            buscarHistorialClinicoPorCedula(valor);
                        }
                    }, 300);
                }
            });

        });

        // Funciones simplificadas para historial clínico únicamente
        
        // Función para extraer solo el nombre limpio sin información adicional
        window.extraerNombreLimpio = function(valorCompleto) {
            return valorCompleto.replace(/\s*\([^)]*\)\s*/g, '').trim();
        };
        
        // Función para limpiar campos de autocompletado
        window.limpiarCamposAutocompletado = function() {
            const mensajesPrevios = document.querySelectorAll(
                '.loading-indicator-historial, .info-historial, .error-historial, .alert-success, .info-historial-registro'
            );
            mensajesPrevios.forEach(msg => msg.remove());
        };
        
        // Función para mostrar información del historial seleccionado
        window.mostrarInformacionHistorial = function(empresa, fecha) {
            limpiarCamposAutocompletado();
            
            const infoMsg = document.createElement('div');
            infoMsg.classList.add('alert', 'alert-info', 'mt-2', 'alert-sm', 'info-historial-registro');
            infoMsg.style.fontSize = '0.875rem';
            infoMsg.style.padding = '0.5rem';
            
            let textoInfo = 'Registro del <strong>HISTORIAL CLÍNICO</strong>';
            if (empresa && empresa !== 'SIN EMPRESA') {
                textoInfo += ` - Empresa: <strong>${empresa}</strong>`;
            }
            if (fecha && fecha !== 'Sin fecha') {
                textoInfo += ` - Fecha: <strong>${fecha}</strong>`;
            }
            
            infoMsg.innerHTML = textoInfo;
            document.getElementById('buscar_historial_clinico').parentNode.appendChild(infoMsg);
            
            setTimeout(() => {
                infoMsg.remove();
            }, 4000);
        };

        // Funciones de búsqueda en historial clínico - SIMPLIFICADAS
        window.buscarHistorialClinico = function(nombreCompleto) {
            if (!nombreCompleto) return;
            buscarHistorialClinicoPorNombreCompleto(nombreCompleto);
        };
        
        window.buscarHistorialClinicoPorCedula = function(cedula) {
            if (!cedula) return;
            buscarHistorialClinicoPorCampo('cedula', cedula);
        };

        window.buscarHistorialClinicoPorNombreCompleto = function(nombreCompleto) {
            if (!nombreCompleto) return;
            
            // Remover indicadores de carga previos
            limpiarCamposAutocompletado();
            
            // Mostrar indicador de carga
            const loadingIndicator = document.createElement('small');
            loadingIndicator.classList.add('loading-indicator-historial', 'text-muted', 'ml-2');
            loadingIndicator.textContent = 'Buscando en historial clínico...';
            document.getElementById('buscar_historial_clinico').parentNode.appendChild(loadingIndicator);
            
            // Petición AJAX
            const url = `/api/historiales-clinicos/buscar-nombre-completo/${encodeURIComponent(nombreCompleto)}`;
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Error al obtener datos del historial');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    procesarRespuestaHistorialClinico(data);
                })
                .catch(error => {
                    procesarErrorHistorialClinico(error);
                });
        };
        
        window.buscarHistorialClinicoPorCampo = function(campo, valor) {
            if (!valor) return;
            
            // Remover indicadores de carga previos
            limpiarCamposAutocompletado();
            
            // Mostrar indicador de carga
            const loadingIndicator = document.createElement('small');
            loadingIndicator.classList.add('loading-indicator-historial', 'text-muted', 'ml-2');
            loadingIndicator.textContent = 'Buscando en historial clínico...';
            document.getElementById('buscar_historial_clinico').parentNode.appendChild(loadingIndicator);
            
            // Petición AJAX
            const url = `/api/historiales-clinicos/buscar-por/${campo}/${encodeURIComponent(valor)}`;
            
            fetch(url)
                .then(response => {
                    if (!response.ok) {
                        return response.json().then(err => {
                            throw new Error(err.message || 'Error al obtener datos del historial');
                        });
                    }
                    return response.json();
                })
                .then(data => {
                    procesarRespuestaHistorialClinico(data);
                })
                .catch(error => {
                    procesarErrorHistorialClinico(error);
                });
        };

        // Función para procesar respuestas del historial clínico - SIMPLIFICADA
        window.procesarRespuestaHistorialClinico = function(data) {
            // Remover indicador de carga
            limpiarCamposAutocompletado();
            
            if (data.success && data.historial) {
                // Mostrar notificación de éxito
                const successMsg = document.createElement('div');
                successMsg.classList.add('alert', 'alert-success', 'mt-2', 'alert-sm');
                
                let textoExito = 'Historial clínico cargado correctamente';
                if (data.historial.empresa && data.historial.empresa.nombre) {
                    textoExito += ` - Empresa: ${data.historial.empresa.nombre.toUpperCase()}`;
                }
                if (data.historial.created_at) {
                    textoExito += ` - Fecha: ${data.historial.created_at}`;
                }
                
                successMsg.textContent = textoExito;
                successMsg.style.fontSize = '0.875rem';
                successMsg.style.padding = '0.5rem';
                document.getElementById('buscar_historial_clinico').parentNode.appendChild(successMsg);
                
                setTimeout(() => successMsg.remove(), 4000);
                
                // Autocompletar campos (solo si están vacíos)
                if (!$('#cedula').val() && data.historial.cedula) {
                    $('#cedula').val(data.historial.cedula);
                }
                if (!$('#celular').val() && data.historial.celular) {
                    $('#celular').val(data.historial.celular);
                }
                if (!$('#correo_electronico').val() && data.historial.correo) {
                    $('#correo_electronico').val(data.historial.correo);
                }
                if (!$('#direccion').val() && data.historial.direccion) {
                    $('#direccion').val(data.historial.direccion);
                }
                if (!$('#empresa_id').val() && data.historial.empresa_id) {
                    $('#empresa_id').val(data.historial.empresa_id);
                }
                
                // Cargar datos de receta en el campo medida
                if (data.historial.od_esfera !== undefined) {
                    // Abrir sección de lunas
                    const lunasHeader = document.querySelector('#lunas-container .card-header');
                    const lunasCollapsed = document.querySelector('#lunas-container').classList.contains('collapsed-card');
                    if (lunasCollapsed && lunasHeader) {
                        lunasHeader.querySelector('.btn-tool').click();
                    }
                    
                    // Formatear receta
                    const formatearValor = (valor) => {
                        if (valor === null || valor === undefined || valor === '') return '';
                        if (!isNaN(parseFloat(valor))) {
                            const num = parseFloat(valor);
                            return num > 0 ? `+${num}` : `${num}`;
                        }
                        return valor;
                    };
                    
                    const odEsfera = formatearValor(data.historial.od_esfera);
                    const odCilindro = formatearValor(data.historial.od_cilindro);
                    const odEje = data.historial.od_eje ? `X${data.historial.od_eje}°` : '';
                    
                    const oiEsfera = formatearValor(data.historial.oi_esfera);
                    const oiCilindro = formatearValor(data.historial.oi_cilindro);
                    const oiEje = data.historial.oi_eje ? `X${data.historial.oi_eje}°` : '';
                    
                    const odMedida = `OD: ${odEsfera} ${odCilindro} ${odEje}`.trim();
                    const oiMedida = `OI: ${oiEsfera} ${oiCilindro} ${oiEje}`.trim();
                    const addInfo = data.historial.add ? `ADD: ${formatearValor(data.historial.add)}` : '';
                    const dpInfo = data.historial.dp ? `DP: ${data.historial.dp}` : '';
                    
                    if (!$('#l_medida').val()) {
                        const medidaCompleta = `${odMedida} / ${oiMedida} ${addInfo} ${dpInfo}`.trim();
                        $('#l_medida').val(medidaCompleta);
                    }
                }
            } else {
                // No se encontraron datos
                const infoMsg = document.createElement('small');
                infoMsg.classList.add('text-muted', 'ml-2', 'info-historial');
                infoMsg.textContent = 'No se encontraron datos en el historial clínico';
                document.getElementById('buscar_historial_clinico').parentNode.appendChild(infoMsg);
                
                setTimeout(() => infoMsg.remove(), 2000);
            }
        };

        // Función para procesar errores del historial clínico - SIMPLIFICADA
        window.procesarErrorHistorialClinico = function(error) {
            limpiarCamposAutocompletado();
            
            const errorMsg = document.createElement('small');
            errorMsg.classList.add('text-warning', 'ml-2', 'error-historial');
            errorMsg.textContent = 'No se encontraron datos del historial clínico';
            document.getElementById('buscar_historial_clinico').parentNode.appendChild(errorMsg);
            
            setTimeout(() => errorMsg.remove(), 3000);
        };

        function calculateTotal() {
            let total = 0;

            // Examen visual
            const examenVisual = parseFloat(document.getElementById('examen_visual').value) || 0;
            total += examenVisual;

            // Armazones - incluir tanto el original como los campos añadidos
            const armazonPrecios = document.querySelectorAll('[name="a_precio"], [name="a_precio[]"]');
            const armazonDescuentos = document.querySelectorAll('[name="a_precio_descuento"], [name="a_precio_descuento[]"]');
            armazonPrecios.forEach((precio, index) => {
                const precioValue = parseFloat(precio.value) || 0;
                const descuento = parseFloat(armazonDescuentos[index]?.value) || 0;
                total += precioValue * (1 - (descuento / 100));
            });

            // Lunas - incluir tanto el original como los campos añadidos
            const lunasPrecios = document.querySelectorAll('[name="l_precio"], [name="l_precio[]"]');
            const lunasDescuentos = document.querySelectorAll('[name="l_precio_descuento"], [name="l_precio_descuento[]"]');
            lunasPrecios.forEach((precio, index) => {
                const precioValue = parseFloat(precio.value) || 0;
                const descuento = parseFloat(lunasDescuentos[index]?.value) || 0;
                total += precioValue * (1 - (descuento / 100));
            });

            // Accesorios - incluir tanto el original como los campos añadidos
            const accesoriosPrecios = document.querySelectorAll('[name="d_precio"], [name="d_precio[]"]');
            const accesoriosDescuentos = document.querySelectorAll('[name="d_precio_descuento"], [name="d_precio_descuento[]"]');
            accesoriosPrecios.forEach((precio, index) => {
                const precioValue = parseFloat(precio.value) || 0;
                const descuento = parseFloat(accesoriosDescuentos[index]?.value) || 0;
                total += precioValue * (1 - (descuento / 100));
            });

            // Valor compra
            const valorCompra = parseFloat(document.getElementById('valor_compra').value) || 0;
            total += valorCompra;

            // Actualizar campos
            document.getElementById('total').value = total.toFixed(2);
            document.getElementById('saldo').value = total.toFixed(2);
        }

        // Event listeners para precios
        ['examen_visual', 'a_precio', 'l_precio', 'd_precio', 'valor_compra'].forEach(id => { // Añadir valor_compra
            const element = document.getElementById(id);
            if(element){
                element.addEventListener('input', calculateTotal);
            }
        });

        // Event listeners para descuentos
        ['a_precio_descuento', 'l_precio_descuento', 'd_precio_descuento'].forEach(id => {
            const element = document.getElementById(id);
            if(element){
                element.addEventListener('input', calculateTotal);
            }
        });

        // Mostrar todas las opciones del datalist al hacer clic en el input - MÉTODO SIMPLE
        $('input[list]').on('click', function() {
            // Forzar que se muestren todas las opciones
            if (this.value === '') {
                this.value = ' ';
                this.value = '';
            }
        });

        // Aplicar el estilo de mayúsculas como en historial clínico
        $('input[type="text"], input[type="email"], textarea').on('input', function() {
            $(this).val($(this).val().toUpperCase());
        });

        function createNewFields(type) {
            let html = '';
            const index = document.querySelectorAll(`[data-${type}-section]`).length;
            
            if (type === 'armazon') {
                html = `
                    <div data-armazon-section class="mt-4">
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove(); calculateTotal();">
                                <i class="fas fa-times"></i> Eliminar
                            </button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Armazón (Inventario)</label>
                                <select class="form-control selectpicker" data-live-search="true" name="a_inventario_id[]">
                                    <option value="">Seleccione un armazón</option>
                                    @foreach($armazones as $armazon)
                                        <option value="{{ $armazon->id }}">
                                            {{ $armazon->codigo }} - {{ $armazon->lugar }} - {{ $armazon->fecha ? \Carbon\Carbon::parse($armazon->fecha)->format('d/m/Y') : 'Sin fecha' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Precio Armazón</label>
                                <input type="number" class="form-control form-control-sm precio-armazon" name="a_precio[]" step="0.01" oninput="calculateTotal()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Desc. Armazón (%)</label>
                                <input type="number" class="form-control form-control-sm descuento-armazon" name="a_precio_descuento[]" min="0" max="100" value="0" oninput="calculateTotal()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto Armazón (Opcional)</label>
                                <input type="file" class="form-control form-control-sm" name="a_foto[]" accept="image/*">
                                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            else if (type === 'lunas') {
                html = `
                    <div data-lunas-section class="mt-4">
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove(); calculateTotal();">
                                <i class="fas fa-times"></i> Eliminar
                            </button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Lunas Medidas</label>
                                <input type="text" class="form-control" name="l_medida[]" oninput="calculateTotal()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Lunas Detalle</label>
                                <input type="text" class="form-control" name="l_detalle[]" oninput="calculateTotal()">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-4">
                                <label class="form-label">Tipo de Lente</label>
                                <input type="text" class="form-control" name="tipo_lente[]" list="tipo_lente_options" 
                                       placeholder="Seleccione o escriba un tipo de lente" oninput="calculateTotal()">
                            </div>
                            <div class="col-md-4">
                                <label class="form-label">Material</label>
                                <input type="text" class="form-control" name="material[]" list="material_options"
                                       placeholder="Seleccione o escriba un material" oninput="calculateTotal()">
                            </div>
                            <div class="col-md.4">
                                <label class="form-label">Filtro</label>
                                <input type="text" class="form-control" name="filtro[]" list="filtro_options"
                                       placeholder="Seleccione o escriba un filtro" oninput="calculateTotal()">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-3">
                                <label class="form-label">Precio Lunas</label>
                                <input type="number" class="form-control input-sm" name="l_precio[]" step="0.01" oninput="calculateTotal()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Desc. Lunas (%)</label>
                                <input type="number" class="form-control input-sm" name="l_precio_descuento[]" 
                                       min="0" max="100" value="0" oninput="calculateTotal()">
                            </div>
                            <div class="col-md-6">
                                <label class="form-label">Foto Lunas (Opcional)</label>
                                <input type="file" class="form-control form-control-sm" name="l_foto[]" accept="image/*">
                                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF</small>
                            </div>
                        </div>
                    </div>
                `;
            }
            else if (type === 'accesorios') {
                html = `
                    <div data-accesorios-section class="mt-4">
                        <hr>
                        <div class="d-flex justify-content-end">
                            <button type="button" class="btn btn-danger btn-sm" onclick="this.parentElement.parentElement.remove(); calculateTotal();">
                                <i class="fas fa-times"></i> Eliminar
                            </button>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-6">
                                <label class="form-label">Accesorio (Inventario)</label>
                                <select class="form-control selectpicker" data-live-search="true" name="d_inventario_id[]">
                                    <option value="" selected>Seleccione un Item del Inventario</option>
                                    @foreach ($accesorios as $item)
                                        <option value="{{ $item->id }}">
                                            {{ $item->codigo }} - {{ $item->lugar }} - {{ $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : 'Sin fecha' }}
                                        </option>
                                    @endforeach
                                </select>
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Precio Accesorio</label>
                                <input type="number" class="form-control input-sm" name="d_precio[]" step="0.01" oninput="calculateTotal()">
                            </div>
                            <div class="col-md-3">
                                <label class="form-label">Desc. Accesorio (%)</label>
                                <input type="number" class="form-control input-sm" name="d_precio_descuento[]" min="0" max="100" value="0" oninput="calculateTotal()">
                            </div>
                        </div>
                        <div class="row mb-3">
                            <div class="col-md-12">
                                <label class="form-label">Foto Accesorio (Opcional)</label>
                                <input type="file" class="form-control form-control-sm" name="d_foto[]" accept="image/*">
                                <small class="form-text text-muted">Formatos permitidos: JPG, PNG, GIF</small>
                            </div>
                        </div>
                    </div>
                `;
            }

            const container = document.querySelector(`#${type}-container .card-body`);
            container.insertAdjacentHTML('beforeend', html);

            // Agregar event listeners a los nuevos campos
            if (type === 'armazon') {
                const newSection = container.lastElementChild;
                const newPrecioInput = newSection.querySelector('.precio-armazon');
                const newDescuentoInput = newSection.querySelector('.descuento-armazon');
                
                newPrecioInput.addEventListener('input', calculateTotal);
                newDescuentoInput.addEventListener('input', calculateTotal);
            }
            
            // Aplicar el comportamiento simple de datalist a los nuevos campos también
            $('input[list]').off('click').on('click', function() {
                if (this.value === '') {
                    this.value = ' ';
                    this.value = '';
                }
            });
            
            $('.selectpicker').selectpicker('refresh'); // Reevaluar el nuevo select
        }

        function duplicateArmazon() {
            createNewFields('armazon');
        }

        function duplicateLunas() {
            createNewFields('lunas');
            calculateTotal(); // recalcular total al agregar más lunas
        }

        function duplicateAccesorios() {
            createNewFields('accesorios');
            calculateTotal(); // recalcular total con el nuevo accesorio
        }
    </script>
    <link rel="stylesheet" href="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/css/bootstrap-select.min.css">
    <script src="https://cdn.jsdelivr.net/npm/bootstrap-select@1.13.18/dist/js/bootstrap-select.min.js"></script>
    <script>
        $(function() {
            $('.selectpicker').selectpicker();
        });
    </script>
@stop
