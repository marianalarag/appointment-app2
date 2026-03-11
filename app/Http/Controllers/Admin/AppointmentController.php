<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Illuminate\Http\Request;

class AppointmentController extends Controller
{
    public function index()
    {
        return view('admin.appointments.index');
    }

    public function create()
    {
        return view('admin.appointments.create', [
            'doctors'  => Doctor::with('user', 'specialty')->get(),
            'patients' => Patient::with('user')->get(),
        ]);
    }

    public function edit($id)
    {
        $appointment = Appointment::findOrFail($id);

        return view('admin.appointments.edit', [
            'appointment' => $appointment,
            'doctors'     => Doctor::with('user', 'specialty')->get(),
            'patients'    => Patient::with('user')->get(),
        ]);
    }

    public function destroy($id)
    {
        $appointment = Appointment::findOrFail($id);
        $appointment->status = Appointment::STATUS_CANCELADO;
        $appointment->save();

        return redirect()->route('admin.appointments.index')
            ->with('success', 'Cita cancelada exitosamente.');
    }

    public function consultation(Appointment $appointment)
    {
        return view('admin.appointments.consultation', compact('appointment'));
    }
}
