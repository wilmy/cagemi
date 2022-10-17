@extends('layouts/contentLayoutMaster')

@section('title', 'Editar '.$grupo_empresarial->nombre)

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
          <h1 class="role-title">Actualizar Grupo Empresarial</h1>
        </div>
       
        <form id="addRoleForm" method="POST" class="row" enctype="multipart/form-data" action="{{route('admin.grupoEmpresarial.update', $grupo_empresarial->cod_grupo_empresarial)}}">
          @csrf
          @method('PUT')
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
                  <label for="register-nombre" class="form-label">Nombre</label>
                  <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="register-nombre"
                    name="nombre" placeholder="" aria-describedby="register-nombre" tabindex="1" autofocus
                    value="{{ old('nombre', $grupo_empresarial->nombre) }}" />
                  @error('name')
                    <span class="invalid-feedback" role="alert">
                      <strong>{{ $message }}</strong>
                    </span>
                  @enderror
                </div> 
            </div>
            
            <div class="form-group row">
              <div class="mb-1 col-md-4">
                <label for="register-comunidad" class="form-label">Comunidad</label>
                <select name="comunidad" class="form-select">
                  <option value="A" {{('A' == $grupo_empresarial['parametros'][0]['valor'] ? 'selected' : '')}}>Abierta</option>
                  <option value="C" {{('C' == $grupo_empresarial['parametros'][0]['valor'] ? 'selected' : '')}}>Cerrada</option>
                </select>
                @error('cominidad')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <div class="mb-1 col-md-4">
                <label for="register-email" class="form-label">Logo</label>
                <input type="file" class="form-control" id="logo" name="logo" />
                @error('logo')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

            <div class="form-group row">
              <div class="mb-1 col-md-4">
                <label for="register-email" class="form-label">Estatus</label>
                <select name="estatus" class="form-select">
                  <option value="A" {{('A' == $grupo_empresarial->estatus ? 'selected' : '')}}>Activo</option>
                  <option value="I" {{('I' == $grupo_empresarial->estatus ? 'selected' : '')}}>Inactivo</option>
                </select>
                @error('estatus')
                  <span class="invalid-feedback" role="alert">
                    <strong>{{ $message }}</strong>
                  </span>
                @enderror
              </div>
            </div>

          <div class="col-4 text-center mt-2">
            <button type="submit" class="btn btn-primary me-1">Actualizar</button>
            <a href="{{route('admin.grupoEmpresarial.index')}}" class="btn btn-outline-danger">
              Cancelar
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
