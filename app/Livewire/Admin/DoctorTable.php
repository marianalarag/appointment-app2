<?php

namespace App\Livewire\Admin;

use App\Models\Doctor;
use Illuminate\Database\Eloquent\Builder;
use Rappasoft\LaravelLivewireTables\DataTableComponent;
use Rappasoft\LaravelLivewireTables\Views\Column;

class DoctorTable extends DataTableComponent
{
    protected $model = Doctor::class;

    public function configure(): void
    {
        $this->setPrimaryKey('id');
    }

    public function builder(): Builder
    {
        return Doctor::query()->with(['user', 'specialty']);
    }

    public function columns(): array
    {
        return [
            Column::make('Id', 'id')
                ->sortable(),

            Column::make('Nombre', 'user.name')
                ->sortable()
                ->searchable(),

            Column::make('Email', 'user.email')
                ->sortable()
                ->searchable(),

            Column::make('DNI', 'user.id_number')
                ->sortable()
                ->searchable(),

            Column::make('Teléfono', 'user.phone')
                ->sortable()
                ->searchable(),

            Column::make('Especialidad', 'specialty.name')
                ->sortable()
                ->searchable(),

            Column::make('Acciones', 'id')
                ->format(fn ($v, $row, $col) =>
                    view('admin.doctors.actions', ['doctor' => $row])
                )
                ->html(),
        ];
    }
}
