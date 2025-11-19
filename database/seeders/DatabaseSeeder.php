<?php

namespace Database\Seeders;

use App\Models\User;
// use Illuminate\Database\Console\Seeds\WithoutModelEvents;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     */
    public function run(): void
    {
        // Llamar al RoleSeeder creado
        $this->call([
            RoleSeeder::class,
            UserSeeder::class, // NUEVO: Agregar UserSeeder
        ]);

        // Usuario de prueba adicional (opcional, ya que UserSeeder crea usuarios)
        User::factory()->create([
            'name' => 'Mariana Lara',
            'email' => 'mariana@tecsoftware.com',
            'password' => Hash::make('mariana'),
            'id_number' => '123456789', // NUEVO
            'phone' => '+1234567890',   // NUEVO
            'address' => 'Dirección de prueba', // NUEVO
        ]);
    }
}
