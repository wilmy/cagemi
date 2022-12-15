@extends('layouts/contentLayoutMaster')

@section('title', __('Role'))

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
<h3>{{__('Role list')}}</h3>

<!-- Role cards -->
<div class="row">
  @foreach($roles as $role)
    @if ($role->name == 'admin' && Auth::user()->super_usuario == 'S')

    <div class="col-xl-4 col-lg-6 col-md-6">
      <div class="card">
        <div class="card-body">
          <div class="d-flex justify-content-between">
            <span>Total {{$role->users_count}} users</span>
            <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
              <li
                data-bs-toggle="tooltip"
                data-popup="tooltip-custom"
                data-bs-placement="top"
                title="Vinnie Mostowy"
                class="avatar avatar-sm pull-up"
              >
                <img class="rounded-circle" src="{{asset('images/avatars/2.png')}}" alt="Avatar" />
              </li>
              <li
                data-bs-toggle="tooltip"
                data-popup="tooltip-custom"
                data-bs-placement="top"
                title="Allen Rieske"
                class="avatar avatar-sm pull-up"
              >
                <img class="rounded-circle" src="{{asset('images/avatars/12.png')}}" alt="Avatar" />
              </li>
              <li
                data-bs-toggle="tooltip"
                data-popup="tooltip-custom"
                data-bs-placement="top"
                title="Julee Rossignol"
                class="avatar avatar-sm pull-up"
              >
                <img class="rounded-circle" src="{{asset('images/avatars/6.png')}}" alt="Avatar" />
              </li>
              <li
                data-bs-toggle="tooltip"
                data-popup="tooltip-custom"
                data-bs-placement="top"
                title="Kaith D'souza"
                class="avatar avatar-sm pull-up"
              >
                <img class="rounded-circle" src="{{asset('images/avatars/11.png')}}" alt="Avatar" />
              </li>
            </ul>
          </div>
            <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
              <div class="role-heading">
                <h4 class="fw-bolder">{{$role->name}}</h4>
                @can('roles.edit')
                  <a href="{{route('admin.roles.edit', $role->id)}}" class="role-edit-modal">
                    <small class="fw-bolder">{{__('Edit')}}</small>
                  </a>
                @endcan
              </div>
              <a href="javascript:void(0);" class="text-body">
                <i data-feather="copy" class="font-medium-5"></i>
              </a>
            </div>
        </div>
      </div>
    </div>

    @else
    
      <div class="col-xl-4 col-lg-6 col-md-6">
        <div class="card">
          <div class="card-body">
            <div class="d-flex justify-content-between">
              <span>Total {{$role->users_count}} users</span>
              <ul class="list-unstyled d-flex align-items-center avatar-group mb-0">
                <li
                  data-bs-toggle="tooltip"
                  data-popup="tooltip-custom"
                  data-bs-placement="top"
                  title="Vinnie Mostowy"
                  class="avatar avatar-sm pull-up"
                >
                  <img class="rounded-circle" src="{{asset('images/avatars/2.png')}}" alt="Avatar" />
                </li>
                <li
                  data-bs-toggle="tooltip"
                  data-popup="tooltip-custom"
                  data-bs-placement="top"
                  title="Allen Rieske"
                  class="avatar avatar-sm pull-up"
                >
                  <img class="rounded-circle" src="{{asset('images/avatars/12.png')}}" alt="Avatar" />
                </li>
                <li
                  data-bs-toggle="tooltip"
                  data-popup="tooltip-custom"
                  data-bs-placement="top"
                  title="Julee Rossignol"
                  class="avatar avatar-sm pull-up"
                >
                  <img class="rounded-circle" src="{{asset('images/avatars/6.png')}}" alt="Avatar" />
                </li>
                <li
                  data-bs-toggle="tooltip"
                  data-popup="tooltip-custom"
                  data-bs-placement="top"
                  title="Kaith D'souza"
                  class="avatar avatar-sm pull-up"
                >
                  <img class="rounded-circle" src="{{asset('images/avatars/11.png')}}" alt="Avatar" />
                </li>
              </ul>
            </div>
          
              <div class="d-flex justify-content-between align-items-end mt-1 pt-25">
                <div class="role-heading">
                  <h4 class="fw-bolder">{{$role->name}}</h4>
                  @can('roles.edit')
                    <a href="{{route('admin.roles.edit', $role->id)}}" class="role-edit-modal">
                      <small class="fw-bolder">{{__('Edit')}}</small>
                    </a>
                  @endcan
                </div>
                <a href="javascript:void(0);" class="text-body">
                  <i data-feather="copy" class="font-medium-5"></i>
                </a>
              </div>
            
          </div>
        </div>
      </div> 
    @endif
@endforeach

@can('roles.create')
  <div class="col-xl-4 col-lg-6 col-md-6">
    <div class="card">
      <div class="row">
        <div class="col-sm-5">
          <div class="d-flex align-items-end justify-content-center h-100">
            <img
              src="{{asset('images/illustration/faq-illustrations.svg')}}"
              class="img-fluid mt-2"
              alt="Image"
              width="85"
            />
          </div>
        </div>
        <div class="col-sm-7">
          <div class="card-body text-sm-end text-center ps-sm-0">
            <a href="{{route('admin.roles.create')}}" class="stretched-link text-nowrap add-new-role">
              <span class="btn btn-primary mb-1">{{__('New Role')}}</span>
            </a>
            <p class="mb-0">Add role, if it does not exist</p>
          </div>
        </div>
      </div>
    </div>
  </div>
  @endcan
</div>
<!--/ Role cards -->
{{--
<h3 class="mt-50">Total users with their roles</h3>
<p class="mb-2">Find all of your company’s administrator accounts and their associate roles.</p>
<!-- table -->
<div class="card">
  <div class="table-responsive">
    <table class="user-list-table table">
      <thead class="table-light">
        <tr>
          <th></th>
          <th></th>
          <th>Name</th>
          <th>Role</th>
          <th>Plan</th>
          <th>Billing</th>
          <th>Status</th>
          <th>Actions</th>
        </tr>
      </thead>
    </table>
  </div>
</div>
--}}
<!-- table -->

@include('content/_partials/_modals/modal-add-role')
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
