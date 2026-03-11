<?php

namespace App\Livewire;

use Livewire\Component;
use Livewire\WithPagination;
use App\Models\Appointment;
use App\Models\Doctor;
use App\Models\Patient;
use Carbon\Carbon;

class AppointmentList extends Component
{
    use WithPagination;

    // Filtros
    public $search = '';
    public $filter_doctor = '';
    public $filter_patient = '';
    public $filter_status = '';
    public $filter_date_from = '';
    public $filter_date_to = '';
    public $filter_time_from = '';
    public $filter_time_to = '';

    // Ordenamiento
    public $sortField = 'date';
    public $sortDirection = 'desc';

    // Configuracion
    protected $paginationTheme = 'tailwind';

    protected $queryString = [
        'search'           => ['except' => ''],
        'filter_doctor'    => ['except' => ''],
        'filter_patient'   => ['except' => ''],
        'filter_status'    => ['except' => ''],
        'filter_date_from' => ['except' => ''],
        'filter_date_to'   => ['except' => ''],
        'filter_time_from' => ['except' => ''],
        'filter_time_to'   => ['except' => ''],
        'sortField'        => ['except' => 'date'],
        'sortDirection'    => ['except' => 'desc'],
    ];

    public function updatingSearch()        { $this->resetPage(); }
    public function updatingFilterDoctor()  { $this->resetPage(); }
    public function updatingFilterPatient() { $this->resetPage(); }
    public function updatingFilterStatus()  { $this->resetPage(); }
    public function updatingFilterDateFrom(){ $this->resetPage(); }
    public function updatingFilterDateTo()  { $this->resetPage(); }
    public function updatingFilterTimeFrom(){ $this->resetPage(); }
    public function updatingFilterTimeTo()  { $this->resetPage(); }

    public function sort($field)
    {
        if ($this->sortField === $field) {
            $this->sortDirection = $this->sortDirection === 'asc' ? 'desc' : 'asc';
        } else {
            $this->sortField = $field;
            $this->sortDirection = 'asc';
        }
    }

    public function getSortIcon($field)
    {
        if ($this->sortField !== $field) {
            return 'fa-solid fa-sort';
        }
        return $this->sortDirection === 'asc' ? 'fa-solid fa-sort-up' : 'fa-solid fa-sort-down';
    }

    public function deleteAppointment($appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);

            if ($appointment->status === Appointment::STATUS_CANCELADO) {
                $this->dispatch('notification', ['type' => 'warning', 'message' => 'Esta cita ya esta cancelada.']);
                return;
            }

            if ($appointment->status === Appointment::STATUS_COMPLETADO) {
                $this->dispatch('notification', ['type' => 'error', 'message' => 'No se puede cancelar una cita completada.']);
                return;
            }

            $appointment->status = Appointment::STATUS_CANCELADO;
            $appointment->save();

            $this->dispatch('notification', ['type' => 'success', 'message' => 'Cita cancelada exitosamente.']);
            $this->resetPage();

        } catch (\Exception $e) {
            $this->dispatch('notification', ['type' => 'error', 'message' => 'Error al cancelar la cita: ' . $e->getMessage()]);
        }
    }

    public function completeAppointment($appointmentId)
    {
        try {
            $appointment = Appointment::findOrFail($appointmentId);

            if ($appointment->status === Appointment::STATUS_COMPLETADO) {
                $this->dispatch('notification', ['type' => 'warning', 'message' => 'Esta cita ya esta completada.']);
                return;
            }

            if ($appointment->status === Appointment::STATUS_CANCELADO) {
                $this->dispatch('notification', ['type' => 'error', 'message' => 'No se puede completar una cita cancelada.']);
                return;
            }

            $appointment->status = Appointment::STATUS_COMPLETADO;
            $appointment->save();

            $this->dispatch('notification', ['type' => 'success', 'message' => 'Cita marcada como completada.']);
            $this->resetPage();

        } catch (\Exception $e) {
            $this->dispatch('notification', ['type' => 'error', 'message' => 'Error al completar la cita: ' . $e->getMessage()]);
        }
    }

    public function getStatsProperty()
    {
        return [
            'total'      => Appointment::count(),
            'programadas'=> Appointment::where('status', Appointment::STATUS_PROGRAMADO)->count(),
            'completadas'=> Appointment::where('status', Appointment::STATUS_COMPLETADO)->count(),
            'canceladas' => Appointment::where('status', Appointment::STATUS_CANCELADO)->count(),
        ];
    }

    public function getAppointmentsProperty()
    {
        return Appointment::query()
            ->with(['doctor.user', 'patient.user'])
            ->when($this->search, function ($query) {
                $query->where(function ($q) {
                    $q->whereHas('patient.user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'))
                      ->orWhereHas('doctor.user', fn($u) => $u->where('name', 'like', '%' . $this->search . '%'))
                      ->orWhere('id', 'like', '%' . $this->search . '%');
                });
            })
            ->when($this->filter_doctor,    fn($q) => $q->where('doctor_id', $this->filter_doctor))
            ->when($this->filter_patient,   fn($q) => $q->where('patient_id', $this->filter_patient))
            ->when($this->filter_status,    fn($q) => $q->where('status', $this->filter_status))
            ->when($this->filter_date_from, fn($q) => $q->whereDate('date', '>=', $this->filter_date_from))
            ->when($this->filter_date_to,   fn($q) => $q->whereDate('date', '<=', $this->filter_date_to))
            ->when($this->filter_time_from, fn($q) => $q->whereTime('start_time', '>=', $this->filter_time_from))
            ->when($this->filter_time_to,   fn($q) => $q->whereTime('start_time', '<=', $this->filter_time_to))
            ->orderBy($this->sortField, $this->sortDirection)
            ->paginate(10);
    }

    public function resetFilters()
    {
        $this->reset(['search','filter_doctor','filter_patient','filter_status','filter_date_from','filter_date_to','filter_time_from','filter_time_to']);
        $this->sortField     = 'date';
        $this->sortDirection = 'desc';
        $this->dispatch('notification', ['type' => 'info', 'message' => 'Filtros restablecidos.']);
    }

    public function render()
    {
        return view('livewire.appointment-list', [
            'appointments' => $this->appointments,
            'doctors'   => Doctor::with('user')->orderBy('id')->get(),
            'patients'  => Patient::with('user')->orderBy('id')->get(),
            'stats'     => $this->stats,
            'statuses'  => [
                Appointment::STATUS_PROGRAMADO => 'Programado',
                Appointment::STATUS_COMPLETADO => 'Completado',
                Appointment::STATUS_CANCELADO  => 'Cancelado',
            ],
        ]);
    }
}
