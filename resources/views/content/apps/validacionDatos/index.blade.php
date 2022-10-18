@extends('layouts/contentLayoutMaster')

@section('title', 'Validacion de datos')

@section('vendor-style')
  <!-- vendor css files -->
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/wizard/bs-stepper.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
@endsection

@section('page-style')
  <!-- Page css files -->
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-wizard.css')) }}">
@endsection

@section('content')

@php 
$lis_empresa = '';
$lis_direc = '';
$lis_depart = '';
$lis_posicio = '';
$lis_empleado = '';
if(isset($_GET['vicp']))
{
  if($_GET['vicp'] == 0)
  {
    $lis_empresa = 'active';
  }
  else if($_GET['vicp'] == 1)
  {
    $lis_direc = 'active';
  }
  else if($_GET['vicp'] == 2)
  {
    $lis_depart = 'active';
  }
  else if($_GET['vicp'] == 3)
  {
    $lis_posicio = 'active';
  }
  else if($_GET['vicp'] == 4)
  {
    $lis_empleado = 'active';
  }
  else 
  {
    $lis_empresa = 'active';
  }
}
else 
{
  $lis_empresa = 'active';
}
@endphp


<!-- Horizontal Wizard -->
<section class="horizontal-wizard">
  <div class="bs-stepper horizontal-wizard-example">
    <div class="bs-stepper-header" role="tablist">
      <div class="step {{ $lis_empresa }}" data-target=".empresas" role="tab" id="account-details-trigger">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-box">1</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Empresas</span>
            <span class="bs-stepper-subtitle">Setup Account Details</span>
          </span>
        </button>
      </div>
      <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
      </div>
      <div class="step {{$lis_direc}}" data-target=".direccion_vicepresidencias" role="tab" id="personal-info-trigger">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-box">2</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Direcciones / Vicepresidencias</span>
            <span class="bs-stepper-subtitle">Add Personal Info</span>
          </span>
        </button>
      </div>
      <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
      </div>
      <div class="step {{$lis_depart}}" data-target=".departamentos" role="tab" id="address-step-trigger">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-box">3</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Departamentos</span>
            <span class="bs-stepper-subtitle">Add Address</span>
          </span>
        </button>
      </div>
      <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
      </div>
      <div class="step {{$lis_posicio}}" data-target=".posiciones" role="tab" id="social-links-trigger">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-box">4</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Posiciones</span>
            <span class="bs-stepper-subtitle">Add Social Links</span>
          </span>
        </button>
      </div>
      <div class="line">
        <i data-feather="chevron-right" class="font-medium-2"></i>
      </div>
      <div class="step {{$lis_empleado}}" data-target=".empleados" role="tab" id="social-links-trigger">
        <button type="button" class="step-trigger">
          <span class="bs-stepper-box">5</span>
          <span class="bs-stepper-label">
            <span class="bs-stepper-title">Empleados</span>
            <span class="bs-stepper-subtitle">Add Social Links</span>
          </span>
        </button>
      </div>
    </div>

   
    <div class="bs-stepper-content">
      <div id="account-details" class="content empresas {{ $lis_empresa }}" role="tabpanel" aria-labelledby="account-details-trigger">
        <div class="content-header">
          <h5 class="mb-0">Empresas</h5>
          <small class="text-muted">Enter Your Account Details.</small>
        </div>
        
        <form id="addRoleForm" method="POST" class="row" action="{{route('admin.empresas.store')}}">
          @csrf

          @if(isset($data_empresas))
            @if(count($data_empresas) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>empresa</th>
                      </tr>
                    </thead>
                    @foreach($data_empresas as $valores)
                      <tr>
                        <th>
                          <input 
                            class="form-check-input" 
                            type="checkbox" 
                            name="empresas[]" 
                            checked="checked"
                            value="{{ $valores->empresa }}"/>
                        </th>
                        <th>{{$valores->empresa}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif

        <div class="d-flex justify-content-between">
          <button class="btn btn-outline-secondary btn-prev" disabled>
            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
          </button>
          <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">Next</span>
            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
          </button>
        </div>
        
      </form>
      </div>
      <div id="personal-info" class="content direccion_vicepresidencias {{$lis_direc}}" role="tabpanel" aria-labelledby="personal-info-trigger">
        <div class="content-header">
          <h5 class="mb-0">Direcciones/Vicepresidencias</h5>
          <small>Enter Your Personal Info.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="">
          @csrf

          @if(isset($data_direcciones_vicepre))
            @if(count($data_direcciones_vicepre) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Direcciones/Vicepresidencias</th>
                      </tr>
                    </thead>
                    @foreach($data_direcciones_vicepre as $valores)
                      <tr>
                        <th>
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              name="direccion_vp[]" 
                              checked="checked"
                              value="{{ $valores->id }}"/>
                        </th>
                        <th>{{$valores->direccion_vp}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif
        </form>
        <div class="d-flex justify-content-between">
          <a href="?vicp=0" class="btn btn-primary btn-prev">
            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
          </a>
          <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">Next</span>
            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
          </button>
        </div>
      </div>

      <div id="address-step" class="content departamentos {{$lis_depart}}" role="tabpanel" aria-labelledby="address-step-trigger">
        <div class="content-header">
          <h5 class="mb-0">Departamentos</h5>
          <small>Enter Your Address.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="">
          @csrf

          @if(isset($data_departamentos))
            @if(count($data_departamentos) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Departamentos</th>
                      </tr>
                    </thead>
                    @foreach($data_departamentos as $valores)
                      <tr>
                        <th>
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              name="departamentos[]" 
                              checked="checked"
                              value="{{ $valores->id }}"/>
                        </th>
                        <th>{{$valores->departamento}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif
        </form>
        <div class="d-flex justify-content-between">
          <a href="?vicp=1" class="btn btn-primary btn-prev">
            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
          </a>
          <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">Next</span>
            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
          </button>
        </div>
      </div>

      
      <div id="address-step" class="content posiciones {{$lis_posicio}}" role="tabpanel" aria-labelledby="address-step-trigger">
        <div class="content-header">
          <h5 class="mb-0">Posiciones</h5>
          <small>Enter Your Address.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="">
          @csrf

          @if(isset($data_posiciones))
            @if(count($data_posiciones) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Posiciones</th>
                      </tr>
                    </thead>
                    @foreach($data_posiciones as $valores)
                      <tr>
                        <th>
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              name="posiciones[]" 
                              checked="checked"
                              value="{{ $valores->id }}"/>
                        </th>
                        <th>{{$valores->posicion}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif
        </form>
        <div class="d-flex justify-content-between">
          <a href="?vicp=2" class="btn btn-primary btn-prev">
            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
          </a>
          <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">Next</span>
            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
          </button>
        </div>
      </div>

      <div id="social-links" class="content empleados {{$lis_empleado}}" role="tabpanel" aria-labelledby="social-links-trigger">
        <div class="content-header">
          <h5 class="mb-0">Empleados</h5>
          <small>Enter Your Social Links.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="">
          @csrf

          @if(isset($data_empleados))
            @if(count($data_empleados) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
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
                    @php 
                      $fila = 2; 
                      $mensaje_alert = '';
                    @endphp
                    @foreach($data_empleados as $valores)
                    @php
                      $colorClass = '';
                      $no_check = true;
                      if($valores->empresa == '' || $valores->cod_empleado == '' ||
                        $valores->nombres == '' || $valores->apellidos == '' ||
                        $valores->posicion == '' || $valores->direccion_vp == '' ||
                        $valores->departamento == '' || $valores->documento == '') 
                      {
                        $colorClass = 'class="alert-danger" style="color: red"';
                        $no_check = false;
                      }

                      $title = '';
                      foreach($data_empleados as $valida)
                      {
                        if($valida->id != $valores->id)
                        {
                          if(($valida->empresa == $valores->empresa && $valida->cod_empleado == $valores->cod_empleado) ||
                            ($valida->empresa == $valores->empresa && $valida->documento == $valores->documento))
                          {
                            $colorClass = 'class="alert-danger" style="color: red"';
                            $title = ' title="Existen datos repetidos para la misma empresa"';
                            $mensaje_alert .= '<li>-El registro de la fila ('.$fila.'), esta con el mismo codigo de empleado ('.$valida->cod_empleado .'), o mismo documento ('.$valida->documento .') en la misma empresa.</li>';
                          
                            $no_check = false;
                            break;
                          }
                        }
                      }
                    @endphp
                      <tr {!! $colorClass !!} {!! $title !!}>
                        <th>
                          @if($no_check)
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              name="empleados[]" 
                              checked="checked"
                              value="{{ $valores->id }}"/>
                            @endif
                        </th>
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
                    @if($mensaje_alert != '')
                      <div class="alert alert-danger p-1" role="alert">
                        {!! $mensaje_alert !!}
                      </div>
                    @endif
                  </table>
                </div>
            @endif
          @endif
        </form>
        <div class="d-flex justify-content-between">
          <a href="?vicp=3" class="btn btn-primary btn-prev">
            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
            <span class="align-middle d-sm-inline-block d-none">Previous</span>
          </a>
          <button class="btn btn-success btn-submit">Submit</button>
        </div>
      </div>
    </div>
  </div>
</section>
<!-- /Horizontal Wizard -->

@endsection

@section('vendor-script')
  <!-- vendor files -->
  <script src="{{ asset(mix('vendors/js/forms/wizard/bs-stepper.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
@endsection
@section('page-script')
  <!-- Page js files 
  <script src="{{ asset(mix('js/scripts/forms/form-wizard.js')) }}"></script>-->

@endsection
