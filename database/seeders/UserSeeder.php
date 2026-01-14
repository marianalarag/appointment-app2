<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class UserSeeder extends Seeder
{
    public function run()
    {
        // Verificar si el usuario administrador ya existe
        if (!User::where('email', 'admin@simify.com')->exists()) {
            // Crear usuario administrador
            $admin = User::create([
                'name' => 'Administrador',
                'email' => 'admin@simify.com',
                'password' => Hash::make('password'),
                'id_number' => '12345678',
                'phone' => '+1234567890',
                'address' => 'Dirección principal del sistema',
            ]);

            // Asignar rol de Administrador
            $adminRole = Role::where('name', 'Administrador')->first();
            $admin->assignRole($adminRole);
        }

        // Verificar si el usuario de prueba ya existe
        if (!User::where('email', 'usuario@simify.com')->exists()) {
            // Crear usuario de prueba
            $user = User::create([
                'name' => 'Usuario Prueba',
                'email' => 'usuario@simify.com',
                'password' => Hash::make('password'),
                'id_number' => '87654321',
                'phone' => '+0987654321',
                'address' => 'Dirección de prueba',
            ]);

            // Asignar rol por defecto (Paciente)
            $userRole = Role::where('name', 'Paciente')->first();
            $user->assignRole($userRole);
        }
    }
}
