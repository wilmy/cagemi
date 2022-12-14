<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
use App\Models\GrupoEmpresarial;
use Illuminate\Support\Facades\DB;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;


class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

     //Para aplicar los permisos para eviar vilaciones o ataques
    public function __construct()
    {
        $this->middleware('can:users.create', ['only' => ['create']]);
        $this->middleware('can:users.edit', ['only' => ['edit', 'update']]); 
        $this->middleware('can:users.destroy', ['only' => ['destroy']]); 
    }

    public function index(Request $request)
    { 
        $buscar         = (isset($request->buscar) ? $request->buscar : '');
        $tipo_usuario   = (isset($request->tipo_usuario) ? $request->tipo_usuario : '');
        $mostrar        = (isset($request->mostrar) ? $request->mostrar : 100);

        if($tipo_usuario != '' && $buscar != '')
        {
            $users = DB::table('users')
                        ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->select('users.*', 'roles.name as nombre_rol')
                        ->where([['users.name', 'LIKE', '%'.$buscar.'%'], ['users.tipo_usuario', $tipo_usuario]])
                        ->orWhere(function($query) use ($buscar, $tipo_usuario) {
                            $query->where([['users.email', 'LIKE', '%'.$buscar.'%'], ['users.tipo_usuario', $tipo_usuario]]);
                        })
                        ->orderBy('created_at', 'DESC')
                        ->paginate($mostrar);
        }
        else if($buscar != '')
        {
            $users = DB::table('users')
                        ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->select('users.*', 'roles.name as nombre_rol')
                        ->where([['users.name', 'LIKE', '%'.$buscar.'%']])
                        ->orWhere(function($query) use ($buscar) {
                            $query->where([['users.email', 'LIKE', '%'.$buscar.'%']]);
                        })
                        ->orderBy('created_at', 'DESC')
                        ->paginate($mostrar);
        }
        else
        {
            $users = DB::table('users')
                        ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                        ->leftjoin('roles', 'roles.id', '=', 'model_has_roles.role_id')
                        ->select('users.*', 'roles.name as nombre_rol')
                        ->orderBy('created_at', 'DESC')
                        ->paginate($mostrar);
        }

        $total_user_acti = User::where('estatus', 'A')->count();
        $total_user_inac = User::where('estatus', 'I')->count();
        $pageConfigs = ['pageHeader' => true];
        
        return view('/content/apps/user/app-user-list', ['users' => $users, 
                                                        'total_user_acti' => $total_user_acti, 
                                                        'total_user_inac' => $total_user_inac, 
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
        $roles = Role::all();
        $grupo_empresarial = GrupoEmpresarial::where('estatus', 'A')->get();

        return view('/content/apps/user/create', ['roles' => $roles, 
                                                  'grupo_empresarial' => $grupo_empresarial, 
                                                  'pageConfigs' => $pageConfigs]);
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
            'nombre' => ['required', 'string', 'max:255'],
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);

        $cod_grupo_empresarial = (isset($request->grupo_empresarial) ? $request->grupo_empresarial : auth()->user()->cod_grupo_empresarial);
        $password = Str::random(6);
        $user = User::create([
            'cod_grupo_empresarial' => $cod_grupo_empresarial,
            'name' => $request->nombre,
            'surname' => $request->apellido,
            'email' => $request->email,
            'password' => Hash::make($password),
            'estatus' => 'A'
        ]);

        $user->assignRole($request->rol);
        $users = User::all(); 
        $pageConfigs = ['pageHeader' => false];

        return redirect('admin/app/users/')
                    ->with(['message' => __('User created successfully'), 
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
        $user = DB::table('users')
                    ->leftjoin('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->select('users.*', 'model_has_roles.role_id as rol')
                    ->find($id);

        $roles = Role::all();
        $grupo_empresarial = GrupoEmpresarial::where('estatus', 'A')->get();
        $pageConfigs = ['pageHeader' => true,];

        return view('/content/apps/user/edit', ['user' => $user, 
                                                'roles' => $roles, 
                                                'grupo_empresarial' => $grupo_empresarial,
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
            'apellido' => ['required', 'string', 'max:255'],
            'email' => ['required', 'string', 'email', 'max:255']
        ]);


        $cod_grupo_empresarial = (isset($request->grupo_empresarial) ? $request->grupo_empresarial : auth()->user()->cod_grupo_empresarial);

        //Actualizamos los datos del usuario enviado
        User::where('id', $id)
            ->update([
                    'cod_grupo_empresarial' => $cod_grupo_empresarial,
                    'name' => $request->nombre,
                    'surname' => $request->apellido,
                    'estatus' => $request->estatus,
                ]);
        
        //Buscamos el usuario para poder realizar los cambios del Rol 
        $user = User::find($id);

        //Removemos los/el rol que contiene el usuario 
        $user->roles()->detach(); 

        //Asigamos el rol seleccionado en el formulario
        $user->assignRole($request->rol);
        
        
        return redirect('admin/app/users/')
                    ->with(['message' => __('User <b>'.$user->name.'</b> updated successfully'), 
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
