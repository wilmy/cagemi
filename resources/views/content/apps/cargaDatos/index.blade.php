@extends('layouts/contentLayoutMaster')

@section('title', 'Carga de datos')

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
          <h1 class="role-title">Carga de Datos</h1>
        </div>
       
        <form id="addRoleForm" method="POST" class="row" enctype="multipart/form-data" action="{{route('admin.cargaDatos.store')}}">
          @csrf

            @if (isset($message))
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($message as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group row">
                <div class="mb-1 col-md-4">
                  <label for="register-apellido" class="form-label">Plantilla</label>
                  <input type="file" class="form-control" id="logo" name="archivo" accept=".csv, application/vnd.openxmlformats-officedocument.spreadsheetml.sheet, application/vnd.ms-excel" />
                  
                </div> 
            </div>

          <div class="col-4  mt-2">
            <button type="submit" class="btn btn-primary me-1">Guardar</button>
            <a href="{{route('admin.grupoEmpresarial.index')}}" class="btn btn-outline-danger">
              Cancelar
            </a>
          </div>
        </form>
        <!--/ Add role form -->
      </div>

      @if(isset($array_data))
        @if(count($array_data) > 0)
          <hr>
          <div class="card-body mt-2">
            <h2>Lista de datos cargados</h2>
            <div class="col-md-12 mb-10 table-responsive">
              <table class="table table-striped- table-bordered table-hover table-responsive">
                <thead>
                  <tr>
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
                    if($valores->empresa == '' || $valores->cod_empleado == '' ||
                       $valores->nombres == '' || $valores->apellidos == '' ||
                       $valores->posicion == '' || $valores->direccion_vp == '' ||
                       $valores->departamento == '' || $valores->documento == '') 
                    {
                      $colorClass = 'class="alert-danger" style="color: red"';
                    }

                    $title = '';
                    foreach($array_data as $valida)
                    {
                      if($valida->id != $valores->id)
                      {
                        if(($valida->empresa == $valores->empresa && $valida->cod_empleado == $valores->cod_empleado) ||
                          ($valida->empresa == $valores->empresa && $valida->documento == $valores->documento))
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $title = ' title="Existen datos repetidos para la misma empresa"';
                          break;
                        }
                      }
                    }
                  @endphp
                  <tr {!!$colorClass!!} {!!$title!!}>
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
                    <th>{{$valores->fecha_nacimiento->format('d/m/Y')}}</th>
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
        </div>
        @endif
      @endif
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