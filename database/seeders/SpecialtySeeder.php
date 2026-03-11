<?php
// database/seeders/SpecialtySeeder.php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use App\Models\Specialty;
use Illuminate\Support\Facades\DB;

class SpecialtySeeder extends Seeder
{
    public function run()
    {
        $specialties = [
            [
                'name' => 'Cardiología',
                'description' => 'Especialidad médica que se ocupa del diagnóstico y tratamiento de enfermedades del corazón y del sistema circulatorio.'
            ],
            [
                'name' => 'Pediatría',
                'description' => 'Rama de la medicina que estudia al niño y sus enfermedades.'
            ],
            [
                'name' => 'Dermatología',
                'description' => 'Especialidad médica dedicada al estudio de la piel y sus enfermedades.'
            ],
            [
                'name' => 'Ginecología',
                'description' => 'Especialidad médica que estudia el sistema reproductor femenino.'
            ],
            [
                'name' => 'Oftalmología',
                'description' => 'Especialidad médica que estudia las enfermedades del ojo y su tratamiento.'
            ],
            [
                'name' => 'Traumatología',
                'description' => 'Especialidad médica dedicada al estudio de las lesiones del aparato locomotor.'
            ],
            [
                'name' => 'Neurología',
                'description' => 'Especialidad médica que estudia el sistema nervioso.'
            ],
            [
                'name' => 'Psiquiatría',
                'description' => 'Especialidad médica dedicada al estudio de los trastornos mentales.'
            ],
            [
                'name' => 'Veterinaria',
                'description' => 'Especialidad médica dedicada al estudio de los animales.'
            ],
        ];

        foreach ($specialties as $specialty) {
            // 👇 ESTO ES LO IMPORTANTE: updateOrCreate
            Specialty::updateOrCreate(
                ['name' => $specialty['name']], // Buscar por nombre
                ['description' => $specialty['description']] // Actualizar o crear con esta descripción
            );
        }

        $this->command->info('✅ Especialidades actualizadas correctamente');
    }
}
