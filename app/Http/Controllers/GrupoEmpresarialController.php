<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Models\GrupoEmpresarial;

class GrupoEmpresarialController extends Controller
{
    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function __construct()
    {
        $this->middleware('can:users.create', ['only' => ['create']]);
        $this->middleware('can:users.edit', ['only' => ['edit', 'update']]); 
        $this->middleware('can:users.destroy', ['only' => ['destroy']]); 
    }

    public function index(Request $request)
    { 
        $buscar         = (isset($request->buscar) ? $request->buscar : '');
        $mostrar        = (isset($request->mostrar) ? $request->mostrar : 100);

        if($buscar != '')
        {
            $GruposEmpresarial = GrupoEmpresarial::where([['nombre', 'LIKE', '%'.$buscar.'%']])
                                                ->orderBy('created_at', 'DESC')
                                                ->paginate($mostrar);
        }
        else
        {
            $GruposEmpresarial = GrupoEmpresarial::orderBy('created_at', 'DESC')->paginate($mostrar);
        }

        $total_grupo_acti = GrupoEmpresarial::where('estatus', 'A')->count();
        $total_grupo_inac = GrupoEmpresarial::where('estatus', 'I')->count();
        $pageConfigs = ['pageHeader' => true];
        
        return view('/content/apps/grupoEmpresarial.index', ['GruposEmpresarial' => $GruposEmpresarial, 
                                                            'total_grupo_acti' => $total_grupo_acti, 
                                                            'total_grupo_inac' => $total_grupo_inac, 
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

        return view('/content/apps/grupoEmpresarial/create', ['pageConfigs' => $pageConfigs]);
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
            'nombre' => ['required', 'string', 'max:255', 'unique:tb_grupos_empresariales'],
        ]);

        $logo = '';

        $file_image = $request->file('logo');
        
        if($request->hasFile('logo'))
        {
            $nombre_imge = rand().trim($request->nombre).'-'.(date('Y-m-d')).'.'.$file_image->getClientOriginalExtension();
            if($file_image->move('images/gruposEmpresariales', $nombre_imge))
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

        GrupoEmpresarial::create([
                                'nombre' => $request->nombre,
                                'logo' => $logo,
                                'estatus' => 'A'
                            ]);

        $pageConfigs = ['pageHeader' => false];

        return redirect('admin/app/grupoEmpresarial/')
                    ->with(['message' => 'Registro creado correctamente ', 
                            'alert' => 'success']);
        
        //return view('/content/apps/user/app-user-list', ['users' => $users, 'pageConfigs' => $pageConfigs]);
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
        $grupo_empresarial = GrupoEmpresarial::find($id);
        $pageConfigs = ['pageHeader' => true,];

        return view('/content/apps/grupoEmpresarial/edit', ['grupo_empresarial' => $grupo_empresarial, 
                                                            'pageConfigs' => $pageConfigs]);
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
        $validator = $request->validate([
            'nombre' => ['required', 'string', 'max:255'],
        ]);
        
        $logo = '';

        $file_image = $request->file('logo');
        
        if($request->hasFile('logo'))
        {
            $nombre_imge = rand().trim($request->nombre).'-'.(date('Y-m-d')).'.'.$file_image->getClientOriginalExtension();
            if($file_image->move('images/gruposEmpresariales', $nombre_imge))
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

        //Actualizamos los datos del usuario enviado
        GrupoEmpresarial::where('id', $id)
                        ->update([
                                'nombre' => $request->nombre,
                                'logo' => $logo,
                                'estatus' => $request->estatus,
                            ]);
        
        
        
        return redirect('admin/app/grupoEmpresarial/')
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
