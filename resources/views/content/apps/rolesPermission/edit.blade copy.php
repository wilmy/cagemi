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
          <h1 class="role-title">Editar el Rol: <b>{{ $rol->name }}</b></h1>
        </div>
        <!-- Add role form -->
        <form id="updateRoleForm" method="POST" class="row" action="{{route('admin.roles.update', $rol->id)}}">
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

          <div class="col-12">
            <label class="form-label" for="modalRoleName">Nombre del Rol</label>
            <input
              type="text"
              id="modalRoleName"
              name="nombreRol"
              class="form-control"
              placeholder="Introduzca el Rol"
              tabindex="-1"
              value="{{ $rol->name }}"
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
                        <label class="form-check-label" for="selectAll"> Select All </label>
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
                                        {{($rol->hasPermissionTo($perm->slug) ? 'checked="checked"' : '')}}
                                        id="ver-{{ $perm->cod_pantalla }}" />

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
                                        {{($rol->hasPermissionTo($perm->slug) ? 'checked="checked"' : '')}}
                                        id="crear-{{ $perm->cod_pantalla }}" />

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
                                        {{($rol->hasPermissionTo($perm->slug) ? 'checked="checked"' : '')}}
                                        id="edit-{{ $perm->cod_pantalla }}" />

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
                                        {{($rol->hasPermissionTo($perm->slug) ? 'checked="checked"' : '')}}
                                        id="elimi-{{ $perm->cod_pantalla }}" />

                                      <label class="form-check-label" for="userManagementRead"> 
                                        Eliminar
                                      </label>
                                  </div>
                              @endif
                            </div>
                        </td>
                    </tr>
                  @endforeach

                  @foreach($pantallas as $perm)
                    <tr>
                        <td class="text-nowrap fw-bolder">{{$perm['name']}}</td>
                          
                        <td>
                            <div class="d-flex">
                              @if(count($perm['tipo_permisos_pantallas']) >0)
                                @foreach($perm['tipo_permisos_pantallas'] as $tipo_perm)
                                  <div class="form-check me-3 me-lg-5">
                                      <input 
                                        class="form-check-input" 
                                        type="checkbox" 
                                        name="permission[]" 
                                        value="{{$tipo_perm['value']}}" 
                                        {{($rol->hasPermissionTo($tipo_perm['value']) ? 'checked="checked"' : '')}}
                                        id="{{$tipo_perm['cod_pantalla']}}" />
                                      <label class="form-check-label" for="userManagementRead"> {{$tipo_perm['name']}} </label>
                                  </div>
                                @endforeach
                              @else 
                                <div class="form-check me-3 me-lg-5">
                                    <input class="form-check-input" type="checkbox" name="permission[]" value="{{$perm['value']}}" id="userManagementRead" />
                                    <label class="form-check-label" for="userManagementRead"> Ver </label>
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
            <button type="submit" class="btn btn-primary me-1">Actualizar</button>
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
