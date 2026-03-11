<?php

namespace App\Livewire;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use App\Models\Specialty;
use Carbon\Carbon;
use Illuminate\Validation\ValidationException;

class CreateAppointment extends Component
{
    // Campos del formulario
    public $patient_id = '';
    public $specialty_id = '';
    public $appointment_date = '';
    public $appointment_time = '';
    public $selected_doctor_id = '';
    public $selected_slot = null;
    public $reason = '';          // motivo de la cita
    public $filter_hour = '';     // optional hour range filter e.g. "08"
    
    // Resultados de búsqueda
    public $available_slots = [];
    public $doctors_available = [];
    public $searchPerformed = false;
    
    // Datos para selects
    public $patients = [];
    public $specialties = [];

    protected $rules = [
        'patient_id'        => 'required|exists:patients,id',
        'selected_doctor_id'=> 'required|exists:doctors,id',
        'appointment_date'  => 'required|date|after_or_equal:today',
        'appointment_time'  => 'required|date_format:H:i',
        'reason'            => 'nullable|string|max:1000',
    ];

    protected $messages = [
        'patient_id.required'         => 'El paciente es obligatorio.',
        'selected_doctor_id.required' => 'Debes seleccionar un doctor.',
        'appointment_date.required'   => 'La fecha es obligatoria.',
        'appointment_date.after_or_equal' => 'La fecha debe ser igual o posterior a hoy.',
        'appointment_time.required'   => 'Debes seleccionar un horario disponible.',
    ];

    public function mount()
    {
        $this->loadInitialData();
    }

    public function loadInitialData()
{
    $this->patients = Patient::with('user')
        ->orderBy('id')
        ->get()
        ->map(function($patient) {
            return [
                'id' => $patient->id,
                'name' => $patient->user->name,
                'email' => $patient->user->email,
            ];
        })
        ->toArray();

    // CAMBIO AQUÍ: Cargar especialidades como objetos Eloquent, no como arrays
    $this->specialties = Specialty::orderBy('name')->get();
}

    /**
     * Buscar disponibilidad de doctores
     */
    public function searchAvailability()
    {
        $this->validate([
            'appointment_date' => 'required|date|after_or_equal:today',
            'specialty_id'     => 'nullable|exists:specialties,id',
        ]);

        $this->searchPerformed = true;
        $this->available_slots = [];
        $this->doctors_available = [];
        $this->selected_slot = null;
        $this->selected_doctor_id = '';
        $this->appointment_time = '';

        $date = Carbon::parse($this->appointment_date);
        $dayOfWeek = $date->dayOfWeek == 0 ? 7 : $date->dayOfWeek; // Convertir domingo de 0 a 7

        // Obtener doctores según especialidad
        $doctorsQuery = Doctor::with(['user', 'specialty', 'schedules' => function($query) use ($dayOfWeek) {
            $query->where('day_of_week', $dayOfWeek)
                  ->where('is_active', true);
        }]);

        if ($this->specialty_id) {
            $doctorsQuery->where('specialty_id', $this->specialty_id);
        }

        $doctors = $doctorsQuery->get();

        foreach ($doctors as $doctor) {
            if ($doctor->schedules->isEmpty()) {
                continue;
            }

            // Obtener citas existentes para este doctor en esta fecha
            $existingAppointments = Appointment::where('doctor_id', $doctor->id)
                ->where('date', $this->appointment_date)
                ->where('status', '!=', \App\Models\Appointment::STATUS_CANCELADO)
                ->get()
                ->map(function($appointment) {
                    return [
                        'start' => Carbon::parse($appointment->start_time),
                        'end' => Carbon::parse($appointment->end_time),
                    ];
                });

            $doctorSlots = [];

            foreach ($doctor->schedules as $schedule) {
                $startTime = Carbon::parse($schedule->start_time);
                $endTime = Carbon::parse($schedule->end_time);

                // Generar slots de 15 minutos
                while ($startTime < $endTime) {
                    $slotEnd = $startTime->copy()->addMinutes(15);
                    
                    // Verificar si el slot está disponible
                    $isAvailable = true;
                    foreach ($existingAppointments as $apt) {
                        if (($startTime >= $apt['start'] && $startTime < $apt['end']) ||
                            ($slotEnd > $apt['start'] && $slotEnd <= $apt['end'])) {
                            $isAvailable = false;
                            break;
                        }
                    }

                    if ($isAvailable) {
                        // Apply hour filter if set
                        $slotHour = $startTime->format('H');
                        if ($this->filter_hour === '' || $slotHour === $this->filter_hour) {
                            $doctorSlots[] = [
                                'time'         => $startTime->format('H:i'),
                                'time_display' => $startTime->format('H:i:s'),
                                'display'      => $startTime->format('H:i:s') . ' - ' . $slotEnd->format('H:i:s'),
                                'doctor_id'    => $doctor->id,
                                'doctor_name'  => $doctor->user->name,
                                'specialty'    => $doctor->specialty->name ?? 'Sin especialidad',
                            ];
                        }
                    }

                    $startTime = $slotEnd;
                }
            }

            if (!empty($doctorSlots)) {
                $this->doctors_available[] = [
                    'doctor_id' => $doctor->id,
                    'doctor_name' => $doctor->user->name,
                    'specialty' => $doctor->specialty->name ?? 'Sin especialidad',
                    'slots' => $doctorSlots,
                ];

                $this->available_slots = array_merge($this->available_slots, $doctorSlots);
            }
        }

        if (empty($this->available_slots)) {
            $this->dispatch('notification', [
                'type' => 'warning',
                'message' => 'No hay horarios disponibles para la fecha seleccionada.'
            ]);
        } else {
            // Ordenar slots por hora
            usort($this->available_slots, function($a, $b) {
                return strcmp($a['time'], $b['time']);
            });

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => 'Se encontraron ' . count($this->available_slots) . ' horarios disponibles.'
            ]);
        }
    }

    /**
     * Seleccionar un slot específico
     */
    public function selectSlot($slotIndex)
    {
        if (isset($this->available_slots[$slotIndex])) {
            $this->selected_slot = $this->available_slots[$slotIndex];
            $this->selected_doctor_id = $this->selected_slot['doctor_id'];
            $this->appointment_time = $this->selected_slot['time'];
        }
    }

    /**
     * Doctor cards filtered to those that still have visible slots
     */
    public function getFilteredDoctorsProperty(): array
    {
        if (empty($this->doctors_available)) {
            return [];
        }
        return array_values(array_filter($this->doctors_available, fn ($d) => !empty($d['slots'])));
    }

    /**
     * Hour ranges for the "Hora" optional filter select
     */
    public function getHourOptionsProperty(): array
    {
        $options = [];
        for ($h = 6; $h <= 20; $h++) {
            $start = str_pad($h, 2, '0', STR_PAD_LEFT);
            $end   = str_pad($h + 1, 2, '0', STR_PAD_LEFT);
            $options[$start] = "{$start}:00:00 - {$end}:00:00";
        }
        return $options;
    }

    /**
     * Guardar la cita
     */
    public function save()
    {
        $this->validate();

        // Verificar que el slot sigue disponible (por si alguien más lo tomó)
        $hasConflict = Appointment::where('doctor_id', $this->selected_doctor_id)
            ->where('date', $this->appointment_date)
            ->where('start_time', $this->appointment_time)
            ->where('status', '!=', \App\Models\Appointment::STATUS_CANCELADO)
            ->exists();

        if ($hasConflict) {
            throw ValidationException::withMessages([
                'appointment_time' => 'Este horario ya no está disponible. Por favor, selecciona otro.'
            ]);
        }

        // Calcular hora de fin (15 minutos después)
        $startTime = Carbon::parse($this->appointment_time);
        $endTime = $startTime->copy()->addMinutes(15);

        try {
            Appointment::create([
                'doctor_id'  => $this->selected_doctor_id,
                'patient_id' => $this->patient_id,
                'date'       => $this->appointment_date,
                'start_time' => $this->appointment_time,
                'end_time'   => $endTime->format('H:i'),
                'duration'   => 15,
                'reason'     => $this->reason,
                'status'     => \App\Models\Appointment::STATUS_PROGRAMADO,
            ]);

            $this->dispatch('notification', [
                'type' => 'success',
                'message' => 'Cita creada exitosamente.'
            ]);

            session()->flash('swal', [
                'icon'  => 'success',
                'title' => '¡Cita creada!',
                'text'  => 'La cita se ha registrado exitosamente.',
            ]);

            $this->redirect(route('admin.appointments.index'));

        } catch (\Exception $e) {
            $this->dispatch('notification', [
                'type' => 'error',
                'message' => 'Error al crear la cita: ' . $e->getMessage()
            ]);
        }
    }

    public function render()
    {
        return view('livewire.create-appointment');
    }
}