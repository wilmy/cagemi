<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\DireccionesVicepresidencias;
use Illuminate\Support\Facades\DB;
use App\Models\EmpresasXGruposEmpresariales;

class DireccionesVicepresidenciasController extends Controller
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

        if($buscar != '')
        {
           
            $resultados = DB::table('tb_vicepresidencia_x_empresa')
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_vicepresidencia_x_empresa.*')
                        ->where([['tb_vicepresidencia_x_empresa.nombre_vicepresidencia', 'LIKE', '%'.$buscar.'%'], 
                                 ['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->orderBy('nombre_vicepresidencia', 'ASC')->paginate($mostrar);
        }
        else
        {
            $resultados = DB::table('tb_vicepresidencia_x_empresa')
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_vicepresidencia_x_empresa.*')
                        ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->orderBy('nombre_vicepresidencia', 'ASC')->paginate($mostrar);

        }

        $coleccion = collect($resultados->items()); 

        $vicepresidencias = $coleccion->map(function ($item, $key) {
            $vicepresidencia = new DireccionesVicepresidencias();

            $vicepresidencia->cod_vicepresidencia = $item->cod_vicepresidencia;
            $vicepresidencia->cod_empresa = $item->cod_empresa;
            $vicepresidencia->nombre_vicepresidencia = $item->nombre_vicepresidencia;
            $vicepresidencia->created_at = $item->created_at;
            $vicepresidencia->updated_at = $item->updated_at;

            return $vicepresidencia;
        });    
       
        //dd($viceprecidencias);
        $pageConfigs = ['pageHeader' => true];
        
        return view('/content/apps/vicepresidencias.index', ['vicepresidencias' => $vicepresidencias, 
                                                          'resultados' => $resultados,
                                                            'pageConfigs' => $pageConfigs,
                                                            'request'=>$request]);
    }

    public function getVicepresidencias($cod_empresa = '')
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;

        $resultados = DB::table('tb_vicepresidencia_x_empresa')
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_vicepresidencia_x_empresa.*')
                        ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->where(function ($query) use ($cod_empresa) {
                            if ($cod_empresa != '') {
                                $query->where('tb_vicepresidencia_x_empresa.cod_empresa', '=', $cod_empresa);                                
                            }
                        })
                        ->orderBy('nombre_vicepresidencia', 'ASC')->get();

        $coleccion = collect($resultados); 

        $viceprecidencias = $coleccion->map(function ($item, $key) {
            $viceprecidencia = new DireccionesVicepresidencias();

            $viceprecidencia->cod_vicepresidencia = $item->cod_vicepresidencia;
            $viceprecidencia->cod_empresa = $item->cod_empresa;
            $viceprecidencia->nombre_vicepresidencia = $item->nombre_vicepresidencia;
            $viceprecidencia->created_at = $item->created_at;
            $viceprecidencia->updated_at = $item->updated_at;

            return $viceprecidencia;
        });  

        return $viceprecidencias;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];  
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;      

        $empresas = EmpresasXGruposEmpresariales::where('cod_grupo_empresarial', $codGrupoEmpresarial)->get(); 

        return view('/content/apps/vicepresidencias/create', ['pageConfigs' => $pageConfigs,
                                                            'empresas' =>  $empresas ]);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        if(isset($request->esFormulario)){
            $validator = $request->validate([
                'nombre' => ['required'],
                'empresa' => ['required'],
            ]);

            DireccionesVicepresidencias::create([
                                                'nombre_vicepresidencia' => $request->nombre,
                                                'cod_empresa' => $request->empresa
                                            ]);
            
            return redirect('admin/app/vicepresidencias/')
            ->with(['message' => 'Vicepresicidencia o Direccion creada correctamente ', 
                    'alert' => 'success']);
        }
       
        $validator = $request->validate([
            'vicepresidencias' => ['required'],
            'empresa' => ['required'],
        ]);

        $nombre_vicepresidencias = $request->vicepresidencias;
        $empresa_nombre = $request->empresa;
        if(count($nombre_vicepresidencias) > 0)
        {
            for($x = 0; $x < count($empresa_nombre); $x++)
            {
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_nombre[$x])->first();
                if(isset($empresa->cod_empresa))
                {
                    //Eliminmos los datos para insertar de nuevo las nuevas empresas o existentes
                    DireccionesVicepresidencias::where('cod_empresa', $empresa->cod_empresa)->delete();
                }
            }
            
            for($x = 0; $x < count($nombre_vicepresidencias); $x++)
            {
                $dda_arr = explode('||', $nombre_vicepresidencias[$x]);
                $nombre_empresa = $dda_arr[0];
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $nombre_empresa)->first();
                if(isset($empresa->cod_empresa))
                {
                    $data_in_vicepre = new DireccionesVicepresidencias();
                    $data_in_vicepre->cod_empresa = $empresa->cod_empresa;
                    $data_in_vicepre->nombre_vicepresidencia = $dda_arr[1];
                    $data_in_vicepre->save();

                     //Actualizamos el registro de dato temporal a S
                     $data_temp = CargaDatos::where('direccion_vp', $dda_arr[1])
                                            ->update(['validacion_VP' => 'S',
                                                      'vicepresidencia_agregada' => 'S']);
                }
            }
        
            return redirect('admin/app/validacionDatos?vicp=2')
                        ->with(['message' => __('Vice-presidency data loaded correctly'), 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Error saving vice presidencies'),
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
    public function edit($cod_viceprsidencia)
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;  

        $vicepresidencia = DireccionesVicepresidencias::where('cod_vicepresidencia', $cod_viceprsidencia)->first();

        $pageConfigs = ['pageHeader' => true];
        
        $empresas = EmpresasXGruposEmpresariales::where('cod_grupo_empresarial', $codGrupoEmpresarial)->get();
        
        return view('/content/apps/vicepresidencias/edit', ['empresas' => $empresas, 
                                                         'vicepresidencia' => $vicepresidencia,
                                                         'pageConfigs' => $pageConfigs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cod_vicepresidencia)
    {
        $validator = $request->validate([
            'nombre' => ['required'],
            'empresa' => ['required'],
        ]);

        DireccionesVicepresidencias::where('cod_vicepresidencia', $cod_vicepresidencia)
                                        ->update([
                                                'nombre_vicepresidencia' => $request->nombre,
                                                'cod_empresa' => $request->empresa
                                            ]);
        
        return redirect('admin/app/vicepresidencias/')
        ->with(['message' => 'Vicepresidencia actulizada correctamente ', 
                'alert' => 'success']);
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
