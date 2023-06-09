<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class RoleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        //
        $admin = Role::create(['name' => 'admin']);
        $manager = Role::create(['name' => 'manager']);
        $developer = Role::create(['name' => 'developer']);

        Permission::create(['name' => 'dashboard.index'])->syncRoles([$admin, $manager, $developer]);

        /*Para los permisios de los usuarios */
        Permission::create(['name' => 'menu-empleados'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'users.index'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'users.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'users.edit'])->syncRoles([$admin]);
        Permission::create(['name' => 'users.destroy'])->syncRoles([$admin]);

        /*Para los usuarios y  roles */
        Permission::create(['name' => 'menu-roles'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'roles.index'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'roles.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'roles.edit'])->syncRoles([$admin]);
        Permission::create(['name' => 'roles.destroy'])->syncRoles([$admin]);

        /*Para los departamentos */
        Permission::create(['name' => 'menu-empresa'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'departamentos.index'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'departamentos.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'departamentos.edit'])->syncRoles([$admin]);

        /*Para los posiciones */
        Permission::create(['name' => 'posiciones.index'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'posiciones.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'posiciones.edit'])->syncRoles([$admin]);

        /*Para los empresas */
        Permission::create(['name' => 'empresas.index'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'empresas.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'empresas.edit'])->syncRoles([$admin]);

        /*Para los vicepresidencias */
        Permission::create(['name' => 'vicepresidencias.index'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'vicepresidencias.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'vicepresidencias.edit'])->syncRoles([$admin]);

        /*Para los vicepresidencias */
        Permission::create(['name' => 'empleados.index'])->syncRoles([$admin, $manager]);
        Permission::create(['name' => 'empleados.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'empleados.edit'])->syncRoles([$admin]);

        Permission::create(['name' => 'menu-configuracion'])->syncRoles([$admin]);

        //Permisos para el administrador del los grupos empresariales 
        Permission::create(['name' => 'grupo_empresarial.index'])->syncRoles([$admin]);
        Permission::create(['name' => 'grupo_empresarial.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'grupo_empresarial.edit'])->syncRoles([$admin]);
        
        //Permisos para la carga de datos temporales
        Permission::create(['name' => 'cargadatos.index'])->syncRoles([$admin]);
        Permission::create(['name' => 'cargadatos.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'cargadatos.edit'])->syncRoles([$admin]);

        //Pantalla para validar los datos cargados en el temporal
        Permission::create(['name' => 'validaciondatos.index'])->syncRoles([$admin]);
        Permission::create(['name' => 'validaciondatos.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'validaciondatos.edit'])->syncRoles([$admin]);
    }
}