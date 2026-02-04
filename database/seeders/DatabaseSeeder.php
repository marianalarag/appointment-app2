<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar a los seeders necesarios
        $this->call([
            RoleSeeder::class,
            UserSeeder::class,
            BloodTypeSeeder::class,
            PatientSeeder::class,
        ]);

        // Crear usuario administrador (si no existe)
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
    }
}
