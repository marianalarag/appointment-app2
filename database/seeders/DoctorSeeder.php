<?php

namespace Database\Seeders;

use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\Hash;

class DoctorSeeder extends Seeder
{
    public function run(): void
    {
        $specialties = Specialty::all();

        if ($specialties->isEmpty()) {
            $this->command->warn('No specialties found. Skipping doctor seeding.');
            return;
        }

        $doctorsData = [
            ['name' => 'Doctor',                    'email' => 'doctor@simify.com', 'id_number' => '2222222222', 'phone' => '2222222222'],
            ['name' => 'Dr. Carlos Perez',         'email' => 'carlos@test.com',   'id_number' => '30000001', 'phone' => '333333333'],
            ['name' => 'Dra. Ana Gomez',            'email' => 'ana@test.com',      'id_number' => '30000002', 'phone' => '333333333'],
            ['name' => 'Dr. Luis Torres',           'email' => 'luis@test.com',     'id_number' => '30000003', 'phone' => '333333333'],
            ['name' => 'Doctor Demo 1',             'email' => 'doctor1@demo.com',  'id_number' => '50000001', 'phone' => '600000001'],
            ['name' => 'Doctor Demo 2',             'email' => 'doctor2@demo.com',  'id_number' => '50000002', 'phone' => '600000002'],
            ['name' => 'Doctor Demo 3',             'email' => 'doctor3@demo.com',  'id_number' => '50000003', 'phone' => '600000003'],
            ['name' => 'Doctor Demo 4',             'email' => 'doctor4@demo.com',  'id_number' => '50000004', 'phone' => '600000004'],
            ['name' => 'Doctor Demo 5',             'email' => 'doctor5@demo.com',  'id_number' => '50000005', 'phone' => '600000005'],
            ['name' => 'Doctor Demo 6',             'email' => 'doctor6@demo.com',  'id_number' => '50000006', 'phone' => '600000006'],
            ['name' => 'Doctor Demo 7',             'email' => 'doctor7@demo.com',  'id_number' => '50000007', 'phone' => '600000007'],
        ];

        foreach ($doctorsData as $i => $data) {
            $user = User::firstOrCreate(
                ['email' => $data['email']],
                [
                    'name'      => $data['name'],
                    'password'  => Hash::make('password'),
                    'id_number' => $data['id_number'],
                    'phone'     => $data['phone'],
                ]
            );

            $user->syncRoles(['Doctor']);

            Doctor::firstOrCreate(
                ['user_id' => $user->id],
                [
                    'specialty_id'   => $specialties->random()->id,
                    'license_number' => 'LIC-' . str_pad($i + 1, 4, '0', STR_PAD_LEFT),
                    'biography'      => 'Médico con amplia experiencia clínica.',
                ]
            );
        }

        $this->command->info('Doctors seeded: ' . count($doctorsData) . ' records.');
    }
}
