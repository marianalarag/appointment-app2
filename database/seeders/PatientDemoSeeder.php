<?php

namespace Database\Seeders;

use App\Models\Patient;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class PatientDemoSeeder extends Seeder
{
    public function run(): void
    {
        $patientNames = [
            'Paciente Demo 1',  'Paciente Demo 2',  'Paciente Demo 3',
            'Paciente Demo 4',  'Paciente Demo 5',  'Paciente Demo 6',
            'Paciente Demo 7',  'Paciente Demo 8',  'Paciente Demo 9',
            'Paciente Demo 10', 'Paciente Demo 11', 'Paciente Demo 12',
            'Paciente Demo 13', 'Paciente Demo 14', 'Paciente Demo 15',
            'Paciente Demo 16', 'Paciente Demo 17', 'Paciente Demo 18',
            'Paciente Demo 19', 'Paciente Demo 20',
        ];

        foreach ($patientNames as $i => $name) {
            $user = User::firstOrCreate(
                ['email' => 'paciente' . ($i + 1) . '@demo.com'],
                [
                    'name'      => $name,
                    'password'  => Hash::make('password'),
                    'id_number' => '7' . str_pad($i + 1, 7, '0', STR_PAD_LEFT),
                    'phone'     => '7000000' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                ]
            );

            $user->syncRoles(['Paciente']);

            Patient::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'blood_type_id'                  => rand(1, 8),
                    'address'                        => 'Calle Demo ' . ($i + 1),
                    'allergies'                      => $i % 3 === 0 ? 'Penicilina' : null,
                    'chronic_conditions'             => $i % 4 === 0 ? 'Hipertension' : null,
                    'family_history'                 => 'Sin antecedentes conocidos',
                    'observations'                   => null,
                    'emergency_contact_name'         => 'Contacto Demo',
                    'emergency_contact_phone'        => '8000000' . str_pad($i + 1, 2, '0', STR_PAD_LEFT),
                    'emergency_contact_relationship' => 'Familiar',
                ]
            );
        }

        $this->command->info('Demo patients seeded: ' . count($patientNames) . ' records.');
    }
}
