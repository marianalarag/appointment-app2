<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\DoctorSchedule;
use Illuminate\Validation\ValidationException;

class EditAppointment extends Component
{
    public $appointment_id;
    public $doctor_id;
    public $patient_id;
    public $appointment_date;
    public $start_time;
    public $end_time;
    public $status;
    public $notes;
    public $available_slots = [];
    public $original_start_time;
    public $original_end_time;
    public $slot_duration = 15;

    protected $rules = [
        'doctor_id'        => 'required|exists:doctors,id',
        'patient_id'       => 'required|exists:patients,id',
        'appointment_date' => 'required|date|after_or_equal:today',
        'start_time'       => 'required|date_format:H:i',
        'end_time'         => 'required|date_format:H:i|after:start_time',
        'status'           => 'required|in:1,2,3',
        'notes'            => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'doctor_id.required'            => 'El doctor es obligatorio.',
        'patient_id.required'           => 'El paciente es obligatorio.',
        'appointment_date.required'     => 'La fecha es obligatoria.',
        'appointment_date.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
        'start_time.required'           => 'La hora de inicio es obligatoria.',
        'end_time.required'             => 'La hora de fin es obligatoria.',
        'end_time.after'                => 'La hora de fin debe ser posterior a la hora de inicio.',
        'status.required'               => 'El estado es obligatorio.',
    ];

    public function mount($appointmentId)
    {
        $appointment = Appointment::findOrFail($appointmentId);

        $this->appointment_id       = $appointment->id;
        $this->doctor_id            = $appointment->doctor_id;
        $this->patient_id           = $appointment->patient_id;
        $this->appointment_date     = $appointment->date->format('Y-m-d');
        $this->start_time           = substr($appointment->start_time, 0, 5);
        $this->end_time             = substr($appointment->end_time, 0, 5);
        $this->status               = $appointment->status;
        $this->notes                = $appointment->reason;
        $this->original_start_time  = $this->start_time;
        $this->original_end_time    = $this->end_time;

        $this->loadAvailableSlots();
    }

    public function updatedDoctorId()
    {
        $this->available_slots = [];
        if ($this->doctor_id && $this->appointment_date) {
            $this->loadAvailableSlots();
        }
    }

    public function updatedAppointmentDate()
    {
        $this->available_slots = [];
        if ($this->doctor_id && $this->appointment_date) {
            $this->loadAvailableSlots();
        }
    }

    public function loadAvailableSlots()
    {
        if (!$this->doctor_id || !$this->appointment_date) {
            return;
        }

        $slots = DoctorSchedule::getAvailableSlotsForDoctor(
            $this->doctor_id,
            $this->appointment_date,
            $this->slot_duration
        );

        $currentSlot = [
            'time'    => $this->original_start_time,
            'display' => $this->original_start_time . ' - ' . $this->original_end_time,
        ];

        $this->available_slots = array_values(array_unique(
            array_merge($slots, [$currentSlot]),
            SORT_REGULAR
        ));
    }

    public function updatedStartTime()
    {
        if ($this->start_time) {
            $start      = \Carbon\Carbon::createFromTimeString($this->start_time);
            $end        = $start->copy()->addMinutes($this->slot_duration);
            $this->end_time = $end->format('H:i');
        }
    }

    public function save()
    {
        $this->validate();

        $appointment = Appointment::findOrFail($this->appointment_id);

        if ($this->start_time !== $this->original_start_time ||
            $this->end_time   !== $this->original_end_time) {

            $hasConflict = Appointment::where('id', '!=', $this->appointment_id)
                ->where('doctor_id', $this->doctor_id)
                ->where('date', $this->appointment_date)
                ->where(function ($query) {
                    $query->whereRaw('TIME(start_time) < ?', [$this->end_time])
                          ->whereRaw('TIME(end_time) > ?',   [$this->start_time]);
                })
                ->where('status', '!=', Appointment::STATUS_CANCELADO)
                ->exists();

            if ($hasConflict) {
                throw ValidationException::withMessages(['start_time' => 'Ya existe una cita en este horario.']);
            }

            $patientConflict = Appointment::where('id', '!=', $this->appointment_id)
                ->where('patient_id', $this->patient_id)
                ->where('date', $this->appointment_date)
                ->where(function ($query) {
                    $query->whereRaw('TIME(start_time) < ?', [$this->end_time])
                          ->whereRaw('TIME(end_time) > ?',   [$this->start_time]);
                })
                ->where('status', '!=', Appointment::STATUS_CANCELADO)
                ->exists();

            if ($patientConflict) {
                throw ValidationException::withMessages(['start_time' => 'El paciente ya tiene otra cita en este horario.']);
            }
        }

        try {
            $appointment->update([
                'doctor_id'  => $this->doctor_id,
                'patient_id' => $this->patient_id,
                'date'       => $this->appointment_date,
                'start_time' => $this->start_time,
                'end_time'   => $this->end_time,
                'status'     => $this->status,
                'reason'     => $this->notes,
            ]);

            $this->dispatch('notification', ['type' => 'success', 'message' => 'Cita actualizada exitosamente.']);
            $this->dispatch('appointment-updated');

        } catch (\Exception $e) {
            $this->dispatch('notification', ['type' => 'error', 'message' => 'Error al actualizar la cita: ' . $e->getMessage()]);
        }
    }

    public function render()
    {
        return view('livewire.edit-appointment', [
            'doctors'  => Doctor::with('user')->get(),
            'patients' => Patient::with('user')->get(),
            'statuses' => [
                Appointment::STATUS_PROGRAMADO => 'Programado',
                Appointment::STATUS_COMPLETADO => 'Completado',
                Appointment::STATUS_CANCELADO  => 'Cancelado',
            ],
        ]);
    }
}
