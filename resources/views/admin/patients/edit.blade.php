<x-admin-layout title="Editar Información Médica" :breadcrumb="[
    ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
    ['name' => 'Pacientes', 'url' => route('admin.patients.index')],
    ['name' => 'Editar'],
]">

    <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
        <div class="mb-6">
            <h2 class="text-lg font-medium text-gray-900">Paciente: {{ $patient->user->name }}</h2>
            <p class="text-sm text-gray-600">Edite la información médica y de contacto adicional.</p>
        </div>

        <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <!-- Información Médica -->
                <div class="mb-4">
                    <label for="blood_type_id" class="block text-sm font-medium text-gray-700">Tipo de Sangre</label>
                    <select name="blood_type_id" id="blood_type_id" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">
                        <option value="">Seleccione...</option>
                        @foreach($bloodTypes as $bloodType)
                            <option value="{{ $bloodType->id }}" {{ old('blood_type_id', $patient->blood_type_id) == $bloodType->id ? 'selected' : '' }}>{{ $bloodType->name }}</option>
                        @endforeach
                    </select>
                    @error('blood_type_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text" name="address" id="address" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('address', $patient->address) }}">
                    @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Contacto de Emergencia -->
                <div class="mb-4">
                    <label for="emergency_contact_name" class="block text-sm font-medium text-gray-700">Nombre Contacto Emergencia</label>
                    <input type="text" name="emergency_contact_name" id="emergency_contact_name" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('emergency_contact_name', $patient->emergency_contact_name) }}">
                    @error('emergency_contact_name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="emergency_contact_phone" class="block text-sm font-medium text-gray-700">Teléfono Contacto Emergencia</label>
                    <input type="text" name="emergency_contact_phone" id="emergency_contact_phone" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('emergency_contact_phone', $patient->emergency_contact_phone) }}">
                    @error('emergency_contact_phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="emergency_contact_relationship" class="block text-sm font-medium text-gray-700">Parentesco Contacto Emergencia</label>
                    <input type="text" name="emergency_contact_relationship" id="emergency_contact_relationship" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm" value="{{ old('emergency_contact_relationship', $patient->emergency_contact_relationship) }}">
                    @error('emergency_contact_relationship')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <!-- Campos de Texto Largo -->
                <div class="col-span-1 md:col-span-2 mb-4">
                    <label for="allergies" class="block text-sm font-medium text-gray-700">Alergias</label>
                    <textarea name="allergies" id="allergies" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('allergies', $patient->allergies) }}</textarea>
                    @error('allergies')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2 mb-4">
                    <label for="chronic_conditions" class="block text-sm font-medium text-gray-700">Condiciones Crónicas</label>
                    <textarea name="chronic_conditions" id="chronic_conditions" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('chronic_conditions', $patient->chronic_conditions) }}</textarea>
                    @error('chronic_conditions')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2 mb-4">
                    <label for="family_history" class="block text-sm font-medium text-gray-700">Historial Familiar</label>
                    <textarea name="family_history" id="family_history" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('family_history', $patient->family_history) }}</textarea>
                    @error('family_history')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2 mb-4">
                    <label for="observations" class="block text-sm font-medium text-gray-700">Observaciones</label>
                    <textarea name="observations" id="observations" rows="2" class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-indigo-500 focus:border-indigo-500 sm:text-sm">{{ old('observations', $patient->observations) }}</textarea>
                    @error('observations')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 mr-2 font-bold text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                    Cancelar
                </a>
                <button type="submit" class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                    Actualizar Información Médica
                </button>
            </div>
        </form>
    </div>

</x-admin-layout>
