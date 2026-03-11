<?php

namespace App\Livewire\Admin;

use Livewire\Component;
use App\Models\Appointment;
use App\Models\Consultation;

class ConsultationManager extends Component
{
    public Appointment $appointment;

    public string $activeTab = 'consulta';

    // Consulta tab fields
    public string $diagnosis  = '';
    public string $treatment  = '';
    public string $notes      = '';

    // Receta tab fields
    public array $medications = [];

    // Modal flags
    public bool $showHistorialModal   = false;
    public bool $showConsultasModal   = false;

    // Previous consultations for modal
    public array $previousConsultations = [];

    protected $rules = [
        'diagnosis' => 'nullable|string|max:3000',
        'treatment' => 'nullable|string|max:3000',
        'notes'     => 'nullable|string|max:3000',
    ];

    public function mount(Appointment $appointment): void
    {
        $this->appointment = $appointment->load(['patient.user', 'patient.bloodType', 'doctor.user', 'consultation']);

        if ($this->appointment->consultation) {
            $c = $this->appointment->consultation;
            $this->diagnosis  = $c->diagnosis  ?? '';
            $this->treatment  = $c->treatment  ?? '';
            $this->notes      = $c->notes      ?? '';
            $this->medications = $c->prescriptions ?? [];
        }

        if (empty($this->medications)) {
            $this->medications = [['medication' => '', 'dose' => '', 'frequency' => '']];
        }
    }

    public function switchTab(string $tab): void
    {
        $this->activeTab = $tab;
    }

    public function addMedication(): void
    {
        $this->medications[] = ['medication' => '', 'dose' => '', 'frequency' => ''];
    }

    public function removeMedication(int $index): void
    {
        array_splice($this->medications, $index, 1);
        $this->medications = array_values($this->medications);
    }

    public function openHistorialModal(): void
    {
        $this->showHistorialModal = true;
    }

    public function closeHistorialModal(): void
    {
        $this->showHistorialModal = false;
    }

    public function openConsultasModal(): void
    {
        $patientId = $this->appointment->patient_id;

        $this->previousConsultations = Consultation::with(['appointment.doctor.user', 'appointment.doctor.specialty'])
            ->whereHas('appointment', function ($q) use ($patientId) {
                $q->where('patient_id', $patientId)
                  ->where('id', '!=', $this->appointment->id)
                  ->where('status', Appointment::STATUS_COMPLETADO);
            })
            ->latest()
            ->get()
            ->map(function ($c) {
                return [
                    'id'             => $c->id,
                    'appointment_id' => $c->appointment_id,
                    'diagnosis'      => $c->diagnosis   ?? 'No registrado',
                    'treatment'      => $c->treatment   ?? 'No registrado',
                    'notes'          => $c->notes       ?? '',
                    'prescriptions'  => $c->prescriptions ?? [],
                    'doctor_name'    => $c->appointment->doctor->user->name ?? '',
                    'specialty_name' => $c->appointment->doctor->specialty->name ?? '',
                    'date'           => $c->appointment->date->format('d/m/Y'),
                    'start_time'     => substr($c->appointment->start_time, 0, 5),
                    'end_time'       => substr($c->appointment->end_time, 0, 5),
                    'reason'         => $c->appointment->reason ?? '',
                ];
            })
            ->toArray();

        $this->showConsultasModal = true;
    }

    public function closeConsultasModal(): void
    {
        $this->showConsultasModal = false;
    }

    public function save(): void
    {
        $this->validate();

        // Filter out empty medication rows
        $prescriptions = array_values(
            array_filter($this->medications, fn($m) => !empty(trim($m['medication'] ?? '')))
        );

        $isNew = ! $this->appointment->consultation()->exists();

        Consultation::updateOrCreate(
            ['appointment_id' => $this->appointment->id],
            [
                'diagnosis'     => $this->diagnosis,
                'treatment'     => $this->treatment,
                'notes'         => $this->notes,
                'prescriptions' => $prescriptions,
            ]
        );

        // Only mark as completed if not already in a terminal state
        if ($this->appointment->status === Appointment::STATUS_PROGRAMADO) {
            $this->appointment->update(['status' => Appointment::STATUS_COMPLETADO]);
        }

        $message = $isNew ? 'La consulta se ha guardado exitosamente.' : 'La consulta se ha actualizado exitosamente.';

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => '¡Consulta guardada!',
            'text'  => $message,
        ]);

        $this->redirect(route('admin.appointments.index'));
    }

    public function render()
    {
        return view('livewire.admin.consultation-manager');
    }
}
