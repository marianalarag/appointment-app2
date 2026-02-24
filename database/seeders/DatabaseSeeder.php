<?php
// database/seeders/DatabaseSeeder.php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

class DatabaseSeeder extends Seeder
{
    public function run(): void
    {
        // Llamar a los seeders necesarios
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            BloodTypeSeeder::class,
            PatientSeeder::class,
            SpecialtySeeder::class, // Si existe
        ]);

        // Crear usuario administrador (FORZAR CREACIÓN)
        $admin = User::firstOrCreate(
            ['email' => 'mariana@tecsoftware.com'],
            [
                'name' => 'Mariana Lara',
                'password' => Hash::make('mariana'),
                'id_number' => '123456789',
                'phone' => '+1234567890',
                'address' => 'Dirección de prueba',
            ]
        );

        // Asignar rol Administrador
        $admin->assignRole('Administrador');

        // Crear rol de Doctor si no existe
        if (!Role::where('name', 'Doctor')->exists()) {
            Role::create(['name' => 'Doctor']);
        }
    }
}
