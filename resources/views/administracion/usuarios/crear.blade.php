@extends('adminlte::page')

@section('title', 'CREAR USUARIO')

@section('content_header')
   
@stop

@section('content')
  
<div class="card">
        <div class="card-header">
          <h3 class="card-title">CREAR USUARIO</h3>

          <div class="card-tools">
            <button type="button" class="btn btn-tool" data-card-widget="collapse" data-toggle="tooltip" title="COLLAPSE">
              <i class="fas fa-minus"></i></button>
            <button type="button" class="btn btn-tool" data-card-widget="remove" data-toggle="tooltip" title="REMOVE">
              <i class="fas fa-times"></i></button>
          </div>
        </div>
        <div class="card-body">
        <div class="col-md-6">
                <form role="form" action="{{route('configuracion.usuarios.store')}}" method="POST">
                        @csrf
                        
                        <div class ="form-group">
                                <label>NOMBRE</label>
                                <input name="nombre" required type="text" class="form-control">
                        </div>
                        <div class ="form-group">
                                <label>USUARIO</label>
                                <input name="user" required type="text" class="form-control">
                        </div>
                        <div class ="form-group">
                            <label>CONTRASEÑA</label>
                            <input name="password" required type="password" class="form-control">
                    </div>
                        <div class ="form-group">
                                <label>E-MAIL</label>
                                <input name="email" required type="mail" class="form-control" >
                        </div>
                        <div class ="form-group">
                                <label>ACTIVO</label>
                                <select id="activo" name="activo" class="form-control">
                                <option value="1">ACTIVO</option>
                                <option value="0">INACTIVO</option>
                               
                                </select>
                        </div>
                <button type="button" class="btn btn-primary pull-left" data-toggle="modal" data-target="#modal">CREAR USUARIO</button>
  <div class="modal fade" id="modal">
    <div class="modal-dialog">
      <div class="modal-content">
        <div class="modal-header">
          
          <h4 class="modal-title">CREAR USUARIO</h4>
          <button type="button" class="close" data-dismiss="modal" aria-label="Close">
            <span aria-hidden="true">&times;</span></button>
        </div>
        <div class="modal-body">
          <p>¿ESTÁ SEGURO QUE QUIERE GUARDAR LOS CAMBIOS?</p>
        </div>
        <div class="modal-footer">
          <button type="button" class="btn btn-default pull-left" data-dismiss="modal">CANCELAR</button>
          <button type="submit" class="btn btn-primary">GUARDAR CAMBIOS</button>
        </div>
      </div>
      <!-- /.modal-content -->
    </div>
    <!-- /.modal-dialog -->
        </form>  
        </div>
              
              
          <!-- Fin contenido -->
        </div>
        <!-- /.card-body -->
        <div class="card-footer">
        CREAR USUARIO
        </div>
        <!-- /.card-footer-->
      </div>
    
@stop

@section('js')
   
@stop

@section('footer')
   <div class="float-right d-none d-sm-block">
        <b>VERSIÓN</b> @version('compact')       
    </div>
@stop