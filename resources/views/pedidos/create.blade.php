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
                <form action="{{ route('pedidos.store') }}" method="POST" id="pedidoForm">
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
                                <div class="col-md-6">
                                    <label for="fact" class="form-label">Factura</label>
                                    <input type="text" class="form-control" id="fact" name="fact"
                                           value="Pendiente">
                                </div>
                                <div la="col-md-6">
                                    <label for="cliente" class="form-label">Cliente</label>
                                    <input type="text" class="form-control" id="cliente" name="cliente" required>
                                </div>
                            </div>

                            {{-- Nueva fila para cédula --}}
                            <div class="row mb-3">
                                <div class="col-md-6">
                                    <label for="cedula" class="form-label">Cédula</label>
                                    <input type="text" class="form-control" id="cedula" name="cedula">
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
                                    <input type="number" class="form-control form-control-sm" id="examen_visual" name="examen_visual">
                                </div>
                                <div class="col-md-3">
                                    <label for="celular" class="form-label">Celular</label>
                                    <input type="text" class="form-control" id="celular" name="celular">
                                </div>
                                <div class="col-md-3">
                                    <label for="correo_electronico" class="form-label">Correo Electrónico</label>
                                    <input type="email" class="form-control" id="correo_electronico" name="correo_electronico">
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
                                                {{ $armazon->codigo }} - {{ $armazon->lugar }}
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
                                            <option value="{{ $item->id }}">{{ $item->codigo }} - {{ $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : 'Sin fecha' }}</option>
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

        // Mostrar todas las opciones del datalist al hacer clic en el input
        document.querySelectorAll('input[list]').forEach(input => {
            input.addEventListener('click', function() {
                this.setAttribute('list', this.getAttribute('list'));
            });
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
                                            {{ $armazon->codigo }} - {{ $armazon->lugar }}
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
                                        <option value="{{ $item->id }}">{{ $item->codigo }} - {{ $item->fecha ? \Carbon\Carbon::parse($item->fecha)->format('d/m/Y') : 'Sin fecha' }}</option>
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
