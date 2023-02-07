@extends('layouts/contentLayoutMaster')

@section('title', __('Edit').' '.$empleado['nombres'])

@section('vendor-style')
<!-- Vendor css files -->
<link rel="stylesheet" href="{{ asset(mix('vendors/css/forms/select/select2.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/dataTables.bootstrap5.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/responsive.bootstrap5.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/buttons.bootstrap5.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/pickadate/pickadate.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/pickers/flatpickr/flatpickr.min.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('vendors/css/tables/datatable/rowGroup.bootstrap5.min.css')) }}">
@endsection

@section('page-style')
<!-- Page css files -->
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/form-validation.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-flat-pickr.css')) }}">
<link rel="stylesheet" href="{{ asset(mix('css/base/plugins/forms/pickers/form-pickadate.css')) }}">
@endsection

@section('content')
<div class="modal-content">
    <div class="modal-body px-5 pb-5">
        <div class="text-left mb-4">
            <h1 class="role-title">{{__('Update ')}}</h1>
        </div>

        <form id="addRoleForm" method="POST" class="row" enctype="multipart/form-data"
            action="{{route('admin.empleadosxposiciones.update', $empleado['cod_empleado'])}}">
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
            <input type="hidden" value="formulario" name="esFormulario">

            <input type="hidden" id="grupo"
                value="{{Crypt::encrypt($codGrupoEmpresarial, false, env('APP_ENCRYPTION'))}}" name="grupo">


            <div class="form-group row">
                <div class="mb-1 col-md-4">
                    <label for="register-email" class="form-label">{{__('Photo')}}</label>

                    @if ($empleado->foto != '')
                    <br>
                    <img src="{{url('images/gruposEmpresariales/grupo'.$codGrupoEmpresarial.'/fotoEmpleados/')}}/{{$empleado->foto}}"
                        style="width: 100px">
                    @endif
                    <input type="file" class="form-control" id="foto" name="foto" accept="image/*" />

                    @error('foto')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="mb-1 col-md-4">
                    <label for="register-nombre" class="form-label">{{__('First Name')}}</label>
                    <input type="text" class="form-control @error('nombre') is-invalid @enderror" id="register-nombre"
                        name="nombre" placeholder="" aria-describedby="register-nombre" tabindex="1" autofocus
                        value="{{ old('nombre', $empleado->nombres)}}" />
                    @error('name')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-1 col-md-4">
                    <label for="register-apellido" class="form-label">{{__('Last Name')}}</label>
                    <input type="text" class="form-control @error('apellido') is-invalid @enderror"
                        id="register-apellido" name="apellido" placeholder="" aria-describedby="register-apellido"
                        tabindex="1" autofocus value="{{ old('apellido', $empleado->apellidos) }}" />
                    @error('apellido')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="mb-1 col-md-4">
                    <label for="register-codigo_empleado" class="form-label">{{__('Employe number')}}</label>
                    <input type="text" class="form-control @error('codigo_empleado') is-invalid @enderror"
                        id="register-codigo_empleado" name="codigo_empleado" placeholder=""
                        aria-describedby="register-codigo_empleado" tabindex="1" autofocus
                        value="{{ old('codigo_empleado', $empleado->cod_empleado_empresa) }}" />
                    @error('codigo_empleado')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-1 col-md-4">
                    <label for="register-documento" class="form-label">{{__('Document')}}</label>
                    <input type="text" class="form-control @error('documento') is-invalid @enderror"
                        id="register-documento" name="documento" placeholder="" aria-describedby="register-documento"
                        tabindex="1" autofocus value="{{ old('documento', $empleado->documento) }}" />
                    @error('documento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="mb-1 col-md-4">
                    <label for="register-telefono_movil" class="form-label">{{__('Mobile number')}}</label>
                    <input type="text" class="form-control @error('telefono_movil') is-invalid @enderror"
                        id="register-telefono_movil" name="telefono_movil" placeholder=""
                        aria-describedby="register-telefono_movil" tabindex="1" autofocus
                        value="{{ old('telefono_movil', $empleado->telefono_movil) }}" />
                    @error('documento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-1 col-md-4">
                    <label for="register-telefono_institucional"
                        class="form-label">{{__('Intitucional number')}}</label>
                    <input type="text" class="form-control @error('telefono_institucional') is-invalid @enderror"
                        id="register-telefono_institucional" name="telefono_institucional" placeholder=""
                        aria-describedby="register-telefono_institucional" tabindex="1" autofocus
                        value="{{ old('telefono_institucional', $empleado->telefono_institucional) }}" />
                    @error('documento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="mb-1 col-md-4">
                    <label for="register-correo_institucional" class="form-label">{{__('Intitucional mail')}}</label>
                    <input type="text" class="form-control @error('correo_institucional') is-invalid @enderror"
                        id="register-correo_institucional" name="correo_institucional" placeholder=""
                        aria-describedby="register-correo_institucional" tabindex="1" autofocus
                        value="{{ old('correo_institucional', $empleado->correo_institucional) }}" />
                    @error('documento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-1 col-md-4">
                    <label for="register-correo_personal" class="form-label">{{__('Personal mail')}}</label>
                    <input type="text" class="form-control @error('correo_personal') is-invalid @enderror"
                        id="register-correo_personal" name="correo_personal" placeholder=""
                        aria-describedby="register-correo_personal" tabindex="1" autofocus
                        value="{{ old('correo_personal', $empleado->correo_personal) }}" />
                    @error('documento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>
            </div>

            <div class="form-group row">
                <div class="mb-1 col-md-4">
                    <label for="register-fecha_nacimiento" class="form-label">{{__('Birthdate')}}</label>
                    <input type="text" id="register-fecha_nacimiento" name="fecha_nacimiento"
                        class="form-control flatpickr-basic" value="{{$empleado->fecha_nacimiento}}"
                        placeholder="YYYY-MM-DD" />
                    @error('documento')
                    <span class="invalid-feedback" role="alert">
                        <strong>{{ $message }}</strong>
                    </span>
                    @enderror
                </div>

                <div class="mb-1 col-md-4">
                    <label for="register-cod_supervisor" class="form-label">{{__('Supervisor')}}</label>
                    <div class=" mb-1">
                        <div class="input-group">
                            <input type="text" class="form-control" id="supervisor" readonly
                                value="{{$supervisor->nombres.' '.$supervisor->apellidos}}"
                                placeholder="{{__('Supervisor')}}" aria-describedby="button-addon2" />
                            <button type="button" class="btn btn-primary waves-effect waves-light"
                                data-bs-toggle="modal" id="bt-buscar-supervisor" data-bs-target="#modalCenter"><i
                                    data-feather="search"></i></button>
                        </div>
                        <input type="hidden" value="{{$empleado->cod_supervisor}}" id="cod_supervisor"
                            name="cod_supervisor">
                        @error('documento')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="mb-1 col-md-4">
                        <label for="register-posiciones" class="form-label">{{__('Company')}}</label>

                        <select name="empresas" id="empresas" class="form-select">
                            <option value=""> {{__('Select Company')}} </option>
                            @isset($empresas)
                            @foreach($empresas as $empresa)
                            <option value="{{$empresa->cod_empresa}}"
                                {{($empresa->cod_empresa == $posicion->departamento->vicepresidencia->empresa->cod_empresa ? 'selected':'' )}}>
                                {{__($empresa->nombre)}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                        @error('empresa')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-1 col-md-4">
                        <label for="register-posiciones" class="form-label">{{__('Vicepresident or Direction')}}</label>

                        <select name="vicepresidencias" id="vicepresidencias" class="form-select">
                            <option value=""> {{__('Select Vicepresident or Direction')}} </option>
                            @isset($vicpepresidencia)
                            @foreach($vicpepresidencias as $vicpepresidencia)
                            <option value="{{$vicpepresidencia->cod_vicepresidencia}}">
                                {{__($vicpepresidencia->nombre_vicepresidencia)}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                        @error('vicepresidencia')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>

                <div class="form-group row">
                    <div class="mb-1 col-md-4">
                        <label for="register-posiciones" class="form-label">{{__('Departament')}}</label>

                        <select name="departamento" id="departamento" class="form-select">
                            <option value=""> {{__('Select Departament')}} </option>
                            @isset($departamentos)
                            @foreach($departamentos as $departamento)
                            <option value="{{$departamento->cod_departamento}}">
                                {{__($departamento->nombre_departamento)}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                        @error('departamento')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>

                    <div class="mb-1 col-md-4">
                        <label for="register-posiciones" class="form-label">{{__('Positios')}}</label>

                        <select name="posicion" id="posicion" class="form-select">
                            <option value=""> {{__('Select Position')}} </option>
                            @isset($posiciones)
                            @foreach($posiciones as $posicion)
                            <option value="{{$posicion->cod_posicion}}">
                                {{__($posicion->nombre_posicion)}}
                            </option>
                            @endforeach
                            @endisset
                        </select>
                        @error('Posiciones')
                        <span class="invalid-feedback" role="alert">
                            <strong>{{ $message }}</strong>
                        </span>
                        @enderror
                    </div>
                </div>
                <div class="col-4 text-center mt-2">
                    <a href="{{route('admin.empleadosxposiciones.index')}}" class="btn btn-outline-danger">
                        {{__('Cancel')}}
                    </a>
                    <button type="submit" class="btn btn-primary me-1" onclick="backblock()">{{__('Update')}}</button>

                </div>
        </form>

        <!--/ Add role form -->
        <div class="modal fade " id="modalCenter" tabindex="-1" style="display: none;" aria-modal="true">
            <div class="modal-dialog modal-xl modal-dialog-centered" role="document">
                <div class="modal-content">
                    <div class="modal-header">
                        <h5 class="modal-title" id="modalCenterTitle">{{__('Search employe')}}</h5>
                        <button type="button" class="btn-close" data-bs-dismiss="modal" aria-label="Close"></button>
                    </div>
                    <div class="modal-body">
                        <div class="row">
                            <div class="col mb-1 col-md-4"">
                        <label for=" nameWithTitle" class="form-label">Search</label>
                                <div class="input-group">
                                    <input type="text" class="form-control" placeholder="{{__('Supervisor')}}"
                                        aria-describedby="button-addon2" />
                                    <button type="button" class="btn btn-primary waves-effect waves-light"
                                        data-bs-toggle="modal" data-bs-target="#modalCenter"><i
                                            data-feather="search"></i></button>
                                </div>
                            </div>
                        </div>

                    </div>
                    <table class="employe-list-table table">
                        <thead class="table-light">
                            <tr>
                                <th>{{__('Name')}}</th>
                                <th>{{__('Departament')}}</th>
                                <th>{{__('Vicepresident')}}</th>
                                <th>{{__('Company')}}</th>
                                <th>{{__('Add')}}</th>
                            </tr>
                        </thead>
                        <tbody class="table-empleados">
                            <tr class="odd">
                                <td class=" control" tabindex="0" style="display: none;"></td>
                                <td class="sorting_1">
                                    <div class="d-flex justify-content-left align-items-center">
                                        <div class="avatar-wrapper">
                                            <div class="avatar  me-1"><img
                                                    src="http://127.0.0.1:8000/images/avatars/2.png" alt="Avatar"
                                                    height="32" width="32"></div>
                                        </div>
                                        <div class="d-flex flex-column"><span class="fw-bolder nombre_super">
                                            </span><small class="emp_post text-muted posicion_super"> </small>
                                        </div>

                                    </div>
                                </td>
                                <td>
                                    <span class=" align-middle departamento_super">
                                    </span>
                                </td>
                                <td class="vice_super"> </td>
                                <td class="empresa_super"><span class="text-nowrap">
                                    </span>
                                </td>

                                <td>
                                    <button type="button" id="bt_ data[i].cod_empleado  " data-bs-dismiss="modal"
                                        class="btn btn-icon btn-primary waves-effect waves-light botonPlus">
                                        <span><i data-feather="plus"></i></span>
                                    </button>
                                </td>
                            </tr>
                            <tr class="odd">
                                <td class=" control" tabindex="0" style="display: none;"></td>
                                <td class="sorting_1">
                                    <div class="d-flex justify-content-left align-items-center">
                                        <div class="avatar-wrapper">
                                            <div class="avatar  me-1"><img
                                                    src="http://127.0.0.1:8000/images/avatars/2.png" alt="Avatar"
                                                    height="32" width="32"></div>
                                        </div>
                                        <div class="d-flex flex-column"><span class="fw-bolder nombre_super">ADRIÁN
                                                NEFTALI
                                                LORENZO PINEDA ADRIÁN NEFTALI LORENZO PINEDA</span><small
                                                class="emp_post text-muted posicion_super">2</small></div>
                                    </div>
                                </td>
                                <td> <span class=" align-middle departamento_super">2</span> </td>
                                <td class="vice_super">2</td>
                                <td class="empresa_super"><span class="text-nowrap">2</span></td>
                                <td>
                                    <button class="btn btn-icon btn-primary waves-effect waves-light botonPlus"
                                        type="button" id="bt_169" data-bs-dismiss="modal"><span><i
                                                data-feather="plus"></i></span></button>
                                </td>
                            </tr>
                        </tbody>
                    </table>
                    <div class="modal-footer">
                        <div class="pagination-container ">
                            <button id="prev-page" data="0" class="btn btn-secondary" disabled>Anterior</button>
                            <button id="next-page" data="1" class="btn btn-primary">Siguiente</button>
                        </div>

                        <button type="button" class="btn btn-outline-primary waves-effect"
                            data-bs-dismiss="modal">{{__('Close')}}</button>
                    </div>
                </div>
            </div>
        </div>
    </div>
    <input type="hidden" name="ocultoVP" id="ocultoVP"
        value="{{$posicion->departamento->vicepresidencia->cod_vicepresidencia}}">
    <input type="hidden" name="ocultoDepa" id="ocultoDepa" value="{{$posicion->departamento->cod_departamento}}">
    <input type="hidden" name="ocultoPosi" id="ocultoPosi" value="{{$posicion->cod_posicion}}">
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
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.date.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/picker.time.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/pickadate/legacy.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/pickers/flatpickr/flatpickr.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/jszip.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/pdfmake.min.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/vfs_fonts.js')) }}"></script>
<script src="{{ asset(mix('vendors/js/tables/datatable/buttons.html5.min.js')) }}"></script>
@endsection

@section('page-script')
<!-- Page js files -->
<script src="{{ asset(mix('js/scripts/forms/pickers/form-pickers.js')) }}"></script>
<script src="{{ asset(mix('js/scripts/pages/app-user-list.js')) }}"></script>
<script>
document.addEventListener("DOMContentLoaded", function() {
    var selectEmpresas = document.getElementById("empresas");
    var valorEmpresas = selectEmpresas.value;
    var valorOculto = document.getElementById("ocultoVP").value;
    var valorOcultoDepa = document.getElementById("ocultoDepa").value;
    var valorOcultoPosi = document.getElementById("ocultoPosi").value;



    // Envía una solicitud HTTP al servidor para obtener los nuevos valores para el select hijo
    fetch("/api/vicepresidencias/" + valorEmpresas)
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            // Obtén el select hijo
            var selectHijo = document.getElementById("vicepresidencias");

            // limpia el select hijo
            selectHijo.innerHTML = "";
            var option1 = document.createElement("option");
            option1.value = "";
            option1.text = "Select Vicepresident or Direction";
            selectHijo.appendChild(option1);

            // Recorremos los valores obtenidos y los agregamos al select hijo
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].cod_vicepresidencia;
                option.text = data[i].nombre_vicepresidencia;
                selectHijo.appendChild(option);
            }
            for (var i = 0; i < selectHijo.options.length; i++) {
                // Si el valor del elemento es igual al valor del campo oculto, seleccionamos el elemento
                if (selectHijo.options[i].value == valorOculto) {
                    selectHijo.options[i].selected = true;
                    break;
                }
            }
        });

    fetch("/api/departamentos/" + valorOculto + "/" + grupo)
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            // Obtén el select hijo
            var selectHijo = document.getElementById("departamento");

            // limpia el select hijo
            selectHijo.innerHTML = "";
            var option1 = document.createElement("option");
            option1.value = "";
            option1.text = "Select Departament";
            selectHijo.appendChild(option1);

            // Recorremos los valores obtenidos y los agregamos al select hijo
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].cod_departamento;
                option.text = data[i].nombre_departamento;
                selectHijo.appendChild(option);
            }
            for (var i = 0; i < selectHijo.options.length; i++) {
                // Si el valor del elemento es igual al valor del campo oculto, seleccionamos el elemento
                if (selectHijo.options[i].value == valorOcultoDepa) {
                    selectHijo.options[i].selected = true;
                    break;
                }
            }
        });


    // Envía una solicitud HTTP al servidor para obtener los nuevos valores para el select hijo
    fetch("/api/posiciones/" + valorOcultoDepa + "/" + grupo)
        .then(function(response) {
            return response.json();
        })
        .then(function(data) {
            // Obtén el select hijo
            var selectHijo = document.getElementById("posicion");

            // limpia el select hijo
            selectHijo.innerHTML = "";
            var option1 = document.createElement("option");
            option1.value = "";
            option1.text = "Select Position";
            selectHijo.appendChild(option1);

            // Recorremos los valores obtenidos y los agregamos al select hijo
            for (var i = 0; i < data.length; i++) {
                var option = document.createElement("option");
                option.value = data[i].cod_posicion;
                option.text = data[i].nombre_posicion;
                selectHijo.appendChild(option);
            }
            for (var i = 0; i < selectHijo.options.length; i++) {
                // Si el valor del elemento es igual al valor del campo oculto, seleccionamos el elemento
                if (selectHijo.options[i].value == valorOcultoPosi) {
                    selectHijo.options[i].selected = true;
                    break;
                }
            }
        });


});




// Obtén el select padre
var selectEmpresa = document.getElementById("empresas");
var selectVice = document.getElementById("vicepresidencias");
var selectDepa = document.getElementById("departamento");
var selectPosi = document.getElementById("posicion");
var grupo = document.getElementById("grupo").value;


// Agrega un evento change al select padre
selectEmpresa.addEventListener("change", function() {
    // Obtén el valor seleccionado en el select padre
    var valorPadre = this.value;

    if (valorPadre != "") {

        // Envía una solicitud HTTP al servidor para obtener los nuevos valores para el select hijo
        fetch("/api/vicepresidencias/" + valorPadre)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                // Obtén el select hijo
                var selectHijo = document.getElementById("vicepresidencias");

                // limpia el select hijo
                selectHijo.innerHTML = "";
                var option1 = document.createElement("option");
                option1.value = "";
                option1.text = "Select Vicepresident or Direction";
                selectHijo.appendChild(option1);

                // Recorremos los valores obtenidos y los agregamos al select hijo
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i].cod_vicepresidencia;
                    option.text = data[i].nombre_vicepresidencia;
                    selectHijo.appendChild(option);
                }
            });
    }
});

selectVice.addEventListener("change", function() {
    // Obtén el valor seleccionado en el select padre
    var valorPadre = this.value;

    if (valorPadre != "") {

        // Envía una solicitud HTTP al servidor para obtener los nuevos valores para el select hijo
        fetch("/api/departamentos/" + valorPadre + "/" + grupo)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                // Obtén el select hijo
                var selectHijo = document.getElementById("departamento");

                // limpia el select hijo
                selectHijo.innerHTML = "";
                var option1 = document.createElement("option");
                option1.value = "";
                option1.text = "Select Departament";
                selectHijo.appendChild(option1);

                // Recorremos los valores obtenidos y los agregamos al select hijo
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i].cod_departamento;
                    option.text = data[i].nombre_departamento;
                    selectHijo.appendChild(option);
                }
            });
    }
});

selectDepa.addEventListener("change", function() {
    // Obtén el valor seleccionado en el select padre
    var valorPadre = this.value;

    if (valorPadre != "") {

        // Envía una solicitud HTTP al servidor para obtener los nuevos valores para el select hijo
        fetch("/api/posiciones/" + valorPadre + "/" + grupo)
            .then(function(response) {
                return response.json();
            })
            .then(function(data) {
                // Obtén el select hijo
                var selectHijo = document.getElementById("posicion");

                // limpia el select hijo
                selectHijo.innerHTML = "";
                var option1 = document.createElement("option");
                option1.value = "";
                option1.text = "Select Position";
                selectHijo.appendChild(option1);

                // Recorremos los valores obtenidos y los agregamos al select hijo
                for (var i = 0; i < data.length; i++) {
                    var option = document.createElement("option");
                    option.value = data[i].cod_posicion;
                    option.text = data[i].nombre_posicion;
                    selectHijo.appendChild(option);
                }
            });
    }
});

function asignarEventoBotonPlus() {
    document.querySelectorAll('.botonPlus').forEach(function(element) {
        element.addEventListener('click', function() {
            var idBoton = this.id;
            let posicionInicio = idBoton.indexOf("bt_") + 3;
            let textoDespuesDeBt = idBoton.substring(posicionInicio);
            document.getElementById('cod_supervisor').value = textoDespuesDeBt;

            var nombreSuper = document.querySelector('.nombre_super').textContent;
            document.getElementById('supervisor').value = nombreSuper;

        });
    });
}


document.getElementById('bt-buscar-supervisor').addEventListener('click', function() {
    document.querySelector('.table-empleados').innerHTML = "";
    // Enviar solicitud al servidor para obtener los siguientes 10 empleados
    fetch("/api/empleados/" + 1 + "/" + grupo) // suponiendo que estas en la pagina 1
        .then(response => response.json())
        .then(function(data) {
            for (var i = 0; i < data.length; i++) {
                var row = '<tr class="odd">' +
                    '<td class=" control" tabindex="0" style="display: none;"></td>' +
                    '<td class="sorting_1">' +
                    '<div class="d-flex justify-content-left align-items-center">' +
                    '<div class="avatar-wrapper">' +
                    '<div class="avatar  me-1"><img src="http://127.0.0.1:8000/images/avatars/2.png"' +
                    'alt="Avatar" height="32" width="32"></div>' +
                    '</div>' +
                    '<div class="d-flex flex-column"><span class="fw-bolder nombre_super">' +
                    data[i]
                    .nombres + ' ' + data[i].apellidos + '</span><small' +
                    ' class="emp_post text-muted posicion_super">' + data[i].cod_posicion +
                    '</small>' +
                    '</div>' +

                    '</div>' +
                    '</td>' +
                    '<td>' +
                    ' <span class=" align-middle departamento_super">' + data[i].cod_posicion +
                    '</span>' +
                    ' </td>' +
                    '<td class="vice_super">' + data[i].cod_posicion + '</td>' +
                    '<td class="empresa_super"><span class="text-nowrap">' + data[i]
                    .cod_posicion +
                    '</span>' +
                    '</td>' +

                    '<td>' +
                    '<button type="button" id="bt_' + data[i].cod_empleado +
                    '" data-bs-dismiss="modal"' +
                    'class="btn btn-icon btn-primary waves-effect waves-light botonPlus">' +
                    '</button>' +
                    '</td>' +
                    '</tr>';

                document.querySelector('.table-empleados').innerHTML += row;

            }
            asignarEventoBotonPlus();

            var botonesPlus = document.querySelectorAll(".botonPlus");
            // Recorrer cada botón
            botonesPlus.forEach(function(boton) {
                // Crear un elemento "i" con el atributo "data-feather" establecido como "plus"
                var icono = document.createElement("i");
                icono.setAttribute("data-feather", "plus");
                // Crear un elemento "span"
                var span = document.createElement("span");
                // Agregar el elemento "i" al elemento "span"
                span.appendChild(icono);
                // Agregar el elemento "span" al botón
                boton.appendChild(span);
            });

        })
        .catch(error => console.log(error));
});



document.getElementById('next-page').addEventListener('click', function() {
    document.querySelector('.table-empleados').innerHTML = "";
    var page = this.getAttribute('data');
    page = parseInt(page);
    var prevButton = document.getElementById("prev-page");
    document.getElementById("prev-page").removeAttribute("disabled");
    prevButton.setAttribute("data", page);
    page = page + 1;
    this.setAttribute("data", page);
    // Enviar solicitud al servidor para obtener los siguientes 10 empleados
    fetch("/api/empleados/" + page + "/" + grupo) // suponiendo que estas en la pagina 1
        .then(response => response.json())
        .then(function(data) {
            for (var i = 0; i < data.length; i++) {
                var row = '<tr class="odd">' +
                    '<td class=" control" tabindex="0" style="display: none;"></td>' +
                    '<td class="sorting_1">' +
                    '<div class="d-flex justify-content-left align-items-center">' +
                    '<div class="avatar-wrapper">' +
                    '<div class="avatar  me-1"><img src="http://127.0.0.1:8000/images/avatars/2.png"' +
                    'alt="Avatar" height="32" width="32"></div>' +
                    '</div>' +
                    '<div class="d-flex flex-column"><span class="fw-bolder nombre_super">' +
                    data[i]
                    .nombres + ' ' + data[i].apellidos + '</span><small' +
                    ' class="emp_post text-muted posicion_super">' + data[i].cod_posicion +
                    '</small>' +
                    '</div>' +

                    '</div>' +
                    '</td>' +
                    '<td>' +
                    ' <span class=" align-middle departamento_super">' + data[i].cod_posicion +
                    '</span>' +
                    ' </td>' +
                    '<td class="vice_super">' + data[i].cod_posicion + '</td>' +
                    '<td class="empresa_super"><span class="text-nowrap">' + data[i]
                    .cod_posicion +
                    '</span>' +
                    '</td>' +

                    '<td>' +
                    '<button type="button" id="bt_' + data[i].cod_empleado +
                    '" data-bs-dismiss="modal"' +
                    'class="btn btn-icon btn-primary waves-effect waves-light botonPlus">' +
                    '<span><i data-feather="plus"></i></span>' +
                    '</button>' +
                    '</td>' +
                    '</tr>';

                document.querySelector('.table-empleados').innerHTML += row;

            }
            asignarEventoBotonPlus();
        })
        .catch(error => console.log(error));
});
</script>
@endsection