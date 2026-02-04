<?php

namespace Database\Seeders;

use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class UserSeeder extends Seeder
{
    public function run(): void
    {
        // Paciente
        User::updateOrCreate(
            ['email' => 'paciente@simify.com'],
            [
                'name' => 'Paciente',
                'password' => Hash::make('password'),
                'id_number' => '1111111111',
                'phone' => '1111111111',
            ]
        )->assignRole('Paciente');

        // Doctor
        User::updateOrCreate(
            ['email' => 'doctor@simify.com'],
            [
                'name' => 'Doctor',
                'password' => Hash::make('password'),
                'id_number' => '2222222222',
                'phone' => '2222222222',
            ]
        )->assignRole('Doctor');

        // Recepcionista
        User::updateOrCreate(
            ['email' => 'recepcionista@simify.com'],
            [
                'name' => 'Recepcionista',
                'password' => Hash::make('password'),
                'id_number' => '3333333333',
                'phone' => '3333333333',
            ]
        )->assignRole('Recepcionista');

        // Administrador
        User::updateOrCreate(
            ['email' => 'admin@simify.com'],
            [
                'name' => 'Administrador',
                'password' => Hash::make('password'),
                'id_number' => '4444444444',
                'phone' => '4444444444',
                'address' => 'Dirección principal del sistema',
            ]
        )->assignRole('Administrador');

        // Usuarios de prueba (Pacientes)
        User::factory(10)->create()->each(function ($user) {
            $user->assignRole('Paciente');
        });
    }
}
