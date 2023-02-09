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
use Illuminate\Validation\Rule;
use App\Exports\EmpleadosExport;
use Maatwebsite\Excel\Facades\Excel;

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

        $emp = new EmpresasXGruposEmpresarialesController();

        $empresas = $emp->getEmpresas();

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


    

    public function getEmpleados($estatus = '', $mostrar='', $page='', $retorno='',  $grupo='')
    {
        if ($grupo =='') {
            $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;

        }else{
            $codGrupoEmpresarial = $grupo;
        }

        if ($mostrar !='' && $page !='' ) {
            
          
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
                            ->orderBy('nombres', 'ASC')->paginate($mostrar, ['*'], 'page', $page);

            $coleccion = collect($resultados->items()); 
        }else {
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
        }

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

        if($retorno =='E'){
            return $empleados;
        }elseif ($retorno =='R') {
            return $resultados;
        }else{
            
            return $empleados;
        }

        
    }

    public function downloadExcel(Request $request)
    {
        dd($request);
        $buscar         = (isset($request->buscar) ? $request->buscar : '');
        $FiltroEmpresa  = (isset($request->FiltroEmpresa) ? $request->FiltroEmpresa : '');
        $FiltroVicepresidencia  = (isset($request->FiltroVicepresidencia) ? $request->FiltroVicepresidencia : '');
        $FiltroDepartamento  = (isset($request->FiltroDepartamento) ? $request->FiltroDepartamento : '');
        $FiltroPosicion  = (isset($request->FiltroPosicion) ? $request->FiltroPosicion : '');
        $FiltroEstatus  = (isset($request->FiltroEstatus) ? $request->FiltroEstatus : '');

        // Obtener los empleados filtrados por las fechas enviadas en el request
        $resultados = DB::table('tb_empleados_x_posicion')
                            ->join('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=', 'tb_posiciones_x_departamento.cod_posicion' )
                            ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                            ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                            ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                            ->select('tb_empleados_x_posicion.*')
                            ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
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

        // Crear una instancia de PhpOffice Excel
        $excel = new \PhpOffice\PhpSpreadsheet\Spreadsheet();

        // Asignar el título de la hoja
        $excel->getActiveSheet()->setTitle('Empleados');

        // Asignar los encabezados de las columnas
        $excel->getActiveSheet()->setCellValue('A1', 'Empresa');
        $excel->getActiveSheet()->setCellValue('B1', 'Codigo');
        $excel->getActiveSheet()->setCellValue('C1', 'Nombre');
        $excel->getActiveSheet()->setCellValue('D1', 'Apellido');
        $excel->getActiveSheet()->setCellValue('E1', 'Posicion');
        $excel->getActiveSheet()->setCellValue('F1', 'Direccion o Vicepresidencia');
        $excel->getActiveSheet()->setCellValue('G1', 'Departamento');
        $excel->getActiveSheet()->setCellValue('H1', 'Documento');
        $excel->getActiveSheet()->setCellValue('I1', 'Correo');
        $excel->getActiveSheet()->setCellValue('J1', 'Celular');
        $excel->getActiveSheet()->setCellValue('K1', 'Fecha de nacimiento');
        $excel->getActiveSheet()->setCellValue('L1', 'Telefono Institucional');
        $excel->getActiveSheet()->setCellValue('M1', 'Extencion');

        // Iniciar en la segunda fila para evitar sobreescribir los encabezados
        $row = 2;

        // Recorrer los empleados y agregarlos al archivo
        foreach ($empleados as $empleado) {
            $excel->getActiveSheet()->setCellValue('A'.$row, $empleado->posicion->departamento->vicepresidencia->empresa->nombre);
            $excel->getActiveSheet()->setCellValue('B'.$row, $empleado->cod_empleado);
            $excel->getActiveSheet()->setCellValue('C'.$row, $empleado->nombres);
            $excel->getActiveSheet()->setCellValue('D'.$row, $empleado->apellidos);
            $excel->getActiveSheet()->setCellValue('E'.$row, $empleado->posicion->nombre_posicion);
            $excel->getActiveSheet()->setCellValue('F'.$row, $empleado->posicion->departamento->vicepresidencia->nombre_vicepresidencia);
            $excel->getActiveSheet()->setCellValue('G'.$row, $empleado->posicion->departamento->nombre_departamento);
            $excel->getActiveSheet()->setCellValue('H'.$row, $empleado->documento);
            $excel->getActiveSheet()->setCellValue('I'.$row, $empleado->correo_personal);
            $excel->getActiveSheet()->setCellValue('J'.$row, $empleado->telefono_movil);
            $excel->getActiveSheet()->setCellValue('L'.$row, $empleado->fecha_nacimiento);
            $excel->getActiveSheet()->setCellValue('M'.$row, $empleado->telefono_institucional);
            $excel->getActiveSheet()->setCellValue('N'.$row, $empleado->extencion);
            $row++;
        }

        // Configurar la respuesta para descargar el archivo
        $headers = [        'Content-Type' => 'application/vnd.ms-excel',        'Content-Disposition' => 'attachment; filename="empleados.xlsx"',    ];

        // Generar el archivo
        $writer = \PhpOffice\PhpSpreadsheet\IOFactory::createWriter($excel, 'Xlsx');
        ob_start();
        $writer->save('php://output');
        $excelFile = ob_get_contents();
        ob_end_clean();

        // Retornar la respuesta con los headers configurados
        return response()->make($excelFile, 200, $headers);
    }
    


    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];  
        
        $emp = new EmpresasXGruposEmpresarialesController();

        $empresas = $emp->getEmpresas(); 

        $empleados = $this->getEmpleados('A');

        return view('/content/apps/empleadosxposiciones/create', ['pageConfigs' => $pageConfigs,
                                                                  'empleados' => $empleados,
                                                            'empresas' =>  $empresas ]);
    }

    public function getEmpleadosPaginados($page='', $grupo ='')
    {
        if ($page == '') {   
            $page = 1;
        }
        $empleados = $this->getEmpleados('A', 10, $page, '', $grupo);
        

        return $empleados;
    }

    function checkDocumento($documento, $id_empresa, $validator) {
        $resultados = DB::table('tb_empleados_x_posicion')
                            ->join('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=', 'tb_posiciones_x_departamento.cod_posicion' )
                            ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                            ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                            ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                            ->select('tb_empleados_x_posicion.*')
                            ->where([['tb_empleados_x_posicion.documento', '=', $documento ]])
                            ->where([['tb_empresas_x_grupos_empresariales.cod_empresa', '=', $id_empresa]])
                            ->orderBy('nombres', 'ASC')->count();
            
        if($resultados>0){
            return back()->withErrors(['error' => 'No se pudo completar la acción debido a un error en el sistema.'])->withInput();
        }
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        
            $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
            //dd($request);
            
            if(isset($request->esFormulario)){
                $validator = $request->validate([
                    'nombre' => ['required'],
                    'apellido' => ['required'],
                    'codigo_empleado' => ['required'],
                    'documento' => ['required'],
                    'telefono_movil' => ['required'],
                    'cod_supervisor' => ['required'],
                    'posicion' => ['required'],
                ]);
                $this->checkDocumento($request->documento, $request->empresas, $validator);

                $foto = '';

                $file_image = $request->file('foto');
                
                if($request->hasFile('foto'))
                {
                    $directorio = 'images/gruposEmpresariales/grupo'.$codGrupoEmpresarial.'/'.'fotoEmpleados';
                    if (!is_dir($directorio)) {
                        mkdir($directorio, 0775, true);
                    }
    
                    $nombre_imge = rand().trim($request->nombre).'-'.(date('Y-m-d')).'.'.$file_image->getClientOriginalExtension();
                    if($file_image->move($directorio, $nombre_imge))
                    {
                        $foto  = $nombre_imge;
                    }
                    else
                    {
                        $foto = '';
                    }
                }
                else
                {
                    $foto = '';
                }
    
    
                $empleado = EmpleadosXPosicion::create([
                                                    'cod_empleado_empresa' => $request->codigo_empleado,
                                                    'cod_posicion' => $request->posicion,
                                                    'cod_supervisor' => $request->cod_supervisor,
                                                    'nombres' => $request->nombre,
                                                    'apellidos' => $request->apellido,
                                                    'documento' => $request->documento,
                                                    'telefono_movil' => $request->telefono_movil,
                                                    'telefono_institucional' => $request->telefono_institucional,
                                                    'correo_institucional' => $request->correo_institucional,
                                                    'extencion' => $request->extencion,
                                                    'correo_personal' => $request->correo_personal,
                                                    'fecha_nacimiento' => $request->fecha_nacimiento,
                                                    'fecha_nacimiento' => $request->fecha_nacimiento,
                                                    'foto' => $foto,
                                                    'estatus' => 'A'
                                                ]);

                $user = User::create([
                    'cod_grupo_empresarial' => $codGrupoEmpresarial,
                    'name' => $request->nombre,
                    'surname' => $request->apellido,
                    'cod_empleado' => $request->codigo_empleado,
                    'password' => Hash::make($request->documento),
                    'estatus' => 'A'
                ]);
                
                
    
                $pageConfigs = ['pageHeader' => false];
    
                return redirect('admin/app/empleadosxposiciones/')
                            ->with(['message' => 'Registro creado correctamente ', 
                                    'alert' => 'success']);
        }
        
        //COdigo de carga macima de las validaciones de los datos
        $validator = $request->validate([
            'empleados' => ['required'],
            //'posiciones' => ['required'],
        ]);

        
        /*$departamentos  = $request->departamento;
        $empresa_arr    = $request->empresa;
        $direccion_vp   = $request->direccion_vp;
        $posiciones     = $request->posiciones;*/
        $empledos       = $request->empleados;

        if(count($empledos) > 0)
        {
            for($x = 0; $x < count($empledos); $x++)
            {
                $dda_arr  = explode('||', $empledos[$x]);
                $empresa_arr            = trim($dda_arr[0]);
                $direccion_vp           = $dda_arr[1];
                $departamentos          = $dda_arr[2];
                $nombre_posicion        = $dda_arr[3];

                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr)->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $departamentos],
                                                                ['cod_vicepresidencia', $vicep->cod_vicepresidencia]])->first();

                /*$posicion = PosicionesXDepartamentos::where([['nombre_posicion', $nombre_posicion],
                                                          ['cod_departamento', $depart->cod_departamento]])->first();
                if(isset($posicion->cod_departamento))
                {
                    //Eliminmos los datos para insertar de nuevo 
                    PosicionesXDepartamentos::where('cod_departamento', $posicion->cod_departamento)->delete();
                }*/
            }


            $activo_hasta  = date('Y-m-d H:i:s');
            
            for($x = 0; $x < count($empledos); $x++)
            {
                $dda_arr  = explode('||', $empledos[$x]);

                $empresa_arr            = trim($dda_arr[0]);
                $direccion_vp           = $dda_arr[1];
                $departamentos          = $dda_arr[2];
                $nombre_posicion        = $dda_arr[3];

                $cod_supervisor         = $dda_arr[4];
                $nombres                = $dda_arr[5];
                $apellidos              = $dda_arr[6];
                $documento              = trim($dda_arr[7]);
                $extencion              = $dda_arr[8];
                $correo_instutucional   = $dda_arr[9];
                $correo_personal        = $dda_arr[10];
                $cod_empleado           = $dda_arr[11];
                $fecha_nacimiento       = $dda_arr[12];
                $telefono_movil         = $dda_arr[13];
                $telefono_institucional = $dda_arr[14];
                $estatus                = 'A';
                                
                //$posicion = PosicionesXDepartamentos::where('nombre_posicion', $nombre_posicion)->first();
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr)->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $departamentos],
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
    public function edit($cod_empleado)
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        $emp = new EmpresasXGruposEmpresarialesController();

        $empresas = $emp->getEmpresas(); 
        $empleado = EmpleadosXPosicion::where('cod_empleado', $cod_empleado)->first();
        $supervisor = EmpleadosXPosicion::where('cod_empleado', $empleado->cod_supervisor)->first();
        $posicion = PosicionesXDepartamentos::where('cod_posicion', $empleado->cod_posicion)->first();
        $pageConfigs = ['pageHeader' => true];
        
       
        
        return view('/content/apps/empleadosxposiciones/edit', ['empleado' => $empleado, 
                                                         'codGrupoEmpresarial' => $codGrupoEmpresarial,
                                                         'empresas' => $empresas,
                                                         'posicion' => $posicion,
                                                         'supervisor' => $supervisor,
                                                         'pageConfigs' => $pageConfigs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cod_empleado)
    {
        
        if(isset($request->esFormulario)){
            $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
            
            if(isset($request->esFormulario)){
                $validator = $request->validate([
                    'nombre' => ['required'],
                    'apellido' => ['required'],
                    'codigo_empleado' => ['required'],
                    'documento' => ['required'],
                    'telefono_movil' => ['required'],
                    'cod_supervisor' => ['required'],
                    'posicion' => ['required'],
                ]);

                

                $foto = '';

                $file_image = $request->file('foto');
                
                if($request->hasFile('foto'))
                {
                    $directorio = 'images/gruposEmpresariales/grupo'.$codGrupoEmpresarial.'/'.'fotoEmpleados';
                    if (!is_dir($directorio)) {
                        mkdir($directorio, 0775, true);
                    }
    
                    $nombre_imge = rand().trim($request->nombre).'-'.(date('Y-m-d')).'.'.$file_image->getClientOriginalExtension();
                    if($file_image->move($directorio, $nombre_imge))
                    {
                        $foto  = $nombre_imge;
                    }
                    else
                    {
                        $foto = '';
                    }
                }
                else
                {
                    $foto = '';
                }
    
    
                $empleado = EmpleadosXPosicion::where('cod_empleado', $cod_empleado)->update([
                                                    'cod_empleado_empresa' => $request->codigo_empleado,
                                                    'cod_posicion' => $request->posicion,
                                                    'cod_supervisor' => $request->cod_supervisor,
                                                    'nombres' => $request->nombre,
                                                    'apellidos' => $request->apellido,
                                                    'documento' => $request->documento,
                                                    'telefono_movil' => $request->telefono_movil,
                                                    'telefono_institucional' => $request->telefono_institucional,
                                                    'correo_institucional' => $request->correo_institucional,
                                                    'extencion' => $request->extencion,
                                                    'correo_personal' => $request->correo_personal,
                                                    'fecha_nacimiento' => $request->fecha_nacimiento,
                                                    'fecha_nacimiento' => $request->fecha_nacimiento,
                                                    'foto' => $foto,
                                                    'estatus' => 'A'
                                                ]);
                
                
    
                $pageConfigs = ['pageHeader' => false];
    
                return redirect('admin/app/empleadosxposiciones/')
                            ->with(['message' => 'Registro actulizado correctamente ', 
                                    'alert' => 'success']);
        }
    }
        
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

    public function export(Request $request) 
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        $buscar         = (isset($request->buscar) ? $request->buscar : '');
        $FiltroEmpresa  = (isset($request->FiltroEmpresa) ? $request->FiltroEmpresa : '');
        $FiltroVicepresidencia  = (isset($request->FiltroVicepresidencia) ? $request->FiltroVicepresidencia : '');
        $FiltroDepartamento  = (isset($request->FiltroDepartamento) ? $request->FiltroDepartamento : '');
        $FiltroPosicion  = (isset($request->FiltroPosicion) ? $request->FiltroPosicion : '');
        $FiltroEstatus  = (isset($request->FiltroEstatus) ? $request->FiltroEstatus : '');

        // Obtener los empleados filtrados por las fechas enviadas en el request
        $resultados = DB::table('tb_empleados_x_posicion')
                            ->join('tb_posiciones_x_departamento', 'tb_empleados_x_posicion.cod_posicion', '=', 'tb_posiciones_x_departamento.cod_posicion' )
                            ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                            ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                            ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                            ->select('tb_empleados_x_posicion.*')
                            ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
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
                            ->orderBy('nombres', 'ASC')->get();

        $coleccion = collect($resultados); 
        
        $empleados = $coleccion->map(function ($item, $key) {
            $empleado = new EmpleadosXPosicion();

            $empleado->cod_empleado = $item->cod_empleado;
            $empleado->cod_empleado_empresa = $item->cod_empleado_empresa;
            $empleado->nombres = ucwords($item->nombres);
            $empleado->apellidos = ucwords($item->apellidos);
            $empleado->documento = $item->documento;

            return $empleado;
        });

        return Excel::download(new EmpleadosExport($empleados), 'Empleados.xlsx');
    }
    
}