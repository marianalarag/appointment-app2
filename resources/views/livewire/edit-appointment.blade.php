<div class="max-w-2xl mx-auto bg-white dark:bg-gray-800 rounded-lg shadow p-6">
    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100 mb-6">Editar Cita</h2>

    <form wire:submit.prevent="save" class="space-y-6">
        <!-- Seleccionar Doctor -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Doctor *
            </label>
            <select wire:model.live="doctor_id" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                @foreach($doctors as $doctor)
                    <option value="{{ $doctor->id }}">{{ $doctor->user->name }} - {{ $doctor->specialty->name ?? 'Sin especialidad' }}</option>
                @endforeach
            </select>
            @error('doctor_id')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Seleccionar Paciente -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Paciente *
            </label>
            <select wire:model="patient_id" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                @foreach($patients as $patient)
                    <option value="{{ $patient->id }}">{{ $patient->user->name }}</option>
                @endforeach
            </select>
            @error('patient_id')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Seleccionar Fecha -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Fecha de la Cita *
            </label>
            <input type="date" wire:model.live="appointment_date" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
            @error('appointment_date')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Horarios Disponibles -->
        @if($available_slots)
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Hora Disponible *
                </label>
                <div class="grid grid-cols-4 gap-2">
                    @foreach($available_slots as $slot)
                        <label class="relative flex items-center p-3 border-2 border-gray-300 dark:border-gray-600 rounded-lg cursor-pointer hover:border-blue-500 transition
                            @if($start_time === $slot['time']) border-blue-500 bg-blue-50 dark:bg-blue-900 @endif"
                            wire:key="slot-{{ $slot['time'] }}">
                            <input type="radio" name="slot" value="{{ $slot['time'] }}" 
                                wire:model="start_time" class="w-4 h-4 text-blue-600">
                            <span class="ml-2 text-sm font-medium text-gray-900 dark:text-gray-100">
                                {{ $slot['display'] }}
                            </span>
                        </label>
                    @endforeach
                </div>
                @error('start_time')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        @endif

        <!-- Hora de Fin (Solo lectura) -->
        <div class="grid grid-cols-2 gap-4">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Hora de Inicio
                </label>
                <input type="time" wire:model="start_time" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                @error('start_time')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>

            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                    Hora de Fin
                </label>
                <input type="time" wire:model="end_time" 
                    class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500" disabled>
                @error('end_time')
                    <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
                @enderror
            </div>
        </div>

        <!-- Estado -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Estado *
            </label>
            <select wire:model="status" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500">
                @foreach($statuses as $value => $label)
                    <option value="{{ $value }}">{{ $label }}</option>
                @endforeach
            </select>
            @error('status')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Motivo / Notas -->
        <div>
            <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">
                Motivo de la cita
            </label>
            <textarea wire:model="notes" rows="4" 
                class="w-full px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"
                placeholder="Motivo o notas de la consulta..."></textarea>
            @error('notes')
                <p class="mt-1 text-sm text-red-600 dark:text-red-400">{{ $message }}</p>
            @enderror
        </div>

        <!-- Botones -->
        <div class="flex gap-4 pt-4">
            <button type="submit" class="flex-1 px-6 py-2 bg-blue-600 hover:bg-blue-700 text-white rounded-lg font-medium transition">
                Actualizar Cita
            </button>
            <a href="{{ route('admin.appointments.index') }}" class="flex-1 px-6 py-2 bg-gray-200 dark:bg-gray-700 hover:bg-gray-300 dark:hover:bg-gray-600 text-gray-900 dark:text-gray-100 rounded-lg font-medium text-center transition">
                Cancelar
            </a>
        </div>
    </form>
</div>
