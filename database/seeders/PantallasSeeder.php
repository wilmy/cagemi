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
        Pantallas::create([
                        'nombre' => 'Dashboard',
                        'slug' => 'dashboard', 
                        'url' => '/',
                        'descripcion' => 'Dashboard - pantalla inicial del sistema',
                        'orden' => 1,
                        'icono' => 'home',
                        'ver' => 'dashboard.index',
                        'estatus'  => 'A'
                        ]);

        $result_new_pantalla = Pantallas::create([ 
                                        'nombre' => 'Empleados',
                                        'slug' => 'empleados', 
                                        'url' => '',
                                        'descripcion' => 'Menu de empleados ',
                                        'orden' => 2,
                                        'icono' => 'user',
                                        'ver' => 'menu-empleados',
                                        'estatus'  => 'A'
                                    ]);

        Pantallas::create([ 'id_padre' => $result_new_pantalla->cod_pantalla,
                            'nombre' => 'Usuarios',
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

        /*$result_new_pantalla = Pantallas::create([
                                        'nombre' => 'Roles',
                                        'slug' => 'roles', 
                                        'url' => '',
                                        'descripcion' => 'Menu',
                                        'orden' => 3,
                                        'icono' => 'shield',
                                        'ver' => 'menu-roles',
                                        'estatus'  => 'A'
                                        ]);*/

        Pantallas::create(['id_padre' => $result_new_pantalla->cod_pantalla,
                            'nombre' => 'Roles',
                            'slug' => 'roles', 
                            'url' => 'admin/app/roles',
                            'descripcion' => 'Roles - pantalla para crear los roles de los usuarios del sistema',
                            'orden' => 2,
                            'icono' => 'circle',
                            'ver' => 'roles.index',
                            'crear' => 'roles.create',
                            'editar' => 'roles.edit',
                            'eliminar' => 'roles.destroy',
                            'estatus'  => 'A'
                            ]);

        $result_new_pantalla1 = Pantallas::create([ 
                                'nombre' => 'Empresa',
                                'slug' => 'empresa', 
                                'url' => '',
                                'descripcion' => 'Menu de empresas ',
                                'orden' => 3,
                                'icono' => 'home',
                                'ver' => 'menu-empresa',
                                'estatus'  => 'A'
                            ]);

        Pantallas::create(['id_padre' => $result_new_pantalla1->cod_pantalla,
                            'nombre' => 'Grupos Empresariales',
                            'slug' => 'grupo-empresarial', 
                            'url' => '/admin/app/grupoEmpresarial',
                            'descripcion' => 'Grupos de Empresas',
                            'orden' => 1,
                            'icono' => 'home',
                            'ver' => 'grupo_empresarial.index',
                            'estatus'  => 'A'
                            ]);
            
        Pantallas::create(['id_padre' => $result_new_pantalla1->cod_pantalla,
                            'nombre' => 'Carga de Datos',
                            'slug' => 'carga-datos', 
                            'url' => '/admin/app/cargaDatos',
                            'descripcion' => 'Carga de datos temporales',
                            'orden' => 2,
                            'icono' => 'download',
                            'ver' => 'cargadatos.index',
                            'estatus'  => 'A'
                            ]);
        
        Pantallas::create([
                        'id_padre' => $result_new_pantalla1->cod_pantalla,
                        'nombre' => 'Validacion de Datos',
                        'slug' => 'validacion-datos', 
                        'url' => '/admin/app/validacionDatos',
                        'descripcion' => 'Validacion de datos temporales',
                        'orden' => 3,
                        'icono' => 'home',
                        'ver' => 'validaciondatos.index',
                        'estatus'  => 'A'
                        ]);

        $result_new_pantalla3 = Pantallas::create([ 
                                    'nombre' => 'Configuracion',
                                    'slug' => 'configuracion', 
                                    'url' => '',
                                    'descripcion' => 'Menu de configuracion ',
                                    'orden' => 3,
                                    'icono' => 'home',
                                    'ver' => 'menu-configuracion',
                                    'estatus'  => 'A'
                                ]);

    }
}
