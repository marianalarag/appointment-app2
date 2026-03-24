<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Services\WhatsAppService;
use Carbon\Carbon;
use Illuminate\Support\Facades\Log;

class SendWhatsAppReminders extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'appointments:send-whatsapp-reminders';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Envía recordatorios de WhatsApp para las citas del día siguiente.';

    /**
     * Execute the console command.
     */
    public function handle(WhatsAppService $whatsappService)
    {
        $tomorrow = Carbon::tomorrow()->format('Y-m-d');

        // Buscar las citas que están programadas para mañana
        $appointments = Appointment::with(['patient.user', 'doctor.user'])
            ->where('date', $tomorrow)
            ->where('status', \App\Models\Appointment::STATUS_PROGRAMADO)
            ->get();

        $count = 0;

        foreach ($appointments as $appointment) {
            $patient = $appointment->patient;
            $doctor = $appointment->doctor;

            // Aseguramos de que el paciente y su número de teléfono existan
            if ($patient && $patient->user && $patient->user->phone) {
                $phone = $patient->user->phone;
                $dateFormatted = Carbon::parse($appointment->date)->format('d/m/Y');
                $timeFormatted = Carbon::parse($appointment->start_time)->format('H:i');
                
                $message = "Recordatorio: Hola {$patient->user->name}, te recordamos que tienes una cita médica con el Dr. {$doctor->user->name} programada para mañana {$dateFormatted} a las {$timeFormatted}. Por favor, llega 10 minutos antes.";

                $sent = $whatsappService->sendMessage($phone, $message);
                
                if ($sent) {
                    $count++;
                    $this->info("Recordatorio enviado a {$phone}");
                } else {
                    $this->error("No se pudo enviar el recordatorio a {$phone}");
                }
            } else {
                $this->warn("La cita {$appointment->id} no tiene un paciente o un número de teléfono válido para enviar WhatsApp.");
            }
        }

        $this->info("Se han enviado {$count} recordatorios de WhatsApp para mañana.");
        Log::info("Job SendWhatsAppReminders completado: se enviaron {$count} mensajes.");
    }
}
