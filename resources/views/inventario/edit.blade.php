@extends('adminlte::page')

@section('title', 'Editar Articulo')

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
        .close {
            text-transform: uppercase !important;
        }

        /* Asegurar que el placeholder también esté en mayúsculas */
        input::placeholder {
            text-transform: uppercase !important;
        }

        /* Asegurar que las opciones del datalist estén en mayúsculas */
        datalist option {
            text-transform: uppercase !important;
        }
    </style>

    <br>
    <div class="card">
        <div class="card-header">
            <h3 class="card-title">EDITAR ARTÍCULO</h3>
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
                <form role="form" action="{{ route('inventario.update', $inventario->id) }}" method="POST" enctype="multipart/form-data">
                    @csrf
                    @method('PUT')

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
                                            <input name="codigo" id="codigo" required type="text" class="form-control text-uppercase" style="text-transform:uppercase" oninput="this.value = this.value.toUpperCase()" value="{{ $inventario->codigo }}" placeholder="CÓDIGO DEL ARTÍCULO">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="cantidad">Cantidad</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-cubes"></i></span>
                                            </div>
                                            <input name="cantidad" id="cantidad" required type="number" class="form-control" value="{{ $inventario->cantidad }}" min="0">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="fecha">Fecha</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-calendar-alt"></i></span>
                                            </div>
                                            <input name="fecha" id="fecha" required type="date" class="form-control" value="{{ $inventario->fecha }}">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="empresa_id">SUCURSAL</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-building"></i></span>
                                            </div>
                                            <select name="empresa_id" id="empresa_id" class="form-control" {{ !auth()->user()->is_admin && isset($userEmpresaId) ? 'readonly disabled' : '' }}>
                                                <option value="">Seleccione una Empresa</option>
                                                @foreach ($empresas as $empresa)
                                                    <option value="{{ $empresa->id }}" 
                                                        {{ 
                                                            (isset($userEmpresaId) && $userEmpresaId == $empresa->id) || 
                                                            (!isset($userEmpresaId) && $inventario->empresa_id == $empresa->id) ? 'selected' : '' 
                                                        }}>
                                                        {{ $empresa->nombre }}
                                                    </option>
                                                @endforeach
                                            </select>
                                            @if(!auth()->user()->is_admin && isset($userEmpresaId))
                                                <input type="hidden" name="empresa_id" value="{{ $userEmpresaId }}">
                                            @endif
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="valor">Valor</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-dollar-sign"></i></span>
                                            </div>
                                            <input name="valor" id="valor" type="number" class="form-control" value="{{ $inventario->valor }}" step="0.01">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="orden">Orden</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                            </div>
                                            <input name="orden" id="orden" type="text" class="form-control" value="{{ $inventario->orden }}" placeholder="ORDEN DEL ARTÍCULO">
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
                                            <input list="lugares" name="lugar" id="lugar" class="form-control" required value="{{ $inventario->lugar }}" placeholder="SELECCIONE O ESCRIBA UN LUGAR">
                                            <datalist id="lugares">
                                                <option value="Soporte">
                                                <option value="Vitrina">
                                                <option value="Estuches">
                                                <option value="Cosas Extras">
                                                <option value="Armazones Extras">
                                                <option value="Líquidos">
                                                <option value="Goteros">
                                            </datalist>
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="columna">Columna</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-columns"></i></span>
                                            </div>
                                            <input name="columna" id="columna" required type="text" class="form-control" value="{{ $inventario->columna }}" placeholder="EJ: A, B, 1, 2...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="numero">Número</label>
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-sort-numeric-down"></i></span>
                                            </div>
                                            <input name="numero" id="numero" class="form-control" required type="number" value="{{ $inventario->numero }}" placeholder="EJ: 1, 2, 3...">
                                        </div>
                                    </div>
                                    <div class="form-group">
                                        <label for="foto">FOTO DEL ARTÍCULO</label>
                                        @if($inventario->foto)
                                            <div class="mb-2">
                                                <img src="{{ asset($inventario->foto) }}" alt="Foto actual" class="img-thumbnail" style="max-height: 150px;">
                                                <p class="text-muted small">FOTO ACTUAL</p>
                                            </div>
                                        @endif
                                        <div class="input-group">
                                            <div class="input-group-prepend">
                                                <span class="input-group-text"><i class="fas fa-camera"></i></span>
                                            </div>
                                            <input type="file" name="foto" id="foto" class="form-control" accept="image/*">
                                        </div>
                                        <small class="form-text text-muted">FORMATOS PERMITIDOS: JPG, PNG, GIF (MÁX. 2MB). DEJE EN BLANCO PARA MANTENER LA FOTO ACTUAL.</small>
                                    </div>
                                </div>
                            </div>
                        </div>
                    </div>

                    <div class="form-group">
                        <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#modal">
                            <i class="fas fa-save"></i> Editar Artículo
                        </button>
                        <a href="{{ route('inventario.index') }}" class="btn btn-secondary">
                            <i class="fas fa-times-circle"></i> Cancelar
                        </a>
                    </div>

                    <div class="modal fade" id="modal">
                        <div class="modal-dialog">
                            <div class="modal-content">
                                <div class="modal-header">
                                    <h4 class="modal-title">Confirmar Edición</h4>
                                    <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                        <span aria-hidden="true">&times;</span>
                                    </button>
                                </div>
                                <div class="modal-body">
                                    <p>¿Está seguro de que desea guardar los cambios en este artículo?</p>
                                </div>
                                <div class="modal-footer justify-content-between">
                                    <button type="button" class="btn btn-default" data-dismiss="modal">Cancelar</button>
                                    <button type="submit" class="btn btn-primary"><i class="fas fa-save"></i> Guardar Cambios</button>
                                </div>
                            </div>
                        </div>
                    </div>
                </form>
            </div>
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        EDITAR ARTÍCULO
    </div>
    <!-- /.card-footer-->
                </form>
            </div>

            <br>
            <!-- Fin contenido -->
        </div>
    </div>
    <!-- /.card-body -->
    <div class="card-footer">
        Editar Articulo
    </div>
    <!-- /.card-footer-->
    </div>

@stop

@section('js')
<script></script>
    // Agrega un 'event listener' al documento para escuchar eventos de teclado
@stop

@section('js')
<script>
document.addEventListener('keydown', function(event) {
    if (event.key === "Home") { // Verifica si la tecla presionada es 'Inicio'
        window.location.href = '/dashboard'; // Redirecciona a '/dashboard'
    }
});

document.addEventListener('DOMContentLoaded', function() {
    const lugarSelect = document.getElementById('lugar');
    const newLugarInput = document.getElementById('newLugar');

    lugarSelect.addEventListener('change', function() {
        if (this.value === 'new') {
            newLugarInput.style.display = 'block';
        } else {
            newLugarInput.style.display = 'none';
        }
    });
});
</script>

@stop

@section('footer')
    <div class="float-right d-none d-sm-block">
        <b>Version</b> @version('compact')
    </div>
@stop