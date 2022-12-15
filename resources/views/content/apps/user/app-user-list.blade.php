@extends('layouts/contentLayoutMaster')

@section('title', __('User List'))

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
            <span>{{ __('Total Users') }}</span>
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
            <span>{{ __('Inactive users') }}</span>
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
            <span>{{ __('Active users') }}</span>
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
          <span class="btn btn-primary mb-1">{{ __('New user') }}</span>
        </a>
      </div>
    </div>
  @endcan
  <!-- list and filter start -->
  <div class="card">
    <div class="card-datatable table-responsive pt-0">
      <div class="card-datatable table-responsive pt-0">
        <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">
         
          <form id="addRoleForm-" method="GET" action="">
           
          <div class="d-flex justify-content-between align-items-center header-actions mx-2 row mt-100">
            <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
              <div class="dataTables_length" id="DataTables_Table_0_length">
                <label>{{ __('To Show') }} 
                  <select name="mostrar" aria-controls="DataTables_Table_0" class="form-select">
                    <option value="100" {{(isset($request->mostrar) ? ($request->mostrar == 100 ? 'selected' : '') : '')}}>100</option>
                    <option value="50" {{(isset($request->mostrar) ? ($request->mostrar == 50 ? 'selected' : '') : '')}}>50</option>
                    <option value="25" {{(isset($request->mostrar) ? ($request->mostrar == 25 ? 'selected' : '') : '')}}>25</option>
                    <option value="10" {{(isset($request->mostrar) ? ($request->mostrar == 10 ? 'selected' : '') : '')}}>10</option>
                  </select> 
                </label>
              </div>
                
                <div class="dataTables_length" id="DataTables_Table_1_length" >
                  <label>{{ __('Type of user') }}:
                    <select class="form-select" name="tipo_usuario" aria-controls="DataTables_Table_0" style="width: 150px;">
                      <option value=""> {{ __('All') }}</option>
                      <option value="B" class="text-capitalize" {{(isset($request->tipo_usuario) ? ($request->tipo_usuario == 'B' ? 'selected' : '') : '')}}>Backoffice</option>
                      <option value="F" class="text-capitalize" {{(isset($request->tipo_usuario) ? ($request->tipo_usuario == 'B' ? 'selected' : '') : '')}}>Frontend</option>
                    </select></label>
                </div>

            </div>

                <div class="col-sm-12 col-lg-8 ps-0">
                  <div class="dt-action-buttons d-flex align-items-center justify-content-center- justify-content-lg-end flex-lg-nowrap flex-wrap">
                      <div class="me-2">
                        <div id="DataTables_Table_1_filter" class="dataTables_filter">
                          <label>{{ __('Search') }}:</label>
                            <input 
                              type="search" 
                              name="buscar" 
                              class="form-control" 
                              value="{{(isset($request->buscar) ? $request->buscar : '')}}"
                              placeholder="" 
                              aria-controls="DataTables_Table_0">
                        </div>
                      </div>

                      <div class="dt-buttons d-inline-flex mt-80"> 
                        <button type="submit" class="dt-button buttons-collection btn btn-primary me-2">
                          {{ __('Search') }}
                        </button>
                      </div>
                  </div>
                </div>
            </div>
          </div>
        </form>
      </div>
  
      <table class="user-list-table table">
        <thead class="table-light">
          <tr>
            <th></th>
            <th>{{ __('Name') }}</th>
            <th>{{ __('Surname') }}</th>
            <th>{{ __('Email') }}</th>
            <th>{{ __('Rol') }}</th>
            <th>{{ __('Status') }}</th>
            @canany(['users.edit', 'users.destroy'])
              <th>{{ __('Action') }}</th>
            @endcan
          </tr>
        </thead>
        <tbody>
          @foreach($users as $user)
            @if($user->super_usuario != 'S')
              <tr>
                <td>
                  <span class="avatar-content">
                    <i data-feather="user" class="font-medium-4"></i>
                  </span>
                </td>
                <td>{{ $user->name }}</td>
                <td>{{ $user->surname }}</td>
                <td>{{ $user->email }}</td>
                <td>{{ $user->nombre_rol }}</td>
                <td>
                  @if($user->estatus == 'A')
                    <span class="badge rounded-pill badge-light-success">{{ __('Active') }}</span>
                  @else
                    <span class="badge rounded-pill badge-light-danger">{{ __('Inactive') }}</span>
                  @endif
                </td>
                
                @canany(['users.edit', 'users.destroy'])
                  <td>
                    @can('users.edit')
                      <a href="{{route('admin.users.edit', $user->id)}}" class="btn btn-sm btn-primary role-edit-modal">
                        <small class="fw-bolder">{{ __('Edit') }}</small>
                      </a> 
                    @endcan
                    @can('users.destroy')
                      <button type="button" class="btn btn-sm btn-danger" data-toggle="tooltip" data-placement="top" title="Destroy">
                        {{ __('Delete') }}
                      </button>
                    @endcan
                  </td>
                @endcan
              </tr>
              @endif
          @endforeach
        </tbody>
      </table>


    </div>
 </div>
 <div class="mb-5">
 {{ $users->appends(['page' => $request->page, 
                     'mostrar' => $request->mostrar, 
                     'tipo_usuario' => $request->tipo_usuario, 
                     'buscar'=> $request->buscar])->links() }}
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


