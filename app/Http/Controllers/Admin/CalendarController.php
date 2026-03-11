<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;

class CalendarController extends Controller
{
    public function index()
    {
        $events = Appointment::with(['patient.user', 'doctor.user'])
            ->get()
            ->map(fn ($a) => [
                'id'    => $a->id,
                'title' => $a->patient->user->name ?? 'Paciente',
                'start' => $a->date->format('Y-m-d') . 'T' . $a->start_time,
                'end'   => $a->date->format('Y-m-d') . 'T' . $a->end_time,
                'color' => match ((int) $a->status) {
                    Appointment::STATUS_COMPLETADO => '#22c55e',
                    Appointment::STATUS_CANCELADO  => '#ef4444',
                    default                        => '#3b82f6',
                },
                'extendedProps' => [
                    'doctor'          => 'Dr(a). ' . ($a->doctor->user->name ?? '—'),
                    'status'          => $a->status_label,
                    'consultationUrl' => route('admin.appointments.consultation', $a->id),
                    'editUrl'         => route('admin.appointments.edit', $a->id),
                ],
            ]);

        return view('admin.calendar.index', ['events' => $events]);
    }
}
