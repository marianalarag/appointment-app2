<?php

namespace App\Observers;

use App\Models\Appointment;
use App\Mail\AppointmentCreatedMail;
use Illuminate\Support\Facades\Mail;
use Illuminate\Support\Facades\Log;

class AppointmentObserver
{
    /**
     * Handle the Appointment "created" event.
     */
    public function created(Appointment $appointment): void
    {
        try {
            $appointment->load(['patient.user', 'doctor.user', 'doctor.specialty']);
            
            $patientEmail = $appointment->patient->user->email ?? null;
            $doctorEmail = $appointment->doctor->user->email ?? null;

            if ($patientEmail || $doctorEmail) {
                $mail = new AppointmentCreatedMail($appointment);

                if ($patientEmail) {
                    Mail::to($patientEmail)->send($mail);
                }
                
                if ($doctorEmail) {
                    Mail::to($doctorEmail)->send($mail);
                }
            }
        } catch (\Exception $e) {
            Log::error('Error sending appointment email: ' . $e->getMessage());
        }
    }

    /**
     * Handle the Appointment "updated" event.
     */
    public function updated(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "deleted" event.
     */
    public function deleted(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "restored" event.
     */
    public function restored(Appointment $appointment): void
    {
        //
    }

    /**
     * Handle the Appointment "force deleted" event.
     */
    public function forceDeleted(Appointment $appointment): void
    {
        //
    }
}
