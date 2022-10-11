<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\Pantallas;

class PantallasSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        
        /**Creamos las pantallas por defecto */
        $result_new_pantll = Pantallas::create([
                                            'nombre' => 'Dashboard',
                                            'slug' => 'dashboard', 
                                            'url' => '/',
                                            'descripcion' => 'Dashboard - pantalla inicial del sistema',
                                            'orden' => 1,
                                            'icono' => 'home',
                                            'ver' => 'dashboard.index',
                                            'estatus'  => 'A'
                                            ]);

        $resul_new_user = Pantallas::create([ 
                                        'nombre' => 'Usuarios',
                                        'slug' => 'users', 
                                        'url' => '',
                                        'descripcion' => 'Menu de usuarios',
                                        'orden' => 2,
                                        'icono' => 'user',
                                        'ver' => 'menu-users',
                                        'estatus'  => 'A'
                                    ]);

        Pantallas::create([ 'id_padre' => $resul_new_user->id,
                            'nombre' => 'Lista de Usuarios',
                            'slug' => 'users', 
                            'url' => 'admin/app/users',
                            'descripcion' => 'Usuarios - pantalla para crear los usuarios del sistema',
                            'orden' => 1,
                            'icono' => 'circle',
                            'ver' => 'users.index',
                            'crear' => 'users.create',
                            'editar' => 'users.edit',
                            'eliminar' => 'users.destroy',
                            'estatus'  => 'A'
                            ]);

        $result_new_rol = Pantallas::create([
                                        'nombre' => 'Roles Y Permisos',
                                        'slug' => 'roles', 
                                        'url' => '',
                                        'descripcion' => 'Menu',
                                        'orden' => 3,
                                        'icono' => 'shield',
                                        'ver' => 'menu-roles',
                                        'estatus'  => 'A'
                                        ]);

        Pantallas::create(['id_padre' => $result_new_rol->id,
                            'nombre' => 'Roles',
                            'slug' => 'roles', 
                            'url' => 'admin/app/roles',
                            'descripcion' => 'Roles - pantalla para crear los roles de los usuarios del sistema',
                            'orden' => 1,
                            'icono' => 'circle',
                            'ver' => 'roles.index',
                            'crear' => 'roles.create',
                            'editar' => 'roles.edit',
                            'eliminar' => 'roles.destroy',
                            'estatus'  => 'A'
                            ]);

    }
}
