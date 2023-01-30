@extends('layouts/contentLayoutMaster')

@section('title', __('Employe List'))

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
                        <h3 class="fw-bolder mb-75">{{$totalEmpleados}}</h3>
                        <span>{{__('Total Employes')}}</span>
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
                        <h3 class="fw-bolder mb-75">{{$empleadosActivos}}</h3>
                        <span>{{__('Active Employes')}}</span>
                    </div>
                    <div class="avatar bg-light-success p-50">
                        <span class="avatar-content">
                            <i data-feather="user-check" class="font-medium-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
        <div class="col-lg-3 col-sm-6">
            <div class="card">
                <div class="card-body d-flex align-items-center justify-content-between">
                    <div>
                        <h3 class="fw-bolder mb-75">{{$empleadosInactivos}}</h3>
                        <span>{{__('Inactive Employes')}}</span>
                    </div>
                    <div class="avatar bg-light-warning p-50">
                        <span class="avatar-content">
                            <i data-feather="user-x" class="font-medium-4"></i>
                        </span>
                    </div>
                </div>
            </div>
        </div>
    </div>

    <!-- list and filter start -->
    <div class="card">
        <form action="empleadosxposiciones" method="GET" id="form_select">

            <div class="card-body border-bottom">
                <h4 class="card-title">{{__('Search & Filter')}}</h4>
                <div class="row">
                    <div class="col-md-2 filtEmpresa">
                        <label class="form-label" for="FiltroEmpresa">{{__('Company')}}</label>
                        <select id="FiltroEmpresa" name="FiltroEmpresa"
                            class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> {{__('Select Company')}} </option>
                            @foreach($empresas as $empresa)
                            <option value="{{$empresa->cod_empresa}}"
                                {{($empresa->cod_empresa == $FiltroEmpresa ? 'selected':'' )}}>{{__($empresa->nombre)}}
                            </option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 filtVicepresidencias">
                        <label class="form-label" for="FiltroVicepresidencia">{{__('Vicepresident')}}</label>
                        <select id="FiltroVicepresidencia" name="FiltroVicepresidencia"
                            class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> {{__('Select Vicepresident')}} </option>
                            @foreach($vicepresidencias as $vicepresidencia)
                            <option value="{{$vicepresidencia->cod_vicepresidencia}}"
                                {{($vicepresidencia->cod_vicepresidencia == $FiltroVicepresidencia ? 'selected':'' )}}>
                                {{__($vicepresidencia->nombre_vicepresidencia)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 filtDepartamento">
                        <label class="form-label" for="FiltroDepartamento">{{__('Departament')}}</label>
                        <select id="FiltroDepartamento" name="FiltroDepartamento"
                            class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> {{__('Select Departament')}} </option>
                            @foreach($departamentos as $departamento)
                            <option value="{{$departamento->cod_departamento}}"
                                {{($departamento->cod_departamento == $FiltroDepartamento ? 'selected':'' )}}>
                                {{__($departamento->nombre_departamento)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 filtPosicion">
                        <label class="form-label" for="FiltroPosicion">{{__('Position')}}</label>
                        <select id="FiltroPosicion" name="FiltroPosicion"
                            class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> {{__('Select Position')}} </option>
                            @foreach($posiciones as $posicion)
                            <option value="{{$posicion->cod_posicion}}"
                                {{($posicion->cod_posicion == $FiltroPosicion ? 'selected':'' )}}>
                                {{__($posicion->nombre_posicion)}}</option>
                            @endforeach
                        </select>
                    </div>
                    <div class="col-md-2 filtEstatus">
                        <label class="form-label" for="FiltroEstatus">{{__('Estatus')}}</label>
                        <select id="FiltroEstatus" name="FiltroEstatus"
                            class="form-select text-capitalize mb-md-0 mb-2">
                            <option value=""> {{__('Select Status')}} </option>
                            <option value="A" {{('A' == $FiltroEstatus ? 'selected' : '')}}>{{__('Active')}}</option>
                            <option value="I" {{('I' == $FiltroEstatus ? 'selected' : '')}}>{{__('Inactive')}}</option>
                        </select>
                    </div>
                    <div class="col-md-2 filtEstatus">
                        <div
                            class="dt-action-buttons d-flex align-items-center justify-content-center- justify-content-lg-end flex-lg-nowrap flex-wrap">
                            <div class="me-2">
                                <div id="DataTables_Table_1_filter" class="dataTables_filter">
                                    <label>{{__('Search')}}</label>
                                    <input type="search" name="buscar" class="form-control"
                                        value="{{(isset($request->buscar) ? $request->buscar : '')}}" placeholder=""
                                        aria-controls="DataTables_Table_0">
                                </div>
                            </div>
                        </div>
                    </div>
                </div>

            </div>
        </form>
        <div class="card-datatable table-responsive pt-0">
            <div class="card-datatable table-responsive pt-0">
                <div id="DataTables_Table_0_wrapper" class="dataTables_wrapper dt-bootstrap5 no-footer">

                    <form id="addRoleForm-" method="POST" enctype="multipart/form-data" ">

                        <div class=" d-flex justify-content-between align-items-center header-actions mx-2 row mt-75">
                        <div class="col-sm-12 col-lg-4 d-flex justify-content-center justify-content-lg-start">
                            <div class="dataTables_length" id="DataTables_Table_0_length">
                                <label>{{__('To show')}}
                                    <select name="mostrar" aria-controls="DataTables_Table_0" class="form-select">
                                        <option value="100"
                                            {{(isset($request->mostrar) ? ($request->mostrar == 100 ? 'selected' : '') : '')}}>
                                            100</option>
                                        <option value="50"
                                            {{(isset($request->mostrar) ? ($request->mostrar == 50 ? 'selected' : '') : '')}}>
                                            50</option>
                                        <option value="25"
                                            {{(isset($request->mostrar) ? ($request->mostrar == 25 ? 'selected' : '') : '')}}>
                                            25</option>
                                        <option value="10"
                                            {{(isset($request->mostrar) ? ($request->mostrar == 10 ? 'selected' : '') : '')}}>
                                            10</option>
                                    </select>
                                </label>
                            </div>
                        </div>
                        <div class="col-sm-12 col-lg-8 ps-0">
                            <div
                                class="dt-action-buttons d-flex align-items-center justify-content-center- justify-content-lg-end flex-lg-nowrap flex-wrap">
                                <div class="dt-buttons d-inline-flex mt-80">
                                    @can('empleados.create')

                                    <div class="col-m-4">
                                        <a onclick="exportData()">
                                            <span class="btn btn-outline-success waves-effect waves-light mb-1"
                                                style="margin: right 10em;">{{__('Excel')}}</span>
                                        </a>
                                    </div>
                                    <div class="col-m-4">
                                        <a href="{{route('admin.empleadosxposiciones.create')}}">
                                            <span
                                                class="btn btn-primary waves-effect waves-light mb-1">{{__('New Employe')}}</span>
                                        </a>
                                    </div>

                                    @endcan
                                </div>


                            </div>
                        </div>
                </div>
            </div>
            </form>

            <table class="employe-list-table table">
                <thead class="table-light">
                    <tr>
                        <th>{{__('Name')}}</th>
                        <th>{{__('Departament')}}</th>
                        <th>{{__('Vicepresident')}}</th>
                        <th>{{__('Company')}}</th>
                        <th>{{__('Status')}}</th>
                        @canany(['empleados.edit'])
                        <th>{{__('Action')}}</th>
                        @endcan
                    </tr>
                </thead>
                <tbody>
                    @foreach($empleados as $empleado)
                    <tr class="odd">
                        <td class=" control" tabindex="0" style="display: none;"></td>
                        <td class="sorting_1">
                            <div class="d-flex justify-content-left align-items-center">
                                <div class="avatar-wrapper">
                                    <div class="avatar  me-1"><img src="http://127.0.0.1:8000/images/avatars/2.png"
                                            alt="Avatar" height="32" width="32"></div>
                                </div>
                                <div class="d-flex flex-column"><span
                                        class="fw-bolder">{{ucwords($empleado->nombres).' '.ucwords($empleado->apellidos)}}</span><small
                                        class="emp_post text-muted">{{$empleado->posicion->nombre_posicion}}</small>
                                </div>
                            </div>
                        </td>
                        <td>
                            <span class=" align-middle">
                                {{$empleado->posicion->departamento->nombre_departamento }}
                            </span>
                        </td>
                        <td>{{$empleado->posicion->departamento->vicepresidencia->nombre_vicepresidencia }}</td>
                        <td><span
                                class="text-nowrap">{{$empleado->posicion->departamento->vicepresidencia->empresa->nombre }}</span>
                        </td>
                        <td>
                            @if($empleado->estatus == 'A')
                            <span class="badge rounded-pill badge-light-success">{{__('Active')}}</span>
                            @else
                            <span class="badge rounded-pill badge-light-danger">{{__('Inactive')}}</span>
                            @endif
                        </td>
                        @canany(['empleados.edit'])
                        <td>
                            <div class="btn-group">
                                <a class="btn btn-sm dropdown-toggle hide-arrow" data-bs-toggle="dropdown">
                                    <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24" viewBox="0 0 24 24"
                                        fill="none" stroke="currentColor" stroke-width="2" stroke-linecap="round"
                                        stroke-linejoin="round" class="feather feather-more-vertical font-small-4">
                                        <circle cx="12" cy="12" r="1"></circle>
                                        <circle cx="12" cy="5" r="1"></circle>
                                        <circle cx="12" cy="19" r="1"></circle>
                                    </svg>
                                </a>
                                <div class="dropdown-menu dropdown-menu-end">
                                    <a href="{{route('admin.empleadosxposiciones.edit', $empleado->cod_empleado)}}"
                                        class="dropdown-item">
                                        <svg xmlns="http://www.w3.org/2000/svg" width="24" height="24"
                                            viewBox="0 0 24 24" fill="none" stroke="currentColor" stroke-width="2"
                                            stroke-linecap="round" stroke-linejoin="round"
                                            class="feather feather-file-text font-small-4 me-50">
                                            <path d="M14 2H6a2 2 0 0 0-2 2v16a2 2 0 0 0 2 2h12a2 2 0 0 0 2-2V8z">
                                            </path>
                                            <polyline points="14 2 14 8 20 8"></polyline>
                                            <line x1="16" y1="13" x2="8" y2="13"></line>
                                            <line x1="16" y1="17" x2="8" y2="17"></line>
                                            <polyline points="10 9 9 9 8 9"></polyline>
                                        </svg>
                                        {{__('Edit')}}
                                    </a>

                                </div>
                            </div>
                        </td>
                        @endcan
                    </tr>
                    @endforeach
                </tbody>
            </table>
        </div>
    </div>
    <div class="mb-5">
        {{ $resultados->appends(['page' => $request->page, 
                                      'mostrar' => $request->mostrar, 
                                      'buscar'=> $request->buscar])->links() }}
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

@section('page-script')
{{-- Page js files --}}
<script src="{{ asset(mix('js/scripts/pages/app-user-list.js')) }}"></script>
<script>
/*function downloadExcel() {
    let params = {
        // Aquí puedes agregar los parámetros que necesites enviar
        // por ejemplo, un rango de fechas para filtrar los empleados
        FiltroPosicion: document.getElementById('FiltroPosicion').value,
        FiltroDepartamento: document.getElementById('FiltroDepartamento').value,
        FiltroVicepresidencia: document.getElementById('FiltroVicepresidencia').value,
        FiltroEmpresa: document.getElementById('FiltroEmpresa').value
    }
    let token = document.head.querySelector('meta[name="csrf-token"]');
    console.log(token.content);
    fetch('/admin/app/empleadosxposiciones/downloadExcel', {
            method: 'POST',
            body: JSON.stringify(params),
            headers: {
                'Content-Type': 'application/json',
                'X-CSRF-TOKEN': token.content
            }
        })
        .then(response => response.blob())
        .then(blob => {
            let url = window.URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'empleados.xlsx';
            a.click();
        });
}*/

function downloadExcel() {


    var FiltroPosicion = document.getElementById('FiltroPosicion').value,
        FiltroDepartamento = document.getElementById('FiltroDepartamento').value,
        FiltroVicepresidencia = document.getElementById('FiltroVicepresidencia').value,
        FiltroEmpresa = document.getElementById('FiltroEmpresa').value;

    let token = document.head.querySelector('meta[name="csrf-token"]');
    console.log(token.content);
    fetch('/empleadosxposiciones/downloadExcel/' + FiltroEmpresa + '/' + FiltroVicepresidencia + '/' +
            FiltroDepartamento + '/' + FiltroPosicion)
        .then(response => response.blob())
        .then(blob => {
            let url = window.URL.createObjectURL(blob);
            let a = document.createElement('a');
            a.href = url;
            a.download = 'empleados.xlsx';
            a.click();
        });
}

function exportData() {
    var FiltroPosicion = document.getElementById('FiltroPosicion').value,
        FiltroDepartamento = document.getElementById('FiltroDepartamento').value,
        FiltroVicepresidencia = document.getElementById('FiltroVicepresidencia').value,
        FiltroEmpresa = document.getElementById('FiltroEmpresa').value;
    var url = "{{ route('empleadosxposiciones.export') }}?FiltroEmpresa=" + FiltroEmpresa + "&FiltroVicepresidencia=" +
        FiltroVicepresidencia + "&FiltroDepartamento=" + FiltroDepartamento + "&FiltroPosicion=" + FiltroPosicion;
    window.location.href = url;
}

document.getElementById('FiltroDepartamento').addEventListener('change', function(event) {
    // Obtenemos el elemento select        
    document.getElementById('FiltroPosicion').value = '';
    document.getElementById('form_select').submit();

});

document.getElementById('FiltroVicepresidencia').addEventListener('change', function(event) {
    document.getElementById('FiltroPosicion').value = '';
    document.getElementById('FiltroDepartamento').value = '';
    document.getElementById('form_select').submit();
});

document.getElementById('FiltroEmpresa').addEventListener('change', function(event) {

    document.getElementById('FiltroPosicion').value = '';
    document.getElementById('FiltroDepartamento').value = '';
    document.getElementById('FiltroVicepresidencia').value = '';
    document.getElementById('form_select').submit();

});
</script>
@endsection