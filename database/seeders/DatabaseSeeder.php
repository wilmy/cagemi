<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use App\Models\User;
use Spatie\Permission\Models\Role;
use Spatie\Permission\Models\Permission;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // \App\Models\User::factory(10)->create();
        $this->call(RoleSeeder::class);
        $this->call(UserSeeder::class);
        
        User::factory(10)->create()->each(function($user){
            $user->assignRole('developer');
        });

        /*$administrador = User::factory()->create([
            'name' => 'Wilmy Rodriguez',
            'email' => 'wilmyrb@gmail.com',
        ]);

        $user = User::factory()->create([
            'name' => 'John Doe',
            'email' => 'wr@example.com',
        ]);
        //Apellido::factory(100)->create();

        $admin = Role::create(['name' => 'Administrador']);
        $user = Role::create(['name' => 'user']);

        //CRUD

        $permisions= [
            'create',
            'read',
            'update',
            'delete'
        ];

        foreach(Role::all() as $role) {
            foreach($permisions as $p) {
                Permission::create(['name' => "{$role->name} $p"]);
            }
        }

        $admin->syncPermissions(Permission::all());
        $user->syncPermissions(Permission::where('name', 'like', "%user%"));

        $administrador->assignRole('administrador');
        $user->assignRole('user');*/
    }
}
