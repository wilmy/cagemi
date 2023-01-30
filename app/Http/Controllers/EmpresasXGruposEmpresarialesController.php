<?php

namespace App\Http\Controllers;

use App\Models\CargaDatos;
use Illuminate\Http\Request;
use App\Models\EmpresasXGruposEmpresariales;

class EmpresasXGruposEmpresarialesController extends Controller
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
           
            $empresas = EmpresasXGruposEmpresariales::where([['nombre', 'LIKE', '%'.$buscar.'%'], 
                                                               ['cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                                                            ->orderBy('nombre', 'ASC')->paginate($mostrar);
        }
        else
        {
            $empresas = EmpresasXGruposEmpresariales::where([['cod_grupo_empresarial', '=', $codGrupoEmpresarial ]])
                                                            ->orderBy('nombre', 'ASC')->paginate($mostrar);

        }

        $pageConfigs = ['pageHeader' => true];
        $total_empresas_acti = EmpresasXGruposEmpresariales::where([['cod_grupo_empresarial', '=', $codGrupoEmpresarial ], ['estatus', '=', 'A' ]])->count();
        $total_empresas_inac = EmpresasXGruposEmpresariales::where([['cod_grupo_empresarial', '=', $codGrupoEmpresarial ], ['estatus', '=', 'I' ]])->count();
        
        return view('/content/apps/empresas.index', ['empresas' => $empresas,
                                                            'pageConfigs' => $pageConfigs,
                                                            'total_empresas_acti' => $total_empresas_acti,
                                                            'total_empresas_inac' => $total_empresas_inac,
                                                            'codGrupoEmpresarial' => $codGrupoEmpresarial,
                                                            'request'=>$request]);
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
        
        $pageConfigs = ['pageHeader' => false];
        return view('/content/apps/empresas/create', ['pageConfigs' => $pageConfigs ]);
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

        if(isset($request->esFormulario)){
            $validator = $request->validate([
                'nombre' => ['required'],                
            ]);

            $logo = '';

            $file_image = $request->file('logo');
            
            if($request->hasFile('logo'))
            {
                $directorio = 'images/gruposEmpresariales/grupo'.$codGrupoEmpresarial.'/'.'logoEmpresa';
                if (!is_dir($directorio)) {
                    mkdir($directorio, 0775, true);
                }

                $nombre_imge = rand().trim($request->nombre).'-'.(date('Y-m-d')).'.'.$file_image->getClientOriginalExtension();
                if($file_image->move($directorio, $nombre_imge))
                {
                    $logo  = $nombre_imge;
                }
                else
                {
                    $logo = '';
                }
            }
            else
            {
                $logo = '';
            }


            $grupo_empresarial = EmpresasXGruposEmpresariales::create([
                                                'nombre' => $request->nombre,
                                                'cod_grupo_empresarial' => $codGrupoEmpresarial,
                                                'logo' => $logo,
                                                'estatus' => 'A'
                                            ]);
            
            

            $pageConfigs = ['pageHeader' => false];

            return redirect('admin/app/empresas/')
                        ->with(['message' => 'Registro creado correctamente ', 
                                'alert' => 'success']);
        

        }
        
        $validator = $request->validate([
            'empresas' => ['required'],
        ]);

        $nombre_empresas = $request->empresas;
        if(count($nombre_empresas) > 0)
        {
            //Eliminmos los datos para insertar de nuevo las nuevas empresas o existentes
            EmpresasXGruposEmpresariales::where('cod_grupo_empresarial', auth()->user()->cod_grupo_empresarial)->delete();

            for($x = 0; $x < count($nombre_empresas); $x++)
            {
                $data_in_empresa = new EmpresasXGruposEmpresariales();
                $data_in_empresa->cod_grupo_empresarial = auth()->user()->cod_grupo_empresarial;
                $data_in_empresa->nombre = $nombre_empresas[$x];
                $data_in_empresa->save();

                //Actualizamos el registro de dato temporal de empresa
                $data_temp = CargaDatos::where('empresa', $nombre_empresas[$x])
                                        ->update(['validacion_empresa' => 'S',
                                                   'empresa_agregada' => 'S']);
                
            }
        
            return redirect('admin/app/validacionDatos?vicp=1')
                        ->with(['message' => __('Company data loaded correctly'), 
                                'alert' => 'success'
                                ]);
        }
        else
        {
            return redirect('admin/app/validacionDatos')
                        ->with(['message' => __('Error saving companies'), 
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
    public function edit($cod_empresa)
    {
        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        
        $empresa = EmpresasXGruposEmpresariales::where('cod_empresa', $cod_empresa)->first();
        $pageConfigs = ['pageHeader' => true];
        
       
        
        return view('/content/apps/empresas/edit', ['empresa' => $empresa, 
                                                         'codGrupoEmpresarial' => $codGrupoEmpresarial,
                                                         'pageConfigs' => $pageConfigs]);
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $cod_empresa)
    {
        $validator = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);

        $codGrupoEmpresarial = auth()->user()->cod_grupo_empresarial;
        
        $logo = '';

        $file_image = $request->file('logo');
        
        if($request->hasFile('logo'))
        {
            $directorio = 'images/gruposEmpresariales/grupo'.$codGrupoEmpresarial.'/'.'logoEmpresa';
            if (!is_dir($directorio)) {
                mkdir($directorio, 0775, true);
            }
            $nombre_imge = rand().trim($request->nombre).'-'.(date('Y-m-d')).'.'.$file_image->getClientOriginalExtension();
            if($file_image->move($directorio, $nombre_imge))
            {
                $logo  = $nombre_imge;
            }
            else
            {
                $logo = '';
            }
        }
        else
        {
            $logo = '';
        }

        if($logo != '')
        {
            //Actualizamos los datos del usuario enviado
            EmpresasXGruposEmpresariales::where('cod_empresa', $cod_empresa)
                            ->update([
                                    'nombre' => $request->nombre,
                                    'logo' => $logo,
                                    'estatus' => $request->estatus,
                                ]);

        }
        else
        {
            //Actualizamos los datos del usuario enviado
            EmpresasXGruposEmpresariales::where('cod_empresa', $cod_empresa)
                            ->update([
                                    'nombre' => $request->nombre,
                                    'estatus' => $request->estatus,
                                ]);
        }
        
        

        return redirect('admin/app/empresas/')
                    ->with(['message' => 'Regisro actualizado correctamente ', 
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