@extends('layouts/contentLayoutMaster')

@section('title', 'New position')

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
          <h1 class="role-title">{{__('New position')}}</h1>
        </div>
       
        <form id="addRoleForm" method="POST" class="row" enctype="multipart/form-data" action="{{route('admin.posicionesxdepartamentos.store')}}">
          @csrf

            @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                            <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>
            @endif

            <div class="form-group row">
                <div class="mb-1 col-md-4">
                  <label for="register-nombre" class="form-label">{{__('Name')}}</label>
                  <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="register-nombre"
                    name="nombre" placeholder="" aria-describedby="register-nombre" tabindex="1" autofocus
                    value="{{ old('nombre') }}" />
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div> 
                <input type="hidden" value="formulario" name="esFormulario">
            </div>

            <div class="form-group row">
              <div class="mb-1 col-md-4">
                <label for="register-posiciones" class="form-label">{{__('Positios')}}</label>

                <select name="departamento" class="form-select">
                  @foreach($departamentos as $departamento)
                    <option value="{{$departamento->cod_departamento}}">{{__($departamento->nombre_departamento.' - ' .$departamento->vicepresidencia->nombre_vicepresidencia.' ('.$departamento->vicepresidencia->empresa->nombre.')')}}</option>
                  @endforeach                  
                </select>
                @error('cominidad')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

        
          <div class="col-4 text-center mt-2">
            <button type="submit" class="btn btn-primary me-1"  onclick="backblock()">{{__('Save')}}</button>
            <a href="{{route('admin.posicionesxdepartamentos.index')}}" class="btn btn-outline-danger">
              {{__('Cancel')}}
            </a>
          </div>
        </form>
        <!--/ Add role form -->
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
