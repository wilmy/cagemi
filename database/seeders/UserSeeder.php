<?php

namespace Database\Seeders;

use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Str;
use App\Models\User;

class UserSeeder extends Seeder
{
    /**
     * Run the database seeds.
     *
     * @return void
     */
    public function run()
    {
        $user = User::create([
            'name' => 'admin',
            'email' => 'admin@admin.com',
            'super_usuario' => 'S',
            'email_verified_at' => now(),
            'estatus' => 'A',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole('admin');

        $user = User::create([
            'name' => 'Wilmy Rodriguez',
            'email' => 'wilmyrb@gmail.com',
            'email_verified_at' => now(),
            'estatus' => 'A',
            'password' => '$2y$10$92IXUNpkjO0rOQ5byMi.Ye4oKoEa3Ro9llC/.og/at2.uheWG/igi', // password
            'remember_token' => Str::random(10),
        ])->assignRole('manager');

        
        //Crea 10 usuarios aleatorios 
        /*User::factory(10)->create()->each(function($user){
            $user->assignRole('developer');
        });*/
    }
}
