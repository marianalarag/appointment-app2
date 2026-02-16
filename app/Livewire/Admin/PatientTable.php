<?php

namespace App\Livewire\Admin;

use App\Models\Patient;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class PatientTable extends DataTableComponent
{
    protected $model = Patient::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function columns(): array
    {
        return [
            Column::make("Id", "id")
                ->sortable(),

            Column::make("Nombre", "user.name")
                ->sortable()
                ->searchable(),

            Column::make("Email", "user.email")
                ->sortable()
                ->searchable(),

            Column::make("Cédula", "user.id_number")
                ->sortable()
                ->searchable(),

            Column::make("Teléfono Paciente", "user.phone")
                ->sortable()
                ->searchable(),

            Column::make("Contacto Emergencia", "emergency_contact_name")
                ->sortable()
                ->searchable(),

            Column::make("Teléfono Emergencia", "emergency_contact_phone")
                ->sortable()
                ->searchable(),

            Column::make("Parentesco", "emergency_contact_relationship")
                ->sortable()
                ->searchable(),

            Column::make("Acciones", "id")
                ->format(function($value, $row, $column) {
                    return view('admin.patients.actions', ['patient' => $row]);
                })
                ->html(),
        ];
    }
}
