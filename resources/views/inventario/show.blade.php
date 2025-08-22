@extends('adminlte::page')

@section('title', 'Ver Artículo - Inventario')

@section('content_header')
    <h1>VER ARTÍCULO</h1>
    <p>DETALLE DEL ARTÍCULO DE INVENTARIO</p>
    @if (session('error'))
        <div class="alert {{ session('tipo') }} alert-dismissible fade show" role="alert">
            <strong>{{ session('error') }}</strong> {{ session('mensaje') }}
            <button type="button" class="close" data-dismiss="alert" aria-label="Close">
                <span aria-hidden="true">&times;</span>
            </button>
        </div>
    @endif
@stop

@section('content_header')
    <h1>VER ARTÍCULO</h1>
    <p>DETALLE DEL ARTÍCULO DE INVENTARIO</p>
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
        body, 
        .content-wrapper, 
        .main-header, 
        .main-sidebar, 
        .card-title,
        .info-box-text,
        .info-box-number,
        .custom-select,
        .btn,
        label,
        input,
        select,
        option,
        datalist,
        datalist option,
        .form-control,
        p,
        h1, h2, h3, h4, h5, h6,
        th,
        td,
        span,
        a,
        .dropdown-item,
        .alert,
        .modal-title,
        .modal-body p,
        .modal-content,
        .card-header,
        .card-footer,
        button,
        .close,
        strong {
            text-transform: uppercase !important;
        }

        /* Estilos para campos de solo lectura */
        .readonly-field {
            background-color: #f8f9fa !important;
            border: 1px solid #e9ecef !important;
            color: #495057 !important;
            cursor: not-allowed !important;
        }
    </style>

    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">VER ARTÍCULO</h3>
            <div class="card-tools">
                <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="Collapse">
                    <i class="fas fa-minus"></i>
                </button>
                <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="Remove">
                    <i class="fas fa-times"></i>
                </button>
            </div>
        </div>
        <div class="card-body">
            <div class="col-md-12">
                <div class="row">
                    <div class="col-md-6">
                        <div class="card card-primary">
                            <div class="card-header">
                                <h3 class="card-title">Información del Artículo</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="codigo">Código</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-barcode"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field text-uppercase" value="{{ $inventario->codigo }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="cantidad">Cantidad</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->cantidad }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="fecha">Fecha</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->fecha }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="empresa">SUCURSAL</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-building"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->empresa ? $inventario->empresa->nombre : 'SIN ASIGNAR' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="valor">Valor</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->valor ?? 'NO ESPECIFICADO' }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="orden">Orden</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->orden ?? 'SIN ORDEN' }}" readonly>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>
                    <div class="col-md-6">
                        <div class="card card-info">
                            <div class="card-header">
                                <h3 class="card-title">Ubicación del Artículo</h3>
                            </div>
                            <div class="card-body">
                                <div class="form-group">
                                    <label for="lugar">Lugar</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-map-marker-alt"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->lugar }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="columna">Columna</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->columna }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="numero">Número</label>
                                    <div class="input-group">
                                        <div class="input-group-prepend">
                                            <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                        </div>
                                        <input type="text" class="form-control readonly-field" value="{{ $inventario->numero }}" readonly>
                                    </div>
                                </div>
                                <div class="form-group">
                                    <label for="foto">FOTO DEL ARTÍCULO</label>
                                    @if($inventario->foto)
                                        <div class="text-center">
                                            <img src="{{ asset($inventario->foto) }}" alt="Foto del artículo {{ $inventario->codigo }}" class="img-thumbnail" style="max-height: 300px; cursor: pointer;" data-toggle="modal" data-target="#fotoModal">
                                            <p class="text-muted small mt-2">CLIC EN LA IMAGEN PARA AMPLIAR</p>
                                        </div>
                                    @else
                                        <div class="text-center">
                                            <div class="alert alert-info">
                                                <i class="fas fa-camera fa-3x mb-2"></i>
                                                <p class="mb-0">SIN FOTO DISPONIBLE</p>
                                            </div>
                                        </div>
                                    @endif
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

                <div class="form-group mt-3">
                    <a href="{{ route('inventario.edit', $inventario->id) }}" class="btn btn-warning">
                        <i class="fas fa-edit"></i> Editar Artículo
                    </a>
                    <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                        <i class="fas fa-list"></i> Volver al Listado
                    </a>
                </div>

                <!-- Modal para ampliar la foto -->
                @if($inventario->foto)
                    <div class="modal fade" id="fotoModal" tabindex="-1" role="dialog" aria-labelledby="fotoModalLabel" aria-hidden="true">
                        <div class="modal-dialog modal-lg" role="document">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h5 class="modal-title" id="fotoModalLabel">FOTO DEL ARTÍCULO: {{ $inventario->codigo }}</h5>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body text-center">
                                    <img src="{{ asset($inventario->foto) }}" alt="Foto del artículo {{ $inventario->codigo }}" class="img-fluid">
                                </div>
                                <div class="modal-footer">
                                    <button type="button" class="btn btn-secondary" data-dismiss="modal">
                                        <i class="fas fa-times"></i> Cerrar
                                    </button>
                                </div>
                            </div>
                        </div>
                    </div>
                @endif
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        VER ARTÍCULO - ID: {{ $inventario->id }}
    </div>
    <!-- /.card-footer-->
@stop

@section('js')
<script>
document.addEventListener('keydown', function(event) {
    if (event.key === "Home") { // Verifica si la tecla presionada es 'Inicio'
        window.location.href = '/dashboard'; // Redirecciona a '/dashboard'
    }
});
</script>

@stop

@section('footer')
    <div class="float-right d-none d-sm-block">
        <b>Version</b> @version('compact')
    </div>
@stop
