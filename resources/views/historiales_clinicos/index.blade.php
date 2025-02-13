@extends('adminlte::page')

@section('title', 'HISTORIALES CLÍNICOS')

@section('content_header')
<h1>HISTORIALES CLÍNICOS</h1>
<p>ADMINISTRACIÓN DE HISTORIALES CLÍNICOS</p>
@if (session('error'))
<div class="alert {{ session('tipo') }} alert-dismissible fade show" role="alert">
    <strong>{{ strtoupper(session('mensaje')) }}</strong>
    <button type="button" class="close" data-dismiss="alert" aria-label="Close">
        <span aria-hidden="true">&times;</span>
    </button>
</div>
@endif
@stop

@section('content')

<div class="card">
    <div class="card-body">
        {{-- Filtros de Mes y Año --}}
        <div class="row mb-3">
            <div class="col-md-6">
                <form action="{{ route('historiales_clinicos.index') }}" method="GET" class="form-inline">
                    <div class="form-group mr-2">
                        <label for="mes" class="mr-2">MES:</label>
                        <select name="mes" id="mes" class="form-control">
                            @for ($i = 1; $i <= 12; $i++)
                                <option value="{{ str_pad($i, 2, '0', STR_PAD_LEFT) }}" {{ request('mes', date('m')) == str_pad($i, 2, '0', STR_PAD_LEFT) ? 'selected' : '' }}>
                                    {{ strtoupper(date('F', mktime(0, 0, 0, $i, 1))) }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <div class="form-group mr-2">
                        <label for="ano" class="mr-2">AÑO:</label>
                        <select name="ano" id="ano" class="form-control">
                            @for ($i = date('Y') - 2; $i <= date('Y') + 2; $i++)
                                <option value="{{ $i }}" {{ request('ano', date('Y')) == $i ? 'selected' : '' }}>
                                    {{ $i }}
                                </option>
                            @endfor
                        </select>
                    </div>
                    <button type="submit" class="btn btn-primary">FILTRAR</button>
                </form>
            </div>
        </div>

        {{-- Botón Añadir Historial Clínico --}}
        <div class="btn-group mb-3">
            <a type="button" class="btn btn-success" href="{{ route('historiales_clinicos.create') }}">
                AÑADIR HISTORIAL CLÍNICO
            </a>
        </div>

        <div class="table-responsive">
            <table id="historialesTable" class="table table-striped table-bordered">
                <thead>
                    <tr>
                        <th>ID</th>
                        <th>NOMBRES</th>
                        <th>APELLIDOS</th>
                        <th>FECHA</th>
                        <th>MOTIVO CONSULTA</th>
                        <th>USUARIO</th>
                        <th>ACCIONES</th>
                    </tr>
                </thead>
                <tbody>
                    @foreach ($historiales as $index => $historial)
                    <tr>
                        <td>{{ $index + 1 }}</td>
                        <td>{{ strtoupper($historial->nombres) }}</td>
                        <td>{{ strtoupper($historial->apellidos) }}</td>
                        <td>{{ $historial->fecha }}</td>
                        <td>{{ strtoupper($historial->motivo_consulta) }}</td>
                        <td>{{ strtoupper($historial->usuario->name ?? 'N/A') }}</td>
                        <td>
                            <a href="{{ route('historiales_clinicos.edit', $historial->id) }}"
                                class="btn btn-xs btn-default text-warning mx-1 shadow" 
                                title="EDITAR">
                                <i class="fa fa-lg fa-fw fa-pen"></i>
                            </a>
                            <a class="btn btn-xs btn-default text-danger mx-1 shadow" 
                               href="#" 
                               data-toggle="modal"
                               data-target="#confirmarEliminarModal" 
                               data-id="{{ $historial->id }}"
                               data-url="{{ route('historiales_clinicos.destroy', $historial->id) }}"
                               title="ELIMINAR">
                                <i class="fa fa-lg fa-fw fa-trash"></i>
                            </a>
                        </td>
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
</div>

{{-- Modal de Confirmación de Eliminación --}}
<div class="modal fade" id="confirmarEliminarModal" tabindex="-1" role="dialog" aria-labelledby="confirmarEliminarModalLabel" aria-hidden="true">
    <div class="modal-dialog" role="document">
        <div class="modal-content">
            <div class="modal-header">
                <h5 class="modal-title" id="confirmarEliminarModalLabel">CONFIRMAR ELIMINACIÓN</h5>
                <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                    <span aria-hidden="true">&times;</span>
                </button>
            </div>
            <div class="modal-body">
                ¿ESTÁ SEGURO DE QUE DESEA ELIMINAR ESTE HISTORIAL CLÍNICO?
            </div>
            <div class="modal-footer">
                <form id="eliminarForm" method="POST" style="display: inline-block;">
                    @csrf
                    @method('DELETE')
                    <button type="button" class="btn btn-secondary" data-dismiss="modal">CANCELAR</button>
                    <button type="submit" class="btn btn-danger">ELIMINAR</button>
                </form>
            </div>
        </div>
    </div>
</div>
@stop

@section('css')
<style>
    /* Convertir todo el texto a mayúsculas */
    .card-title,
    .card-header,
    .table th,
    .table td,
    .alert,
    h1, h2, h3, h4, h5,
    p,
    .btn,
    .modal-title,
    .modal-body,
    .dataTables_wrapper .dataTables_filter,
    .dataTables_wrapper .dataTables_info,
    .dataTables_wrapper .dataTables_length,
    .dataTables_wrapper .dataTables_paginate,
    .buttons-html5,
    .buttons-print {
        text-transform: uppercase !important;
    }
</style>
@stop

@section('js')
<script>
    $(document).ready(function() {
        // Inicializar DataTable
        $('#historialesTable').DataTable({
            "order": [[0, "desc"]],
            "columnDefs": [{
                "targets": [2],
                "visible": true,
                "searchable": true,
            }],
            "dom": 'Bfrtip',
            "paging": false,
            "lengthChange": false,
            "info": false,
            "processing": false,
            "serverSide": false,
            "buttons": [
                'excelHtml5',
                'csvHtml5',
                {
                    "extend": 'print',
                    "text": 'IMPRIMIR',
                    "autoPrint": true,
                    "exportOptions": {
                        "columns": [0, 1, 2, 3]
                    },
                    "customize": function(win) {
                        $(win.document.body).css('font-size', '16pt');
                        $(win.document.body).find('table')
                            .addClass('compact')
                            .css('font-size', 'inherit');
                    }
                },
                {
                    "extend": 'pdfHtml5',
                    "text": 'PDF',
                    "filename": 'HISTORIALES_CLINICOS.pdf',
                    "pageSize": 'LETTER',
                    "exportOptions": {
                        "columns": [0, 1, 2, 3]
                    }
                }
            ],
            "language": {
                "url": "//cdn.datatables.net/plug-ins/1.10.16/i18n/Spanish.json"
            }
        });

        // Convertir a mayúsculas los textos del DataTable
        $('.dataTables_wrapper').find('label, .dataTables_info').css('text-transform', 'uppercase');

        // Modal de eliminación
        $('#confirmarEliminarModal').on('show.bs.modal', function(event) {
            var button = $(event.relatedTarget);
            var url = button.data('url');
            var modal = $(this);
            modal.find('#eliminarForm').attr('action', url);
        });
    });
</script>
@stop
