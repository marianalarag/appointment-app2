<div class="space-y-4" x-data="{ showFilters: false }">
    <!-- Barra de acciones rapida -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow p-4">
        <div class="flex flex-wrap items-center justify-between gap-4">
            <div class="flex flex-wrap items-center gap-4">
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-blue-500 rounded-full"></span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">Programadas: <span class="font-bold">{{ $stats['programadas'] }}</span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-green-500 rounded-full"></span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">Completadas: <span class="font-bold">{{ $stats['completadas'] }}</span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-red-500 rounded-full"></span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">Canceladas: <span class="font-bold">{{ $stats['canceladas'] }}</span></span>
                </div>
                <div class="flex items-center space-x-2">
                    <span class="w-3 h-3 bg-gray-500 rounded-full"></span>
                    <span class="text-sm text-gray-600 dark:text-gray-300">Total: <span class="font-bold">{{ $stats['total'] }}</span></span>
                </div>
            </div>
            <div class="flex items-center gap-2">
                <button @click="showFilters = !showFilters"
                    class="px-3 py-2 text-sm font-medium text-gray-700 bg-gray-100 rounded-lg hover:bg-gray-200 dark:bg-gray-700 dark:text-gray-300 dark:hover:bg-gray-600 transition-colors">
                    <i class="fa-solid fa-filter mr-1"></i>Filtros
                    <i class="fa-solid" :class="showFilters ? 'fa-chevron-up' : 'fa-chevron-down'"></i>
                </button>
                @if($search || $filter_doctor || $filter_patient || $filter_status || $filter_date_from || $filter_date_to)
                    <button wire:click="resetFilters"
                        class="px-3 py-2 text-sm font-medium text-red-600 bg-red-100 rounded-lg hover:bg-red-200 dark:bg-red-900 dark:text-red-300 dark:hover:bg-red-800 transition-colors">
                        <i class="fa-solid fa-times mr-1"></i>Limpiar filtros
                    </button>
                @endif
                <a href="{{ route('admin.appointments.create') }}"
                    class="px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition-colors inline-flex items-center">
                    <i class="fa-solid fa-plus mr-2"></i>Nueva Cita
                </a>
            </div>
        </div>
    </div>

    <!-- Filtros avanzados -->
    <div x-show="showFilters" x-transition.duration.300ms class="bg-white dark:bg-gray-800 rounded-lg shadow p-6">
        <div class="grid grid-cols-1 md:grid-cols-3 lg:grid-cols-4 gap-4">
            <div class="col-span-1 md:col-span-3 lg:col-span-2">
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Buscar</label>
                <input type="text" wire:model.live.debounce.300ms="search" placeholder="ID, paciente, doctor..."
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Doctor</label>
                <select wire:model.live="filter_doctor" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($doctors as $doctor)
                        <option value="{{ $doctor->id }}">{{ $doctor->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Paciente</label>
                <select wire:model.live="filter_patient" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($patients as $patient)
                        <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Estado</label>
                <select wire:model.live="filter_status" class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                    <option value="">Todos</option>
                    @foreach($statuses as $value => $label)
                        <option value="{{ $value }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha desde</label>
                <input type="date" wire:model.live="filter_date_from"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Fecha hasta</label>
                <input type="date" wire:model.live="filter_date_to" min="{{ $filter_date_from }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hora inicio desde</label>
                <input type="time" wire:model.live="filter_time_from"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1">Hora inicio hasta</label>
                <input type="time" wire:model.live="filter_time_to" min="{{ $filter_time_from }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            </div>
        </div>
    </div>

    <!-- Tabla de Citas -->
    <div class="bg-white dark:bg-gray-800 rounded-lg shadow overflow-hidden">
        <div class="overflow-x-auto">
            <table class="w-full">
                <thead class="bg-gray-50 dark:bg-gray-700 border-b border-gray-200 dark:border-gray-600">
                    <tr>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sort('id')">
                            <div class="flex items-center space-x-1"><span>ID</span><i class="{{ $this->getSortIcon('id') }} text-xs"></i></div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Paciente</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Doctor</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sort('date')">
                            <div class="flex items-center space-x-1"><span>Fecha</span><i class="{{ $this->getSortIcon('date') }} text-xs"></i></div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider cursor-pointer hover:bg-gray-100" wire:click="sort('start_time')">
                            <div class="flex items-center space-x-1"><span>Hora</span><i class="{{ $this->getSortIcon('start_time') }} text-xs"></i></div>
                        </th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Hora Fin</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Estado</th>
                        <th class="px-6 py-3 text-left text-xs font-medium text-gray-700 dark:text-gray-300 uppercase tracking-wider">Acciones</th>
                    </tr>
                </thead>
                <tbody class="divide-y divide-gray-200 dark:divide-gray-600">
                    @forelse($appointments as $appointment)
                        <tr class="hover:bg-gray-50 dark:hover:bg-gray-700 transition">
                            <td class="px-6 py-4 whitespace-nowrap">
                                <span class="text-sm font-semibold text-gray-900 dark:text-gray-100">{{ $appointment->id }}</span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $appointment->patient->user->name }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <p class="text-sm font-medium text-gray-900 dark:text-gray-100">{{ $appointment->doctor->user->name }}</p>
                                <p class="text-xs text-gray-500 dark:text-gray-400">{{ $appointment->doctor->specialty->name ?? '' }}</p>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                <div class="text-sm text-gray-900 dark:text-gray-100">{{ $appointment->date->format('d/m/Y') }}</div>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ substr($appointment->start_time, 0, 5) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-900 dark:text-gray-100">
                                {{ substr($appointment->end_time, 0, 5) }}
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap">
                                @php
                                    $statusClasses = [
                                        \App\Models\Appointment::STATUS_PROGRAMADO => 'bg-blue-100 text-blue-800 dark:bg-blue-900 dark:text-blue-200',
                                        \App\Models\Appointment::STATUS_COMPLETADO => 'bg-green-100 text-green-800 dark:bg-green-900 dark:text-green-200',
                                        \App\Models\Appointment::STATUS_CANCELADO  => 'bg-red-100 text-red-800 dark:bg-red-900 dark:text-red-200',
                                    ];
                                @endphp
                                <span class="inline-flex items-center px-2.5 py-0.5 rounded-full text-xs font-medium {{ $statusClasses[$appointment->status] ?? 'bg-gray-100 text-gray-800' }}">
                                    @if($appointment->status === \App\Models\Appointment::STATUS_PROGRAMADO)
                                        <i class="fa-regular fa-clock mr-1"></i>
                                    @elseif($appointment->status === \App\Models\Appointment::STATUS_COMPLETADO)
                                        <i class="fa-regular fa-circle-check mr-1"></i>
                                    @else
                                        <i class="fa-regular fa-circle-xmark mr-1"></i>
                                    @endif
                                    {{ $appointment->status_label }}
                                </span>
                            </td>
                            <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                                <div class="flex items-center space-x-2">
                                    <a href="{{ route('admin.appointments.edit', $appointment->id) }}"
                                        class="text-blue-600 hover:text-blue-900 p-1 rounded hover:bg-blue-50 transition" title="Editar cita">
                                        <i class="fa-regular fa-pen-to-square"></i>
                                    </a>
                                    @if($appointment->status === \App\Models\Appointment::STATUS_PROGRAMADO)
                                        <a href="{{ route('admin.appointments.consultation', $appointment->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition" title="Atender consulta">
                                            <i class="fa-solid fa-stethoscope"></i>
                                        </a>
                                        <button wire:click="completeAppointment({{ $appointment->id }})"
                                            onclick="return confirm('Marcar esta cita como completada?')"
                                            class="text-green-600 hover:text-green-900 p-1 rounded hover:bg-green-50 transition" title="Completar cita">
                                            <i class="fa-regular fa-check-circle"></i>
                                        </button>
                                        <button wire:click="deleteAppointment({{ $appointment->id }})"
                                            onclick="return confirm('Cancelar esta cita?')"
                                            class="text-red-600 hover:text-red-900 p-1 rounded hover:bg-red-50 transition" title="Cancelar cita">
                                            <i class="fa-solid fa-trash"></i>
                                        </button>
                                    @endif
                                    @if($appointment->status === \App\Models\Appointment::STATUS_COMPLETADO)
                                        <a href="{{ route('admin.appointments.consultation', $appointment->id) }}"
                                            class="text-indigo-600 hover:text-indigo-900 p-1 rounded hover:bg-indigo-50 transition" title="Ver consulta">
                                            <i class="fa-solid fa-stethoscope"></i>
                                        </a>
                                    @endif
                                    @if($appointment->status === \App\Models\Appointment::STATUS_CANCELADO)
                                        <span class="text-gray-400 p-1 cursor-not-allowed" title="Cita cancelada">
                                            <i class="fa-regular fa-circle-xmark"></i>
                                        </span>
                                    @endif
                                </div>
                            </td>
                        </tr>
                    @empty
                        <tr>
                            <td colspan="8" class="px-6 py-12 text-center">
                                <div class="flex flex-col items-center justify-center text-gray-500 dark:text-gray-400">
                                    <i class="fa-regular fa-calendar-xmark text-5xl mb-4"></i>
                                    <p class="text-lg font-medium">No se encontraron citas</p>
                                    <p class="text-sm mt-2">
                                        @if($search || $filter_doctor || $filter_patient || $filter_status || $filter_date_from || $filter_date_to || $filter_time_from || $filter_time_to)
                                            No hay resultados para los filtros aplicados.
                                            <button wire:click="resetFilters" class="text-blue-600 hover:text-blue-800 underline">Limpiar filtros</button>
                                        @else
                                            Comienza creando una nueva cita medica.
                                        @endif
                                    </p>
                                </div>
                            </td>
                        </tr>
                    @endforelse
                </tbody>
            </table>
        </div>
        @if($appointments->hasPages())
            <div class="bg-white dark:bg-gray-800 px-6 py-4 border-t border-gray-200 dark:border-gray-600">
                {{ $appointments->links() }}
            </div>
        @endif
    </div>

    <div class="text-sm text-gray-500 dark:text-gray-400">
        Mostrando {{ $appointments->firstItem() ?? 0 }} - {{ $appointments->lastItem() ?? 0 }} de {{ $appointments->total() }} citas
    </div>
</div>