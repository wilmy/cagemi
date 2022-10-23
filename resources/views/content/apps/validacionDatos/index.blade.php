@extends('layouts/contentLayoutMaster')

@section('title', __('Data validation'))

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
$lis_validation = '';
if(isset($_GET['vicp']))
{
  if($_GET['vicp'] == 0 && count($data_empresas) > 0)
  {
    $lis_empresa = 'active';
  }
  else if($_GET['vicp'] == 1 && count($data_direcciones_vicepre) > 0)
  {
    $lis_direc = 'active';
  }
  else if($_GET['vicp'] == 2 && count($data_departamentos) > 0)
  {
    $lis_depart = 'active';
  }
  else if($_GET['vicp'] == 3 && count($data_posiciones) > 0)
  {
    $lis_posicio = 'active';
  }
  else if($_GET['vicp'] == 4 && count($data_empleados) > 0)
  {
    $lis_empleado = 'active';
  }
  else 
  {
    $lis_validation = 'active';
  }
}
else if(count($data_empresas) > 0)
{
  $lis_empresa = 'active';
}
else if(count($data_direcciones_vicepre) > 0)
{
  $lis_direc = 'active';
}
else if(count($data_departamentos) > 0)
{
  $lis_depart = 'active';
}
else if(count($data_posiciones) > 0)
{
  $lis_posicio = 'active';
}
else if(count($data_empleados) > 0)
{
  $lis_empleado = 'active';
}
else 
{
  $lis_validation = 'active';
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
            <span class="bs-stepper-title">{{__('Companies')}}</span>
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
            <span class="bs-stepper-title">{{__('Directions/Vice Presidencies')}}</span>
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
            <span class="bs-stepper-title">{{__('Departments')}}</span>
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
            <span class="bs-stepper-title">{{__('Positions')}}</span>
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
            <span class="bs-stepper-title">{{__('Employees')}}</span>
            <span class="bs-stepper-subtitle">Add Social Links</span>
          </span>
        </button>
      </div>
    </div>

    @if ($errors->any())
        <div class="alert alert-danger">
            <ul>
                @foreach ($errors->all() as $error)
                    <li>{{ $error }}</li>
                @endforeach
            </ul>
        </div>
    @endif
   
    <div class="bs-stepper-content">
      <div id="account-details" class="content empresas {{ $lis_empresa }}" role="tabpanel" aria-labelledby="account-details-trigger">
        <div class="content-header">
          <h5 class="mb-0">{{__('Companies')}}</h5>
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
                      @if(trim($valores->empresa) != '')
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
                        @endif
                    @endforeach
                  </table>
                </div>
            @endif
          @endif

        <div class="d-flex justify-content-between">
          <button class="btn btn-outline-secondary btn-prev" disabled>
            <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
            <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
          </button>
          <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">{{__('Next')}}</span>
            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
          </button>
        </div>
        
      </form>
      </div>
      <div id="personal-info" class="content direccion_vicepresidencias {{$lis_direc}}" role="tabpanel" aria-labelledby="personal-info-trigger">
        <div class="content-header">
          <h5 class="mb-0">{{__('Directions/Vice Presidencies')}} </h5>
          <small>Enter Your Personal Info.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="{{route('admin.vicepresidencias.store')}}">
          @csrf

          @if(isset($data_direcciones_vicepre))
            @if(count($data_direcciones_vicepre) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Empresa</th>
                        <th>Direcciones/Vicepresidencias</th>
                      </tr>
                    </thead>
                    @foreach($data_direcciones_vicepre as $valores)
                      <tr>
                        <th>
                          @if($valores->empresa != '')
                            <input type="hidden" name="empresa[]" value="{{ $valores->empresa}}">
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              name="vicepresidencias[]" 
                              checked="checked"
                              value="{{ $valores->empresa.'||'.$valores->direccion_vp }}"/>
                            @endif
                        </th>
                        <th>{{$valores->empresa}}</th>
                        <th>{{$valores->direccion_vp}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif
        
          <div class="d-flex justify-content-between">
            @if(count($data_empresas) > 0)
              <a href="?vicp=0" class="btn btn-primary btn-prev">
                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
              </a>
            @else 
              <button class="btn btn-outline-secondary btn-prev" disabled>
                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
              </button>
            @endif
            <button class="btn btn-primary btn-next">
              <span class="align-middle d-sm-inline-block d-none">{{__('Next')}}</span>
              <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
            </button>
          </div>
        </form>
      </div>

      <div id="address-step" class="content departamentos {{$lis_depart}}" role="tabpanel" aria-labelledby="address-step-trigger">
        <div class="content-header">
          <h5 class="mb-0">{{__('Departments')}}</h5>
          <small>Enter Your Address.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="{{route('admin.departamentosxvicepresidencias.store')}}">
          @csrf

          @if(isset($data_departamentos))
            @if(count($data_departamentos) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Vicepresidencias</th>
                        <th>Departamentos</th>
                      </tr>
                    </thead>
                    @foreach($data_departamentos as $valores)
                      <tr>
                        <th>
                          @if($valores->empresa != '')
                            <input type="hidden" name="direccion_vp[]" value="{{ $valores->direccion_vp}}">
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              name="departamentos[]" 
                              checked="checked"
                              value="{{ $valores->direccion_vp.'||'.$valores->departamento }}"/>
                            @endif
                        </th>
                        <th>{{$valores->direccion_vp}}</th>
                        <th>{{$valores->departamento}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif
        
          <div class="d-flex justify-content-between">
            @if(count($data_direcciones_vicepre) > 0)
              <a href="?vicp=1" class="btn btn-primary btn-prev">
                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
              </a>
            @else 
              <button class="btn btn-outline-secondary btn-prev" disabled>
                <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
                <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
              </button>
            @endif
            <button class="btn btn-primary btn-next">
              <span class="align-middle d-sm-inline-block d-none">{{__('Next')}}</span>
              <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
            </button>
          </div>
        </form>
      </div>

      
      <div id="address-step" class="content posiciones {{$lis_posicio}}" role="tabpanel" aria-labelledby="address-step-trigger">
        <div class="content-header">
          <h5 class="mb-0">{{__('Positions')}}</h5>
          <small>Enter Your Address.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="{{route('admin.posicionesxdepartamentos.store')}}">
          @csrf

          @if(isset($data_posiciones))
            @if(count($data_posiciones) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  <table class="table table-striped- table-bordered table-hover table-responsive">
                    <thead>
                      <tr>
                        <th></th>
                        <th>Empresa</th>
                        <th>Vicepresidencia</th>
                        <th>Departamento</th>
                        <th>Posiciones</th>
                      </tr>
                    </thead>
                    @foreach($data_posiciones as $valores)
                      <tr>
                        <th>
                          @if($valores->empresa != '')
                            <input type="hidden" name="departamento[]" value="{{ $valores->departamento}}">
                            <input 
                              class="form-check-input" 
                              type="checkbox" 
                              name="posiciones[]" 
                              checked="checked"
                              value="{{ $valores->departamento.'||'.$valores->posicion }}"/>
                            @endif
                        </th>
                        <th>{{$valores->empresa}}</th>
                        <th>{{$valores->direccion_vp}}</th>
                        <th>{{$valores->departamento}}</th>
                        <th>{{$valores->posicion}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif
        
        <div class="d-flex justify-content-between">
          @if(count($data_departamentos) > 0)
            <a href="?vicp=2" class="btn btn-primary btn-prev">
              <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
              <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
            </a>
          @else 
            <button class="btn btn-outline-secondary btn-prev" disabled>
              <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
              <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
            </button>
          @endif
          <button class="btn btn-primary btn-next">
            <span class="align-middle d-sm-inline-block d-none">{{__('Next')}}</span>
            <i data-feather="arrow-right" class="align-middle ms-sm-25 ms-0"></i>
          </button>
        </div>
        </form>
      </div>

      <div id="social-links" class="content empleados {{$lis_empleado}}" role="tabpanel" aria-labelledby="social-links-trigger">
        <div class="content-header">
          <h5 class="mb-0">{{__('Employees')}}</h5>
          <small>Enter Your Social Links.</small>
        </div>
        <form id="addRoleForm" method="POST" class="row" action="{{route('admin.empleadosxposiciones.store')}}">
          @csrf

          @if(isset($data_empleados))
            @if(count($data_empleados) > 0)
                <div class="col-md-12 mb-10 table-responsive">
                  @if($validacionesDataTmp != '')
                    <div class="alert alert-danger p-1" role="alert">
                      {!! $validacionesDataTmp !!}
                    </div>
                  @endif

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
                        <th>telefono_institucional</th>
                        <th>extencion</th>
                        <th>correo_instutucional</th>
                        <th>correo_personal</th>
                        <th>documento</th>
                        <th>fecha_nacimiento</th>
                        <th>codigo_superfisor</th>
                      </tr>
                    </thead>
                    @foreach($data_empleados as $valores)
                      @php
                        $fila = $valores->fila;
                        $colorClass = '';
                        $no_check = true;
                        if($valores->empresa == '') 
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $messs = __('The row record '.$fila.' has the empresa field empty.'); 
                          $title = ' title="'.$messs.'"';
                          
                          $no_check = false;
                        }
    
                        if($valores->cod_empleado == '') 
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $messs = __('The row record '.$fila.' has the codigo de empleado field empty.'); 
                          $title = ' title="'.$messs.'"';
                          $no_check = false;
                        }
    
                        if($valores->nombres == '') 
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $messs = __('The row record '.$fila.' has the nombres field empty.'); 
                          $title = ' title="'.$messs.'"';
                          $no_check = false;
                        }
    
                        if($valores->apellidos == '') 
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $messs = __('The row record '.$fila.' has the apellidos field empty.'); 
                          $title = ' title="'.$messs.'"';
                          $no_check = false;
                        }
    
                        if($valores->direccion_vp == '') 
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $messs = __('The row record '.$fila.' has the direccion/vicepresidencia field empty.'); 
                          $title = ' title="'.$messs.'"';
                          $no_check = false;
                        }
    
                        if($valores->departamento == '') 
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $messs = __('The row record '.$fila.' has the departamento field empty.'); 
                          $title = ' title="'.$messs.'"';
                          $no_check = false;
                        }
    
                        if($valores->documento == '') 
                        {
                          $colorClass = 'class="alert-danger" style="color: red"';
                          $messs = __('The row record '.$fila.' has the documento field empty.'); 
                          $title = ' title="'.$messs.'"';
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
                              $title = ' title="'.__('There are duplicate data for the same company').'"';
                              $no_check = false;
                              break;
                            }
                          }
                        }
                      @endphp
                      <tr {!!$colorClass!!} {!!$title!!}>
                        <th>
                          @if($valores->empresa != '')
                            @if($no_check)
                              <input type="hidden" name="posiciones[]" value="{{ $valores->posiciones}}">
                              <input 
                                class="form-check-input" 
                                type="checkbox" 
                                name="empleados[]" 
                                checked="checked"
                                value="{{ 
                                  $valores->posicion.'||'.
                                  $valores->codigo_superfisor.'||'.
                                  $valores->nombres.'||'.
                                  $valores->apellidos.'||'.
                                  $valores->documento.'||'.
                                  $valores->extencion.'||'.
                                  $valores->correo_instutucional.'||'.
                                  $valores->correo_personal.'||'.
                                  $valores->cod_empleado.'||'.
                                  $valores->fecha_nacimiento.'||'.
                                  $valores->telefono_movil.'||'.
                                  $valores->telefono_institucional
                                  
                                  }}"/>
                            @endif
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
                        <th>{{$valores->telefono_institucional}}</th>
                        <th>{{$valores->extencion}}</th>
                        <th>{{$valores->correo_institucional}}</th>
                        <th>{{$valores->correo_personal}}</th>
                        <th>{{$valores->documento}}</th>
                        <th>{{($valores->fecha_nacimiento != '' ? $valores->fecha_nacimiento->format('d/m/Y') : '')}}</th>
                        <th>{{$valores->codigo_superfisor}}</th>
                      </tr>
                    @endforeach
                  </table>
                </div>
            @endif
          @endif
        
        <div class="d-flex justify-content-between">
          @if(count($data_posiciones) > 0)
            <a href="?vicp=3" class="btn btn-primary btn-prev">
              <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
              <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
            </a>
          @else 
            <button class="btn btn-outline-secondary btn-prev" disabled>
              <i data-feather="arrow-left" class="align-middle me-sm-25 me-0"></i>
              <span class="align-middle d-sm-inline-block d-none">{{__('Previous')}}</span>
            </button>
          @endif
          <button class="btn btn-success btn-submit">{{__('To finalize')}}</button>
        </div>
      </form>
        
      </div>
        @if($lis_validation == 'active')
          <div class="alert alert-info p-2">
            {{__('There are no data pending validation')}}
          </div>
        @endif
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
