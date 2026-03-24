<?php

namespace App\Console\Commands;

use Illuminate\Console\Command;
use App\Models\Appointment;
use App\Models\User;
use Carbon\Carbon;
use Illuminate\Support\Facades\Mail;
use App\Mail\DoctorDailyReportMail;
use App\Mail\AdminDailyReportMail;

class SendDailyReportsCommand extends Command
{
    /**
     * The name and signature of the console command.
     *
     * @var string
     */
    protected $signature = 'reports:daily-appointments';

    /**
     * The console command description.
     *
     * @var string
     */
    protected $description = 'Sends a daily report of appointments to doctors and administrators';

    /**
     * Execute the console command.
     */
    public function handle()
    {
        $today = Carbon::today();
        
        $appointments = Appointment::with(['doctor.user', 'patient.user'])
            ->whereDate('date', $today)
            ->where('status', '!=', App\Models\Appointment::STATUS_CANCELADO)
            ->get();

        if ($appointments->isEmpty()) {
            $this->info('No appointments for today. Reports not sent.');
            return;
        }

        // Send to each doctor
        $appointmentsByDoctor = $appointments->groupBy('doctor_id');

        foreach ($appointmentsByDoctor as $doctorId => $doctorAppointments) {
            $doctor = $doctorAppointments->first()->doctor;
            if ($doctor && $doctor->user && $doctor->user->email) {
                Mail::to($doctor->user->email)->send(new DoctorDailyReportMail($doctor, $doctorAppointments));
            }
        }

        // Send to admins
        $admins = User::role('admin')->get(); // assuming 'admin' is the role name
        
        // If no user has admin role, we can optionally pick the first user or skip
        if ($admins->isEmpty()) {
            $admins = User::where('id', 1)->get(); // fallback to first user if no spatie role 'admin'
        }

        foreach ($admins as $admin) {
            if ($admin->email) {
                Mail::to($admin->email)->send(new AdminDailyReportMail($admin, $appointments));
            }
        }

        $this->info('Daily reports sent successfully.');
    }
}
