<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Models\DireccionesVicepresidencias;
use App\Models\DepartamentosXVicepresidencias;
use App\Models\EmpresasXGruposEmpresariales;

class DepartamentosXVicepresidenciasController extends Controller
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
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        //dd($codGrupoEmpresarial);

        if($buscar != '')
        {
            $DepartamentosXVicepresidencias = DepartamentosXVicepresidencias::where([['nombre_departamento', 'LIKE', '%'.$buscar.'%'],
                                                                                     ['cod_grupo_empresarial', '=', $codGrupoEmpresarial]])
                                                                                    ->orderBy('nombre_departamento', 'ASC')
                                                                                    ->paginate($mostrar);
        }
        else
        {
            $resultados = DB::table('tb_departamentos_x_vicepresidencia')
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_departamentos_x_vicepresidencia.*')
                        ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->orderBy('nombre_departamento', 'ASC')->paginate($mostrar);

            $coleccion = collect($resultados->items());

           //dd($coleccion);

           $DepartamentosXVicepresidencias = $coleccion->map(function ($item, $key) {
                return new DepartamentosXVicepresidencias($item->cod_departamento, $item->cod_vicepresidencia, $item->nombre_departamento, $item->created_at, $item->updated_at);
            });            

           // dd($DepartamentosXVicepresidencias);

           

           // $DepartamentosXVicepresidencias = 
           /* $empresasXGruposEmpresariales = EmpresasXGruposEmpresariales::where([['cod_grupo_empresarial', '=', $codGrupoEmpresarial]])->get();
                                                                                //->orderBy('nombre_departamento', 'ASC')
                                                                               // ->paginate($mostrar);
                                                                                //->paginate($mostrar);*/
           

            /*$DepartamentosXVicepresidencias = array();
            foreach ($empresasXGruposEmpresariales as $empresa) {              
                foreach ($empresa->departamentos as $departamento) {
                    array_push($DepartamentosXVicepresidencias, $departamento);                    
                }       
            }*/

        }

       /* $total_departamentos_acti = DepartamentosXVicepresidencias::where('estatus', 'A')->count();
        $total_departamentos_inac = DepartamentosXVicepresidencias::where('estatus', 'I')->count();*/
        $pageConfigs = ['pageHeader' => true];
        
        return view('/content/apps/departamentos.index', ['DepartamentosXVicepresidencias' => $DepartamentosXVicepresidencias, 
                                                          // 'empresasXGruposEmpresariales' => $empresasXGruposEmpresariales,
                                                            //'total_departamentos_acti' => $total_grupo_acti, 
                                                            //'total_departamentos_inac' => $total_grupo_inac, 
                                                            'pageConfigs' => $pageConfigs,
                                                            'request'=>$request]);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];

        

        $viceprecidencias = DireccionesVicepresidencias::where([['nombre_departamento', 'LIKE', '%'.$buscar.'%']])
                                                                ->orderBy('nombre_vicepresidencia', 'ASC')->get();

        return view('/content/apps/departamentos/create', ['pageConfigs' => $pageConfigs,
                                                            'viceprecidencias' =>  $viceprecidencias ]);
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
            'departamentos' => ['required'],
            'direccion_vp' => ['required'],
        ]);

        $nombre_departamento = $request->departamentos;
        $direccion_vp = $request->direccion_vp;
        if(count($nombre_departamento) > 0)
        {
            for($x = 0; $x < count($direccion_vp); $x++)
            {
                $vicep = DireccionesVicepresidencias::where('nombre_vicepresidencia', $direccion_vp[$x])->first();

                if(isset($vicep->cod_vicepresidencia))
                {
                    //Eliminmos los datos para insertar de nuevo 
                    DepartamentosXVicepresidencias::where('cod_vicepresidencia', $vicep->cod_vicepresidencia)->delete();
                }
            }
            
            for($x = 0; $x < count($nombre_departamento); $x++)
            {
                $dda_arr = explode('||', $nombre_departamento[$x]);
                $nombre_vicepresidencia = $dda_arr[0];
                $vicep = DireccionesVicepresidencias::where('nombre_vicepresidencia', $nombre_vicepresidencia)->first();
                if(isset($vicep->cod_vicepresidencia))
                {
                    $data_in_depart = new DepartamentosXVicepresidencias();
                    $data_in_depart->cod_vicepresidencia = $vicep->cod_vicepresidencia;
                    $data_in_depart->nombre_departamento = $dda_arr[1];
                    $data_in_depart->save();

                    //Actualizamos el registro de dato temporal a S
                    $data_temp = CargaDatos::where('departamento', $dda_arr[1])
                                            ->update(['validacion_departamento' => 'S',
                                                      'departamento_agregado' => 'S']);
                }
            }
            
        
            return redirect('admin/app/validacionDatos?vicp=3')
                        ->with(['message' => __('Department data loaded correctly'), 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Error saving departments'),  
                                'alert' => 'danger']);
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
        return "editar ".$id;
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
