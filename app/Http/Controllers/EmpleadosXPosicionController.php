<?php

namespace App\Http\Controllers;

use App\Models\User;
use App\Models\CargaDatos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\DB;
use App\Models\EmpleadosXPosicion;
use App\Models\PosicionesXDepartamentos;
use App\Models\DepartamentosXVicepresidencias;
use App\Models\DireccionesVicepresidencias;
use App\Models\EmpresasXGruposEmpresariales;

class EmpleadosXPosicionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index(Request $request)
    {
        $buscar         = (isset($request->buscar) ? $request->buscar : '');
        $mostrar        = (isset($request->mostrar) ? $request->mostrar : 100);
        $FiltroEmpresa  = (isset($request->FiltroEmpresa) ? $request->FiltroEmpresa : '');
        $FiltroVicepresidencia  = (isset($request->FiltroVicepresidencia) ? $request->FiltroVicepresidencia : '');
        $FiltroDepartamento  = (isset($request->FiltroDepartamento) ? $request->FiltroDepartamento : '');
        $FiltroPosicion  = (isset($request->FiltroPosicion) ? $request->FiltroPosicion : '');
        $FiltroEstatus  = (isset($request->FiltroEstatus) ? $request->FiltroEstatus : '');
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        
        

        
        $resultados = DB::table('tb_empleados_x_posicion')
                        ->join('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=', 'tb_posiciones_x_departamento.cod_posicion' )
                        ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_empleados_x_posicion.*')
                        ->where(function ($query) use ($buscar) {
                            if ($buscar) {
                                $query->where('tb_empleados_x_posicion.nombres', 'LIKE', "%$buscar%");
                            }
                        })                        
                        ->where(function ($query) use ($codGrupoEmpresarial ) {
                            if ($codGrupoEmpresarial) {
                                $query->where('tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial);
                            }
                        })
                        ->where(function ($query) use ($FiltroEmpresa ) {
                            if ($FiltroEmpresa) {
                                $query->where('tb_empresas_x_grupos_empresariales.cod_empresa', '=', $FiltroEmpresa);                                
                            }
                        })
                        ->where(function ($query) use ($FiltroVicepresidencia ) {
                            if ($FiltroVicepresidencia) {
                                $query->where('tb_vicepresidencia_x_empresa.cod_vicepresidencia', '=', $FiltroVicepresidencia);                                
                            }
                        })
                        ->where(function ($query) use ($FiltroDepartamento ) {
                            if ($FiltroDepartamento) {
                                $query->where('tb_departamentos_x_vicepresidencia.cod_departamento', '=', $FiltroDepartamento);                                
                            }
                        })
                        ->where(function ($query) use ($FiltroPosicion ) {
                            if ($FiltroPosicion) {
                                $query->where('tb_posiciones_x_departamento.cod_posicion', '=', $FiltroPosicion);                                
                            }
                        })
                        ->where(function ($query) use ($FiltroEstatus ) {
                            if ($FiltroEstatus) {
                                $query->where('tb_empleados_x_posicion.estatus', '=', $FiltroEstatus);                                
                            }
                        })
                        ->orderBy('tb_empleados_x_posicion.nombres', 'ASC')->paginate($mostrar);

                        

        $coleccion = collect($resultados->items()); 

        $empleados = $coleccion->map(function ($item, $key) {
            $empleado = new EmpleadosXPosicion();

            $empleado->cod_empleado = $item->cod_empleado;
            $empleado->cod_empleado_empresa = $item->cod_empleado_empresa;
            $empleado->cod_posicion = $item->cod_posicion;
            $empleado->cod_supervisor = $item->cod_supervisor;
            $empleado->nombres = $item->nombres;
            $empleado->apellidos = $item->apellidos;
            $empleado->documento = $item->documento;
            $empleado->telefono_movil = $item->telefono_movil;
            $empleado->telefono_institucional = $item->telefono_institucional;
            $empleado->extencion = $item->extencion;
            $empleado->correo_institucional = $item->correo_institucional;
            $empleado->correo_personal = $item->correo_personal;
            $empleado->fecha_nacimiento = $item->fecha_nacimiento;
            $empleado->foto = $item->foto;
            $empleado->estatus = $item->estatus;
            $empleado->activo_hasta = $item->activo_hasta;
            $empleado->created_at = $item->created_at;
            $empleado->updated_at = $item->updated_at;

            return $empleado;
        });
        
        $objVice = new DireccionesVicepresidenciasController();
        $vicepresidencias = $objVice->getVicepresidencias($FiltroEmpresa);

        $depart = new DepartamentosXVicepresidenciasController();
        $departamentos = $depart->getDepartamentos($FiltroVicepresidencia); 

        $posicionesController = new PosicionesXDepartamentosController();
        $posiciones = $posicionesController->getPosiciones($FiltroDepartamento);
        
        $pageConfigs = ['pageHeader' => true];

        $empresas = $this->getEmpresas();

        $totalEmpleados     = count($this->getEmpleados());
        $empleadosActivos    = count($this->getEmpleados('A'));
        $empleadosInactivos = count($this->getEmpleados('I'));
        
        return view('/content/apps/empleadosxposiciones.index', ['empleados' => $empleados, 
                                                                'resultados' => $resultados,
                                                                'empresas' => $empresas,
                                                                'vicepresidencias' => $vicepresidencias,
                                                                'FiltroEmpresa' => $FiltroEmpresa,
                                                                'departamentos' => $departamentos,
                                                                'posiciones' => $posiciones,
                                                                'totalEmpleados' => $totalEmpleados,
                                                                'empleadosActivos' => $empleadosActivos,
                                                                'empleadosInactivos' => $empleadosInactivos,
                                                                'FiltroVicepresidencia' => $FiltroVicepresidencia,
                                                                'FiltroDepartamento' => $FiltroDepartamento,
                                                                'FiltroPosicion' => $FiltroPosicion,
                                                                'FiltroEstatus' => $FiltroEstatus,
                                                                'pageConfigs' => $pageConfigs,
                                                                'request'=>$request]);
    }

    public function getEmpleados($estatus = '')
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;

        $resultados = DB::table('tb_empleados_x_posicion')
                        ->join('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=', 'tb_posiciones_x_departamento.cod_posicion' )
                        ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_empleados_x_posicion.*')
                        ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->where(function ($query) use ($estatus) {
                            if ($estatus != '') {
                                $query->where('tb_empleados_x_posicion.estatus', '=', $estatus);                                
                            }
                        })
                        ->orderBy('nombres', 'ASC')->get();

        $coleccion = collect($resultados); 

        $empleados = $coleccion->map(function ($item, $key) {
            $empleado = new EmpleadosXPosicion();

            $empleado->cod_empleado = $item->cod_empleado;
            $empleado->cod_empleado_empresa = $item->cod_empleado_empresa;
            $empleado->cod_posicion = $item->cod_posicion;
            $empleado->cod_supervisor = $item->cod_supervisor;
            $empleado->nombres = ucwords($item->nombres);
            $empleado->apellidos = ucwords($item->apellidos);
            $empleado->documento = $item->documento;
            $empleado->telefono_movil = $item->telefono_movil;
            $empleado->telefono_institucional = $item->telefono_institucional;
            $empleado->extencion = $item->extencion;
            $empleado->correo_institucional = $item->correo_institucional;
            $empleado->correo_personal = $item->correo_personal;
            $empleado->fecha_nacimiento = $item->fecha_nacimiento;
            $empleado->foto = $item->foto;
            $empleado->estatus = $item->estatus;
            $empleado->activo_hasta = $item->activo_hasta;
            $empleado->created_at = $item->created_at;
            $empleado->updated_at = $item->updated_at;

            return $empleado;
        });

        return $empleados;
    }

    public function getEmpresas()
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;

        $empresas = EmpresasXGruposEmpresariales::where([['cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                                                            ->orderBy('nombre', 'ASC')->get();

        return $empresas;
    }


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $validator = $request->validate([
            'empleados' => ['required'],
            'posiciones' => ['required'],
        ]);

        $departamentos  = $request->departamento;
        $empresa_arr    = $request->empresa;
        $direccion_vp   = $request->direccion_vp;
        $posiciones     = $request->posiciones;
        $empledos       = $request->empleados;

        if(count($empledos) > 0)
        {
            for($x = 0; $x < count($posiciones); $x++)
            {
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr[$x])->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp[$x]],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $departamentos[$x]],
                                                                ['cod_vicepresidencia', $vicep->cod_vicepresidencia]])->first();

                $posicion = PosicionesXDepartamentos::where([['nombre_posicion', $posiciones[$x]],
                                                          ['cod_departamento', $depart->cod_departamento]])->first();
                if(isset($posicion->cod_departamento))
                {
                    //Eliminmos los datos para insertar de nuevo 
                    PosicionesXDepartamentos::where('cod_departamento', $posicion->cod_departamento)->delete();
                }
            }
            
            for($x = 0; $x < count($empledos); $x++)
            {
                $dda_arr                = explode('||', $empledos[$x]);
                $nombre_posicion        = $dda_arr[0];
                $cod_supervisor         = $dda_arr[1];
                $nombres                = $dda_arr[2];
                $apellidos              = $dda_arr[3];
                $documento              = trim($dda_arr[4]);
                $extencion              = $dda_arr[5];
                $correo_instutucional   = $dda_arr[6];
                $correo_personal        = $dda_arr[7];
                $cod_empleado           = $dda_arr[8];
                $fecha_nacimiento       = $dda_arr[9];
                $telefono_movil         = $dda_arr[10];
                $telefono_institucional = $dda_arr[11];
                $estatus                = 'A';
                $activo_hasta           = date('Y-m-d H:i:s');
                
                //$posicion = PosicionesXDepartamentos::where('nombre_posicion', $nombre_posicion)->first();
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr[$x])->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp[$x]],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $departamentos[$x]],
                                                                ['cod_vicepresidencia', $vicep->cod_vicepresidencia]])->first();

                $posicion = PosicionesXDepartamentos::where([['nombre_posicion', $nombre_posicion],
                                                             ['cod_departamento', $depart->cod_departamento]])->first();
                                                  
                if(isset($posicion->cod_posicion))
                {
                    
                    $data_in_emplead = new EmpleadosXPosicion();
                    $data_in_emplead->cod_empleado_empresa      = $cod_empleado;
                    $data_in_emplead->cod_posicion              = $posicion->cod_posicion;
                    $data_in_emplead->nombres                   = $nombres;
                    $data_in_emplead->apellidos                 = $apellidos;
                    $data_in_emplead->estatus                   = $estatus;
                    $data_in_emplead->documento                 = $documento;

                    $this->validateValue($data_in_emplead, 'cod_supervisor', $cod_supervisor);
                    $this->validateValue($data_in_emplead, 'telefono_institucional', $telefono_institucional);
                    $this->validateValue($data_in_emplead, 'extencion', $extencion);
                    $this->validateValue($data_in_emplead, 'correo_institucional', $correo_instutucional);
                    $this->validateValue($data_in_emplead, 'correo_personal', $correo_personal);
                    $this->validateValue($data_in_emplead, 'activo_hasta', $activo_hasta);
                    $this->validateValue($data_in_emplead, 'fecha_nacimiento', $fecha_nacimiento);
                    $this->validateValue($data_in_emplead, 'telefono_movil', $telefono_movil);

                    $data_in_emplead->save();

                    //Actualizamos el registro de dato temporal a S
                    $data_temp = CargaDatos::where('documento', $documento)
                                            ->update(['validacion_empleados' => 'S',
                                                      'empleado_agregado' => 'S']);

                    //Registro de usuarios 
                    $cod_grupo_empresarial =  auth()->user()->cod_grupo_empresarial;
                    $user = User::create([
                        'cod_grupo_empresarial' => $cod_grupo_empresarial,
                        'name' => $nombres,
                        'cod_empleado' => $cod_empleado,
                        'password' => Hash::make($documento),
                        'estatus' => 'A'
                    ]);
                }
            }
        
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Employee data uploaded successfully'),
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Error saving employees'), 
                                'alert' => 'danger']);
        }
    }

    public function validateValue($model, $input, $dataImput)
    {
        if($dataImput != '')
        {
            $model->$input = $dataImput;
        }
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}