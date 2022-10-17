@extends('layouts/contentLayoutMaster')

@section('title', 'Validacion de datos')

@section('vendor-style')
  <!-- Vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
@endsection
@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')

    <div class="modal-content">
      <div class="modal-body px-5 pb-5">
        <div class="text-left mb-4">
          <h1 class="role-title">Validacion</h1>
        </div>
       
        <form id="addRoleForm" method="POST" class="row" enctype="multipart/form-data" action="">
          @csrf

            @if(isset($array_data))
              @if(count($array_data) > 0)
                  <div class="col-md-12 mb-10 table-responsive">
                    <table class="table table-striped- table-bordered table-hover table-responsive">
                      <thead>
                        <tr>
                          <th></th>
                          <th>Fila</th>
                          <th>empresa</th>
                          <th>cod_empleado</th>
                          <th>nombres</th>
                          <th>apellidos</th>
                          <th>posicion</th>
                          <th>direccion_vp</th>
                          <th>departamento</th>
                          <th>telefono_movil</th>
                          <th>extencion</th>
                          <th>correo_instutucional</th>
                          <th>correo_personal</th>
                          <th>documento</th>
                          <th>fecha_nacimiento</th>
                          <th>codigo_superfisor</th>
                        </tr>
                      </thead>
                      @php $fila = 2; @endphp
                      @foreach($array_data as $valores)

                        @php
                          $colorClass = '';
                          $checkedInput = 0;
                          if($valores->empresa == '' || $valores->cod_empleado == '' ||
                            $valores->nombres == '' || $valores->apellidos == '' ||
                            $valores->posicion == '' || $valores->direccion_vp == '' ||
                            $valores->departamento == '' || $valores->documento == '') 
                          {
                            $colorClass = 'class="alert-danger" style="color: red"';
                            $checkedInput = 1;
                          }

                        @endphp
                        <tr {!!$colorClass!!}>
                          <th>
                            @if($checkedInput != 1)
                              <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="validacionDataos[]" 
                                checked="checked"
                                value="{{ $valores->id }}"/>
                            @endif
                          </th>
                          <th>{{$fila}}</th>
                          <th>{{$valores->empresa}}</th>
                          <th>{{$valores->cod_empleado}}</th>
                          <th>{{$valores->nombres}}</th>
                          <th>{{$valores->apellidos}}</th>
                          <th>{{$valores->posicion}}</th>
                          <th>{{$valores->direccion_vp}}</th>
                          <th>{{$valores->departamento}}</th>
                          <th>{{$valores->telefono_movil}}</th>
                          <th>{{$valores->extencion}}</th>
                          <th>{{$valores->correo_instutucional}}</th>
                          <th>{{$valores->correo_personal}}</th>
                          <th>{{$valores->documento}}</th>
                          <th>{{$valores->fecha_nacimiento}}</th>
                          <th>{{$valores->codigo_superfisor}}</th>
                        </tr>
                        @php $fila++; @endphp
                      @endforeach
                    </table>
                  </div>
                <br><br>
                <div class="col-md-12 mt-20">
                  {{$array_data->render()}}
                </div> 
              @endif
            @endif
          </form>
        </div>
    </div>


@endsection

@section('vendor-script')
  <!-- Vendor js files -->
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.checkboxes.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
