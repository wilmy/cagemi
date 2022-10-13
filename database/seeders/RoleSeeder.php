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
        Permission::create(['name' => 'menu-users'])->syncRoles([$admin, $manager]);
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

        //Permisos para el administrador del los grupos empresariales 
        Permission::create(['name' => 'grupo_empresarial.index'])->syncRoles([$admin]);
        Permission::create(['name' => 'grupo_empresarial.create'])->syncRoles([$admin]);
        Permission::create(['name' => 'grupo_empresarial.edit'])->syncRoles([$admin]);
    }
}
