<?php

namespace App\Livewire\Admin;

use App\Models\Appointment;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class AppointmentTable extends DataTableComponent
{
    protected $model = Appointment::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return Appointment::query()
            ->with(['patient.user', 'doctor.user', 'doctor.specialty']);
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),

            Column::make('Paciente', 'patient_id')
                ->searchable(fn ($query, $val) =>
                    $query->whereHas('patient.user', fn ($q) =>
                        $q->where('name', 'like', "%{$val}%")
                    )
                )
                ->format(fn ($value, $row) => $row->patient->user->name ?? '—'),

            Column::make('Doctor', 'doctor_id')
                ->searchable(fn ($query, $val) =>
                    $query->orWhereHas('doctor.user', fn ($q) =>
                        $q->where('name', 'like', "%{$val}%")
                    )
                )
                ->format(fn ($value, $row) => $row->doctor->user->name ?? '—'),

            Column::make('Fecha', 'date')
                ->sortable()
                ->format(fn ($value, $row) => $row->date->format('d/m/Y')),

            Column::make('Hora', 'start_time')
                ->sortable()
                ->format(fn ($value) => substr($value, 0, 5)),

            Column::make('Hora Fin', 'end_time')
                ->format(fn ($value) => substr($value, 0, 5)),

            Column::make('Estado', 'status')
                ->sortable()
                ->format(fn ($value, $row) => $row->status_label),

            Column::make('Acciones', 'id')
                ->format(fn ($v, $row, $col) =>
                    view('admin.appointments.actions', ['appointment' => $row])
                )
                ->html(),
        ];
    }
}
