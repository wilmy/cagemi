<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\PosicionesXDepartamentos;
use Illuminate\Support\Facades\DB;
use App\Models\DepartamentosXVicepresidencias;
use App\Models\DireccionesVicepresidencias;
use App\Models\EmpresasXGruposEmpresariales;

class PosicionesXDepartamentosController extends Controller
{
    //

    public function index(Request $request)
    {
        $buscar         = (isset($request->buscar) ? $request->buscar : '');
        $mostrar        = (isset($request->mostrar) ? $request->mostrar : 100);
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;

        if($buscar != '')
        {
           
            $resultados = DB::table('tb_posiciones_x_departamento')
                        ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_posiciones_x_departamento.*')
                        ->where([['tb_posiciones_x_departamento.nombre_posicion', 'LIKE', '%'.$buscar.'%'], ['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->orderBy('nombre_posicion', 'ASC')->paginate($mostrar);
        }
        else
        {
            $resultados = DB::table('tb_posiciones_x_departamento')
                        ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_posiciones_x_departamento.*')
                        ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->orderBy('nombre_posicion', 'ASC')->paginate($mostrar);

        }

        $coleccion = collect($resultados->items()); 

        $posiciones = $coleccion->map(function ($item, $key) {
            $posicion = new PosicionesXDepartamentos();

            $posicion->cod_posicion = $item->cod_posicion;
            $posicion->cod_departamento = $item->cod_departamento;
            $posicion->nombre_posicion = $item->nombre_posicion;
            $posicion->created_at = $item->created_at;
            $posicion->updated_at = $item->updated_at;

            return $posicion;
        });    
       
        //dd($posiciones);
        $pageConfigs = ['pageHeader' => true];
        
        return view('/content/apps/posicionesxdepartamentos.index', ['posiciones' => $posiciones, 
                                                          'resultados' => $resultados,
                                                            'pageConfigs' => $pageConfigs,
                                                            'request'=>$request]);
    }

    public function getPosiciones($cod_departamento = '')
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        
        $resultados = DB::table('tb_posiciones_x_departamento')
                        ->join('tb_departamentos_x_vicepresidencia', 'tb_departamentos_x_vicepresidencia.cod_departamento', '=', 'tb_posiciones_x_departamento.cod_departamento' )
                        ->join('tb_vicepresidencia_x_empresa', 'tb_departamentos_x_vicepresidencia.cod_vicepresidencia', '=', 'tb_vicepresidencia_x_empresa.cod_vicepresidencia' )
                        ->join('tb_empresas_x_grupos_empresariales', 'tb_empresas_x_grupos_empresariales.cod_empresa', '=', 'tb_vicepresidencia_x_empresa.cod_empresa')
                        ->select('tb_posiciones_x_departamento.*')
                        ->where([['tb_empresas_x_grupos_empresariales.cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                        ->where(function ($query) use ($cod_departamento) {
                            if ($cod_departamento != '') {
                                $query->where('tb_posiciones_x_departamento.cod_departamento', '=', $cod_departamento);                                
                            }
                        })
                        ->orderBy('nombre_posicion', 'ASC')->get();        

        $coleccion = collect($resultados); 

        $posiciones = $coleccion->map(function ($item, $key) {
            $posicion = new PosicionesXDepartamentos();

            $posicion->cod_posicion = $item->cod_posicion;
            $posicion->cod_departamento = $item->cod_departamento;
            $posicion->nombre_posicion = $item->nombre_posicion;
            $posicion->created_at = $item->created_at;
            $posicion->updated_at = $item->updated_at;

            return $posicion;
        });  
        return $posiciones;
    }

     /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $pageConfigs = ['pageHeader' => false];  
        
        $depart = new DepartamentosXVicepresidencias();

        $departamentos = $depart->getDepartamentos(); 

        return view('/content/apps/posicionesxdepartamentos/create', ['pageConfigs' => $pageConfigs,
                                                            'departamentos' =>  $departamentos ]);
    }

    public function store(Request $request)
    {
        if(isset($request->esFormulario)){
            
            $validator = $request->validate([
                'nombre' => ['required'],
                'departamento' => ['required'],
            ]);

            PosicionesXDepartamentos::create([
                                            'nombre_posicion' => $request->nombre,
                                            'cod_departamento' => $request->departamento
                                        ]);
            
            return redirect('admin/app/posicionesxdepartamentos/')
            ->with(['message' => 'Posicion creada correctamente ', 
                    'alert' => 'success']);
        }
        
        
        $validator = $request->validate([
            'departamento' => ['required'],
            'posiciones' => ['required'],
        ]);

        $nombre_posicion = $request->posiciones;

        $departamentos = $request->departamento;
        $empresa_arr = $request->empresa;
        $direccion_vp = $request->direccion_vp;

        if(count($nombre_posicion) > 0)
        {
            for($x = 0; $x < count($departamentos); $x++)
            {
                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr[$x])->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp[$x]],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $departamentos[$x]],
                                                                ['cod_vicepresidencia', $vicep->cod_vicepresidencia]])->first();
                if(isset($depart->cod_departamento))
                {
                    //Eliminmos los datos para insertar de nuevo 
                    PosicionesXDepartamentos::where('cod_departamento', $depart->cod_departamento)->delete();
                }
            }
            
            for($x = 0; $x < count($nombre_posicion); $x++)
            {
                $dda_arr = explode('||', $nombre_posicion[$x]);
                $nombre_departamento = $dda_arr[0];

                $empresa = EmpresasXGruposEmpresariales::where('nombre', $empresa_arr[$x])->first();
                $vicep = DireccionesVicepresidencias::where([['nombre_vicepresidencia', $direccion_vp[$x]],
                                                             ['cod_empresa', $empresa->cod_empresa]])->first();

                $depart = DepartamentosXVicepresidencias::where([['nombre_departamento', $nombre_departamento],
                                                                ['cod_vicepresidencia', $vicep->cod_vicepresidencia]])->first();
                if(isset($depart->cod_departamento))
                {
                    $data_in_posici = new PosicionesXDepartamentos();
                    $data_in_posici->cod_departamento = $depart->cod_departamento;
                    $data_in_posici->nombre_posicion = $dda_arr[1];
                    $data_in_posici->save();

                    //Actualizamos el registro de dato temporal en S
                    $data_temp = CargaDatos::where('posicion', $dda_arr[1])
                                            ->update(['validacion_posicon' => 'S',
                                                      'posicion_agregada' => 'S']);
                }
            }
        
            return redirect('admin/app/validacionDatos?vicp=4')
                        ->with(['message' => __('Position data uploaded successfully'), 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Failed to save positions'),
                                'alert' => 'danger']);
        }
    }

     /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $cod_departamento
     * @return \Illuminate\Http\Response
     */
    public function edit($cod_posicion)
    {
       
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        
        $posicion = PosicionesXDepartamentos::where('cod_posicion', $cod_posicion)->first();

        $pageConfigs = ['pageHeader' => true];
        
        $depart = new DepartamentosXVicepresidencias();

        $departamentos = $depart->getDepartamentos();  
        
        if($codGrupoEmpresarial == isset($posicion->departamento->vicepresidencia->empresa->cod_grupo_empresarial)){
            return view('/content/apps/posicionesxdepartamentos/edit', ['posicion' => $posicion, 
                                                         'departamentos' => $departamentos,
                                                         'pageConfigs' => $pageConfigs]);

        }else{
            return redirect('admin/app/posicionesxdepartamentos/');
        }
       
        
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cod_posicion)
    {
        $validator = $request->validate([
            'nombre' => ['required'],
            'departamento' => ['required'],
        ]);

        PosicionesXDepartamentos::where('cod_posicion', $cod_posicion)
                                        ->update([
                                                'nombre_posicion' => $request->nombre,
                                                'cod_departamento' => $request->departamento
                                            ]);
        
        return redirect('admin/app/posicionesxdepartamentos/')
        ->with(['message' => 'Posicion actulizada correctamente ', 
                'alert' => 'success']);
    }
}