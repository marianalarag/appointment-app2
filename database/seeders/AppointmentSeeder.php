<?php

namespace Database\Seeders;

use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;
use Illuminate\Database\Seeder;

class AppointmentSeeder extends Seeder
{
    public function run(): void
    {
        $doctors  = Doctor::all();
        $patients = Patient::all();

        if ($doctors->isEmpty() || $patients->isEmpty()) {
            $this->command->info('No doctors or patients found. Skipping appointment seeding.');
            return;
        }

        $statuses = [
            Appointment::STATUS_PROGRAMADO,
            Appointment::STATUS_COMPLETADO,
            Appointment::STATUS_CANCELADO,
        ];

        $reasons = [
            'Consulta general',
            'Control de presion arterial',
            'Revision de medicamentos',
            'Chequeo de rutina',
            'Dolor abdominal',
            'Control de diabetes',
            'Revision de resultados',
            'Consulta de seguimiento',
            'Vacunacion',
            'Control post-operatorio',
        ];

        $hours = [8, 9, 10, 11, 12, 14, 15, 16, 17];

        for ($i = 0; $i < 50; $i++) {
            $doctor  = $doctors->random();
            $patient = $patients->random();

            $appointmentDate = Carbon::now()->addDays(rand(-30, 30));

            while ($appointmentDate->dayOfWeek === 0) {
                $appointmentDate->addDay();
            }

            $startHour   = $hours[array_rand($hours)];
            $startMinute = [0, 15, 30, 45][rand(0, 3)];
            $duration    = [15, 30, 30, 30, 45][rand(0, 4)];

            $startTime = sprintf('%02d:%02d', $startHour, $startMinute);
            $endTime   = Carbon::createFromTimeString($startTime)->addMinutes($duration)->format('H:i');

            Appointment::create([
                'doctor_id'  => $doctor->id,
                'patient_id' => $patient->id,
                'date'       => $appointmentDate->toDateString(),
                'start_time' => $startTime,
                'end_time'   => $endTime,
                'duration'   => $duration,
                'reason'     => $reasons[array_rand($reasons)],
                'status'     => $statuses[array_rand($statuses)],
            ]);
        }

        $this->command->info('50 appointments seeded.');
    }
}