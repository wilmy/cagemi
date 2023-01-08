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
   /* public function __construct()
    {
        $this->middleware('can:DepartamentosXVicepresidenciasController.create', ['only' => ['create']]);
        $this->middleware('can:DepartamentosXVicepresidenciasController.edit', ['only' => ['edit', 'update']]); 
        $this->middleware('can:DepartamentosXVicepresidenciasController.destroy', ['only' => ['destroy']]); 
    }*/

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
           
            $resultados = DB::table('tb_departamentos_x_vicepresidencia')
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_departamentos_x_vicepresidencia.*')
                        ->where([['tb_departamentos_x_vicepresidencia.nombre_departamento', 'LIKE', '%'.$buscar.'%'], ['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->orderBy('nombre_departamento', 'ASC')->paginate($mostrar);
        }
        else
        {
            $resultados = DB::table('tb_departamentos_x_vicepresidencia')
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_departamentos_x_vicepresidencia.*')
                        ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->orderBy('nombre_departamento', 'ASC')->paginate($mostrar);

        }

        $coleccion = collect($resultados->items()); 

        $DepartamentosXVicepresidencias = $coleccion->map(function ($item, $key) {
            $departamentos = new DepartamentosXVicepresidencias();

            $departamentos->cod_departamento = $item->cod_departamento;
            $departamentos->cod_vicepresidencia = $item->cod_vicepresidencia;
            $departamentos->nombre_departamento = $item->nombre_departamento;
            $departamentos->created_at = $item->created_at;
            $departamentos->updated_at = $item->updated_at;

            return $departamentos;
        });    
       
        //dd($DepartamentosXVicepresidencias);
        $pageConfigs = ['pageHeader' => true];
        
        return view('/content/apps/departamentos.index', ['DepartamentosXVicepresidencias' => $DepartamentosXVicepresidencias, 
                                                          'resultados' => $resultados,
                                                            'pageConfigs' => $pageConfigs,
                                                            'request'=>$request]);
    }

    public function getDepartamentos($cod_vicepresidencia = '')
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        
        $resultados = DB::table('tb_departamentos_x_vicepresidencia')
                    ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                    ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                    ->select('tb_departamentos_x_vicepresidencia.*')
                    ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                    ->where(function ($query) use ($cod_vicepresidencia) {
                        if ($cod_vicepresidencia != '') {
                            $query->where('tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', $cod_vicepresidencia);                                
                        }
                    })
                    ->orderBy('nombre_departamento', 'ASC')->get();        

        $coleccion = collect($resultados); 

        $DepartamentosXVicepresidencias = $coleccion->map(function ($item, $key) {
            $departamentos = new DepartamentosXVicepresidencias();

            $departamentos->cod_departamento = $item->cod_departamento;
            $departamentos->cod_vicepresidencia = $item->cod_vicepresidencia;
            $departamentos->nombre_departamento = $item->nombre_departamento;
            $departamentos->created_at = $item->created_at;
            $departamentos->updated_at = $item->updated_at;

            return $departamentos;
        });    

        return $DepartamentosXVicepresidencias;
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];
        
        $vicep = new DireccionesVicepresidenciasController();

        $viceprecidencias = $vicep->getVicepresidencias(); 

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
        if(isset($request->esFormulario)){
            
            $validator = $request->validate([
                'nombre' => ['required'],
                'direccion_vp' => ['required'],
            ]);

            DepartamentosXVicepresidencias::create([
                                                    'nombre_departamento' => $request->nombre,
                                                    'cod_vicepresidencia' => $request->direccion_vp
                                                ]);
            
            return redirect('admin/app/departamentos/')
            ->with(['message' => 'Departamento creado correctamente ', 
                    'alert' => 'success']);
        }

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
     * @param  int  $cod_departamento
     * @return \Illuminate\Http\Response
     */
    public function edit($cod_departamento)
    {
       
        
        $departamento = DepartamentosXVicepresidencias::where('cod_departamento', $cod_departamento)->first();
        $pageConfigs = ['pageHeader' => true];
        
        $vicep = new DireccionesVicepresidenciasController();

        $viceprecidencias = $vicep->getVicepresidencias(); 
       
        
        return view('/content/apps/departamentos/edit', ['departamento' => $departamento, 
                                                         'viceprecidencias' => $viceprecidencias,
                                                         'pageConfigs' => $pageConfigs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cod_departamento)
    {
        $validator = $request->validate([
            'nombre' => ['required'],
            'direccion_vp' => ['required'],
        ]);

        DepartamentosXVicepresidencias::where('cod_departamento', $cod_departamento)
                                        ->update([
                                                'nombre_departamento' => $request->nombre,
                                                'cod_vicepresidencia' => $request->direccion_vp
                                            ]);
        
        return redirect('admin/app/departamentos/')
        ->with(['message' => 'Departamento actulizado correctamente ', 
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
