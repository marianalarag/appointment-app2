<?php

namespace Database\Seeders;

use App\Models\DoctorSchedule;
use App\Models\Doctor;
use Illuminate\Database\Seeder;

class DoctorScheduleSeeder extends Seeder
{
    /**
     * Run the database seeds.
     */
    public function run(): void
    {
        // Obtener todos los doctores
        $doctors = Doctor::all();

        foreach ($doctors as $doctor) {
            // Lunes a Viernes: 8:00 a 12:00 y 14:00 a 18:00
            for ($day = 1; $day <= 5; $day++) {
                // Mañana
                DoctorSchedule::firstOrCreate([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '08:00',
                    'end_time' => '12:00',
                    'is_active' => true,
                ]);

                // Tarde
                DoctorSchedule::firstOrCreate([
                    'doctor_id' => $doctor->id,
                    'day_of_week' => $day,
                    'start_time' => '14:00',
                    'end_time' => '18:00',
                    'is_active' => true,
                ]);
            }

            // Sábado: 9:00 a 13:00
            DoctorSchedule::firstOrCreate([
                'doctor_id' => $doctor->id,
                'day_of_week' => 6,
                'start_time' => '09:00',
                'end_time' => '13:00',
                'is_active' => true,
            ]);
        }
    }
}
