<?php

namespace App\Http\Controllers\Admin;

use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use App\Http\Controllers\Controller;
use Illuminate\Support\Facades\Auth;
use Spatie\Permission\Models\Permission;

class RoleController extends Controller
{
    //Para aplicar los permisos para eviar vilaciones o ataques
    public function __construct()
    {
        $this->middleware('can:roles.create', ['only' => ['create']]);
        $this->middleware('can:roles.edit', ['only' => ['edit', 'update']]); 
        $this->middleware('can:roles.destroy', ['only' => ['destroy']]); 
    }

    // Access Roles App
    public function index()
    {
        if(Auth::user()->super_usuario == 'S')
        {
            $roles = Role::withCount('users')
                        ->get();
        }
        else
        {
            $roles = Role::withCount('users')
                            ->where('name', '<>', 'admin')
                            ->get();
        }

        $pageConfigs = ['pageHeader' => false,];

        return view('/content/apps/rolesPermission/app-access-roles', ['roles' => $roles, 'pageConfigs' => $pageConfigs]);
    }

    public function show($id)
    {
        //
        dd($id);
    }

    public function create()
    {
        $permisos = Permission::all();
        $pageConfigs = ['pageHeader' => false,];

        $pantallas = array(
                            array('name' => 'Usuarios', 
                                'value' => 'users.index',
                                'tipo_permisos_pantallas' =>  array(
                                                                    array('id' => 1, 'name' => 'Ver', 'value' => 'users.index'),
                                                                    array('id' => 2, 'name' => 'Crear', 'value' => 'users.create'),
                                                                    array('id' => 3, 'name' => 'Editar', 'value' => 'users.edit'),
                                                                    array('id' => 4, 'name' => 'Eliminar', 'value' => 'users.destroy'),
                                                                    )
                                ),
                            array('name' => 'Roles', 
                                'value' => 'roles.index',
                                'tipo_permisos_pantallas' =>  array(
                                                                    array('id' => 5, 'name' => 'Ver', 'value' => 'roles.index'),
                                                                    array('id' => 6, 'name' => 'Crear', 'value' => 'roles.create'),
                                                                    array('id' => 7, 'name' => 'Editar', 'value' => 'roles.edit'),
                                                                    array('id' => 8, 'name' => 'Eliminar', 'value' => 'roles.destroy'),
                                                                    )
                                ),
                            array('name' => 'Dashboard', 
                                'value' => 'dashboard.index',
                                'tipo_permisos_pantallas' =>  array(
                                                                    array('id' => 9, 'name' => 'Ver', 'value' => 'dashboard.index'),
                                                                    )
                                ),
                        );

        return view('/content/apps/rolesPermission/create', ['permisos' => $permisos, 
                                                            'pantallas' => $pantallas, 
                                                            'pageConfigs' => $pageConfigs]);
    }

    public function edit($id)
    {
        $rol = Role::find($id);
        $permisos = Permission::all();
        $pageConfigs = ['pageHeader' => false,];

        $pantallas = array(
                            array('name' => 'Usuarios', 
                                 'value' => 'users.index',
                                 'tipo_permisos_pantallas' =>  array(
                                                                    array('id' => 1, 'name' => 'Ver', 'value' => 'users.index'),
                                                                    array('id' => 2, 'name' => 'Crear', 'value' => 'users.create'),
                                                                    array('id' => 3, 'name' => 'Editar', 'value' => 'users.edit'),
                                                                    array('id' => 4, 'name' => 'Eliminar', 'value' => 'users.destroy'),
                                                                    )
                                ),
                            array('name' => 'Roles', 
                                 'value' => 'roles.index',
                                 'tipo_permisos_pantallas' =>  array(
                                                                    array('id' => 5, 'name' => 'Ver', 'value' => 'roles.index'),
                                                                    array('id' => 6, 'name' => 'Crear', 'value' => 'roles.create'),
                                                                    array('id' => 7, 'name' => 'Editar', 'value' => 'roles.edit'),
                                                                    array('id' => 8, 'name' => 'Eliminar', 'value' => 'roles.destroy'),
                                                                    )
                                ),
                            array('name' => 'Dashboard', 
                                 'value' => 'dashboard.index',
                                 'tipo_permisos_pantallas' =>  array(
                                                                    array('id' => 9, 'name' => 'Ver', 'value' => 'dashboard.index'),
                                                                    )
                                ),
                        );
        
        return view('/content/apps/rolesPermission/edit', ['rol' => $rol, 
                                                            'pantallas' => $pantallas, 
                                                            'permisos' => $permisos,
                                                            'pageConfigs' => $pageConfigs]);
    }

    public function store(Request $request)
    {
        $validator = $request->validate([
            'nombreRol' => ['required', 'string', 'max:255', 'unique:roles,name']
        ]);

        $nombre = $request->get('nombreRol');
        $rol_asing = Role::create(['name' => $nombre]);
        
        if(isset($request->permission))
        {
            $permisos = $request->permission;
            
            if(count($permisos) > 0)
            {
                for($x =0; $x < count($permisos); $x++)
                {
                    if(Permission::where(['name' => $permisos[$x]])->count() <= 0) 
                    {
                        Permission::create(['name' => $permisos[$x]]);
                    }
                }
            }
        
            $rol_asing->revokePermissionTo($request->permission);
            $rol_asing->permissions()->sync([]);
            $rol_asing->givePermissionTo($request->permission);
        }

        return redirect('admin/app/roles/')
                    ->with(['message' => 'Rol actualizado correctamente ', 
                            'alert' => 'success']);
    }

    public function update(Request $request, $id)
    {
        $validator = $request->validate([
            'nombreRol' => ['required', 'string', 'max:255']
        ]);
        
        $rol_asing = Role::where('id', '=', $id)->update(['name' => $request->nombreRol]);
    
        $data_rol_asing = Role::find($id);
        if(isset($request->permission))
        {
            $permisos = $request->permission;
            
            if(count($permisos) > 0)
            {
                for($x =0; $x < count($permisos); $x++)
                {
                    if(Permission::where(['name' => $permisos[$x]])->count() <= 0) 
                    {
                        Permission::create(['name' => $permisos[$x]]);
                    }
                }
            }
        
            $data_rol_asing->revokePermissionTo($request->permission);
            $data_rol_asing->permissions()->sync([]);
            $data_rol_asing->givePermissionTo($request->permission);
        }
        else
        {
            $data_rol_asing->permissions()->sync([]);
        }

        return redirect('admin/app/roles/')
                    ->with(['message' => 'Rol actualizado correctamente ', 
                            'alert' => 'success']);
    }

    /*Funcion para eliminar un registro */    
    public function destroy($id)
    {
        //
    }

}
