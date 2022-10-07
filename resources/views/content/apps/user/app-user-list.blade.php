@extends('layouts/contentLayoutMaster')

@section('title', 'User List')

@section('vendor-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
  <link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
@endsection

@section('page-style')
  {{-- Page Css files --}}
  <link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
@endsection

@section('content')

<!-- users list start -->
<section class="app-user-list">
  <div class="row">
    <div class="col-lg-3 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{count($users)}}</h3>
            <span>Total de Usuarios</span>
          </div>
          <div class="avatar bg-light-primary p-50">
            <span class="avatar-content">
              <i data-feather="user" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{$total_user_inac}}</h3>
            <span>Usuarios Inactivos</span>
          </div>
          <div class="avatar bg-light-danger p-50">
            <span class="avatar-content">
              <i data-feather="user-plus" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
    <div class="col-lg-3 col-sm-6">
      <div class="card">
        <div class="card-body d-flex align-items-center justify-content-between">
          <div>
            <h3 class="fw-bolder mb-75">{{$total_user_acti}}</h3>
            <span>Usuarios activos</span>
          </div>
          <div class="avatar bg-light-success p-50">
            <span class="avatar-content">
              <i data-feather="user-check" class="font-medium-4"></i>
            </span>
          </div>
        </div>
      </div>
    </div>
  </div>

  @can('users.create')
    <div class="row">
      <div class="col-lg-3 col-sm-6">
        <a href="{{route('admin.users.create')}}">
          <span class="btn btn-primary mb-1">Nuevo Usuario</span>
        </a>
      </div>
    </div>
  @endcan
  <!-- list and filter start -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <table class="user-list-table table">
        <thead class="table-light">
          <tr>
            <th></th>
            <th>Nombre</th>
            <th>Email</th>
            <th>Rol</th>
            <th>Estatus</th>
            @canany(['users.edit', 'users.destroy'])
              <th>Actions</th>
            @endcan
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            <tr>
              <td>
                <span class="avatar-content">
                  <i data-feather="user" class="font-medium-4"></i>
                </span>
              </td>
              <td>{{ $user->name }}</td>
              <td>{{ $user->email }}</td>
              <td>{{ $user->nombre_rol }}</td>
              <td>
                @if($user->estatus == 'A')
                  <span class="badge rounded-pill badge-light-success">Activo</span>
                @else
                  <span class="badge rounded-pill badge-light-danger">Inactivo</span>
                @endif
              </td>
              
              @canany(['users.edit', 'users.destroy'])
                <td>
                  @can('users.edit')
                    <a href="{{route('admin.users.edit', $user->id)}}" class="btn btn-sm btn-primary role-edit-modal">
                      <small class="fw-bolder">Editar</small>
                    </a> 
                  @endcan
                  @can('users.destroy')
                    <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Destroy">
                      Eliminar
                    </button>
                  @endcan
                </td>
              @endcan
            </tr>
          @endforeach
        </tbody>
      </table>
    </div>

  </div>
  <!-- list and filter end -->
</section>
<!-- users list ends -->
@endsection

@section('vendor-script')
  {{-- Vendor js files --}}
  <script src="{{ asset(mix('vendors/js/forms/select/select2.full.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jquery.dataTables.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.bootstrap5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.responsive.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/responsive.bootstrap5.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/datatables.buttons.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/buttons.print.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/tables/datatable/dataTables.rowGroup.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/validation/jquery.validate.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/cleave.min.js')) }}"></script>
  <script src="{{ asset(mix('vendors/js/forms/cleave/addons/cleave-phone.us.js')) }}"></script>
@endsection


