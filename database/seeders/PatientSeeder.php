<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;

class PatientSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener usuarios con rol Paciente (RESPETA mayúsculas)
        $users = User::role('Paciente')->get();

        foreach ($users as $user) {
            Patient::updateOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_type_id' => 1,
                    'address' => 'Dirección de prueba',
                    'allergies' => 'Ninguna',
                    'chronic_conditions' => 'Ninguna',
                    'family_history' => 'Sin antecedentes',
                    'observations' => 'Paciente creado desde seeder',
                    'emergency_contact_name' => 'Contacto de emergencia',
                    'emergency_contact_phone' => '1234567890',
                    'emergency_contact_relationship' => 'Familiar',
                ]
            );
        }
    }
}
