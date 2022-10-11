<?php

namespace App\Http\Controllers;

use App\Models\User;
use Illuminate\Support\Str;
use Illuminate\Http\Request;
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

    public function index()
    {
        $users = DB::table('users')
                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->join('roles', 'roles.id', '=', 'model_has_roles.role_id')
                    ->select('users.*', 'roles.name as nombre_rol')
                    ->orderBy('created_at', 'DESC')
                    ->paginate(30);

        $total_user_acti = User::where('estatus', 'A')->count();
        $total_user_inac = User::where('estatus', 'I')->count();
        $pageConfigs = ['pageHeader' => false];
        
        return view('/content/apps/user/app-user-list', ['users' => $users, 
                                                        'total_user_acti' => $total_user_acti, 
                                                        'total_user_inac' => $total_user_inac, 
                                                        'pageConfigs' => $pageConfigs]);
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
        return view('/content/apps/user/create', ['roles' => $roles, 'pageConfigs' => $pageConfigs]);
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
            'email' => ['required', 'string', 'email', 'max:255', 'unique:users']
        ]);

        $password = Str::random(6);
        $user = User::create([
            'name' => $request->nombre,
            'email' => $request->email,
            'password' => Hash::make($password),
            'estatus' => 'A'
        ]);

        $user->assignRole($request->rol);
        $users = User::all(); 
        $pageConfigs = ['pageHeader' => false];

        return redirect('admin/app/users/')
                    ->with(['message' => 'Usuario creado correctamente '. $password, 
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
                    ->join('model_has_roles', 'users.id', '=', 'model_has_roles.model_id')
                    ->select('users.*', 'model_has_roles.role_id as rol')
                    ->find($id);

        $roles = Role::all();
        $pageConfigs = ['pageHeader' => true,];

        return view('/content/apps/user/edit', ['user' => $user, 'roles' => $roles, 'pageConfigs' => $pageConfigs]);
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
            'email' => ['required', 'string', 'email', 'max:255']
        ]);

        //Actualizamos los datos del usuario enviado
        User::where('id', $id)
            ->update([
                    'name' => $request->nombre,
                    'estatus' => $request->estatus,
                ]);
        
        //Buscamos el usuario para poder realizar los cambios del Rol 
        $user = User::find($id);

        //Removemos los/el rol que contiene el usuario 
        $user->roles()->detach(); 

        //Asigamos el rol seleccionado en el formulario
        $user->assignRole($request->rol);
        
        
        return redirect('admin/app/users/')
                    ->with(['message' => 'Usuario <b>'.$user->name.'</b> actualizado correctamente ', 
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
