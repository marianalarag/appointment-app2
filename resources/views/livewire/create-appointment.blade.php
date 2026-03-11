<div class="space-y-6">

    {{-- ═══════════════════════════════════════════════════════
         SEARCH BAR
    ═══════════════════════════════════════════════════════ --}}
    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-6 shadow-sm">
        <h2 class="text-lg font-bold text-gray-900 dark:text-gray-100 mb-1">Buscar disponibilidad</h2>
        <p class="text-sm text-gray-500 dark:text-gray-400 mb-5">Encuentra el horario perfecto para tu cita.</p>

        <div class="grid grid-cols-1 sm:grid-cols-2 lg:grid-cols-4 gap-4">
            {{-- Fecha --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1.5">Fecha</label>
                <input type="date"
                    wire:model.live="appointment_date"
                    min="{{ now()->format('Y-m-d') }}"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm">
                @error('appointment_date')
                    <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Hora (optional hour range filter) --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1.5">Hora</label>
                <select wire:model="filter_hour"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="">Cualquier hora</option>
                    @foreach($this->hourOptions as $val => $label)
                        <option value="{{ $val }}">{{ $label }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Especialidad --}}
            <div>
                <label class="block text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1.5">Especialidad (opcional)</label>
                <select wire:model="specialty_id"
                    class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm">
                    <option value="">Todas las especialidades</option>
                    @foreach($specialties as $specialty)
                        <option value="{{ $specialty->id }}">{{ $specialty->name }}</option>
                    @endforeach
                </select>
            </div>

            {{-- Buscar --}}
            <div class="flex items-end">
                <button type="button"
                    wire:click="searchAvailability"
                    wire:loading.attr="disabled"
                    class="w-full px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center gap-2">
                    <span wire:loading.remove wire:target="searchAvailability">
                        <i class="fa-solid fa-magnifying-glass mr-1"></i>Buscar disponibilidad
                    </span>
                    <span wire:loading wire:target="searchAvailability">
                        <i class="fa-solid fa-spinner fa-spin mr-1"></i>Buscando...
                    </span>
                </button>
            </div>
        </div>
    </div>

    {{-- ═══════════════════════════════════════════════════════
         RESULTS — 2-column layout
    ═══════════════════════════════════════════════════════ --}}
    @if($searchPerformed)
        <div class="grid grid-cols-1 lg:grid-cols-3 gap-6">

            {{-- ── Left: Doctor cards ── --}}
            <div class="lg:col-span-2 space-y-4">
                @forelse($this->filteredDoctors as $doctor)
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-5 shadow-sm">
                        <div class="flex items-center gap-3 mb-4">
                            <div class="flex-shrink-0 w-10 h-10 rounded-full bg-blue-100 dark:bg-blue-900 flex items-center justify-center text-blue-700 dark:text-blue-200 font-bold text-sm">
                                {{ strtoupper(substr(explode(' ', $doctor['doctor_name'])[0], 0, 1)) }}{{ strtoupper(substr(explode(' ', $doctor['doctor_name'])[1] ?? '', 0, 1)) }}
                            </div>
                            <div>
                                <p class="font-semibold text-gray-900 dark:text-gray-100 text-sm">{{ $doctor['doctor_name'] }}</p>
                                <p class="text-xs text-blue-600 dark:text-blue-400">{{ $doctor['specialty'] }}</p>
                            </div>
                        </div>

                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-2">Horarios disponibles:</p>
                        <div class="flex flex-wrap gap-2">
                            @foreach($doctor['slots'] as $slot)
                                @php $slotIndex = array_search($slot, $available_slots); @endphp
                                <button type="button"
                                    wire:click="selectSlot({{ $slotIndex !== false ? $slotIndex : 0 }})"
                                    class="px-3 py-1.5 rounded-lg text-sm font-medium border-2 transition-colors
                                        {{ ($selected_slot && $selected_slot['time'] === $slot['time'] && $selected_slot['doctor_id'] === $doctor['doctor_id'])
                                            ? 'border-blue-600 bg-blue-600 text-white'
                                            : 'border-blue-200 dark:border-blue-700 text-blue-700 dark:text-blue-300 hover:bg-blue-50 dark:hover:bg-blue-900/30' }}">
                                    {{ $slot['time_display'] }}
                                </button>
                            @endforeach
                        </div>
                    </div>
                @empty
                    <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 p-10 text-center shadow-sm">
                        <i class="fa-regular fa-calendar-xmark text-4xl text-gray-300 dark:text-gray-600 mb-3"></i>
                        <p class="text-gray-500 dark:text-gray-400 font-medium">No hay horarios disponibles</p>
                        <p class="text-sm text-gray-400 dark:text-gray-500 mt-1">Intenta con otra fecha, hora o especialidad.</p>
                    </div>
                @endforelse
            </div>

            {{-- ── Right: Summary + Form ── --}}
            <div class="lg:col-span-1">
                <div class="bg-white dark:bg-gray-800 rounded-xl border border-gray-200 dark:border-gray-700 shadow-sm sticky top-6">

                    {{-- Resumen --}}
                    @if($selected_slot)
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-3">Resumen de la cita</h3>
                            <div class="space-y-2 text-sm">
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Doctor:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100 text-right ml-2 truncate">{{ $selected_slot['doctor_name'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Fecha:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $appointment_date }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Horario:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">{{ $selected_slot['display'] }}</span>
                                </div>
                                <div class="flex justify-between">
                                    <span class="text-gray-500">Duración:</span>
                                    <span class="font-medium text-gray-900 dark:text-gray-100">15 minutos</span>
                                </div>
                            </div>
                        </div>
                    @else
                        <div class="p-5 border-b border-gray-100 dark:border-gray-700">
                            <h3 class="text-sm font-bold text-gray-900 dark:text-gray-100 mb-2">Resumen de la cita</h3>
                            <p class="text-xs text-gray-400 dark:text-gray-500">Selecciona un horario disponible para ver el resumen.</p>
                        </div>
                    @endif

                    <div class="p-5 space-y-4">
                        {{-- Paciente --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Paciente</label>
                            <select wire:model="patient_id"
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm">
                                <option value="">-- Seleccionar paciente --</option>
                                @foreach($patients as $patient)
                                    <option value="{{ $patient['id'] }}">{{ $patient['name'] }}</option>
                                @endforeach
                            </select>
                            @error('patient_id')
                                <p class="mt-1 text-xs text-red-600">{{ $message }}</p>
                            @enderror
                        </div>

                        {{-- Motivo --}}
                        <div>
                            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-1.5">Motivo de la cita</label>
                            <textarea wire:model="reason" rows="3"
                                placeholder="Describe brevemente el motivo de la consulta..."
                                class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm resize-none"></textarea>
                        </div>

                        {{-- Submit --}}
                        <button type="button"
                            wire:click="save"
                            wire:loading.attr="disabled"
                            @if(!$selected_slot || !$patient_id) disabled @endif
                            class="w-full px-4 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-300 dark:disabled:bg-blue-800/50 text-white text-sm font-medium rounded-lg transition-colors flex items-center justify-center">
                            <span wire:loading.remove wire:target="save">Confirmar cita</span>
                            <span wire:loading wire:target="save">
                                <i class="fa-solid fa-spinner fa-spin mr-1"></i>Guardando...
                            </span>
                        </button>

                        @error('appointment_time')
                            <p class="text-xs text-red-600">{{ $message }}</p>
                        @enderror
                    </div>
                </div>
            </div>

        </div>
    @endif

</div>