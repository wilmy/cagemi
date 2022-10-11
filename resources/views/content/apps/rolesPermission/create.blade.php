@extends('layouts/contentLayoutMaster')

@section('title', 'Roles')

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

<div class="row">

    <div class="modal-content">
      <div class="modal-body px-5 pb-5">
        <div class="text-left mb-4">
          <h1 class="role-title">Nuevo Rol</h1>
          <p>Configure los permisos del rol</p>
        </div>
        <!-- Add role form -->
        <form id="addRoleForm" method="POST" class="row" action="{{route('admin.roles.store')}}">
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

          <div class="col-12">
            <label class="form-label" for="modalRoleName">Nombre del Rol</label>
            <input
              type="text"
              id="modalRoleName"
              name="nombreRol"
              class="form-control"
              placeholder="Introduzca el Rol"
              tabindex="-1"
              required="required"
              data-msg="Agrege el nombre del ROl"
            />
          </div>
          <div class="col-12">
            <h4 class="mt-2 pt-50">Permisos del Rol</h4>
            <!-- Permission table -->
            <div class="table-responsive">
              <table class="table table-flush-spacing">
                <tbody>
                  <tr>
                    <td class="text-nowrap fw-bolder">
                      Administrador de Accesos
                      <span data-bs-toggle="tooltip" data-bs-placement="top" title="Allows a full access to the system">
                        <i data-feather="info"></i>
                      </span>
                    </td>
                    <td>
                      <div class="form-check">
                        <input class="form-check-input" type="checkbox" id="selectAll" />
                        <label class="form-check-label" for="selectAll"> Seleccionar todos</label>
                      </div>
                    </td>
                  </tr>
                  
                  @php 
                    $listMenu = Helper::listMenu('all');
                  @endphp
                  @foreach($listMenu as $perm)
                    <tr>
                        <td class="text-nowrap fw-bolder">{{ $perm->nombre }}</td>
                        <td>
                            <div class="d-flex">
                              @if($perm->ver == 'S')
                                  <div class="form-check me-3 me-lg-5">
                                      <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="permission[]" 
                                        value="{{ $perm->slug }}" 
                                        id="ver-{{ $perm->id }}" />

                                      <label class="form-check-label" for="userManagementRead"> 
                                        Ver
                                      </label>
                                  </div>
                              @endif

                              @if($perm->crear == 'S')
                                  <div class="form-check me-3 me-lg-5">
                                      <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="permission[]" 
                                        value="{{ $perm->slug }}" 
                                        id="crear-{{ $perm->id }}" />

                                      <label class="form-check-label" for="userManagementRead"> 
                                        Crear
                                      </label>
                                  </div>
                              @endif

                              @if($perm->editar == 'S')
                                  <div class="form-check me-3 me-lg-5">
                                      <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="permission[]" 
                                        value="{{ $perm->slug }}" 
                                        id="edit-{{ $perm->id }}" />

                                      <label class="form-check-label" for="userManagementRead"> 
                                        Editar
                                      </label>
                                  </div>
                              @endif

                              @if($perm->eliminar == 'S')
                                  <div class="form-check me-3 me-lg-5">
                                      <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="permission[]" 
                                        value="{{ $perm->slug }}" 
                                        id="elimi-{{ $perm->id }}" />

                                      <label class="form-check-label" for="userManagementRead"> 
                                        Eliminar
                                      </label>
                                  </div>
                              @endif
                            </div>
                        </td>
                    </tr>
                  @endforeach
                </tbody>
              </table>
            </div>
            <!-- Permission table -->
          </div>
          <div class="col-12 text-center mt-2">
            <button type="submit" class="btn btn-primary me-1">Guardar</button>
            <a href="{{route('admin.roles.index')}}" class="btn btn-outline-danger">
              Cancelar
            </a>
          </div>
        </form>
        <!--/ Add role form -->
      </div>
    </div>

</div>
<!--/ Role cards -->

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
