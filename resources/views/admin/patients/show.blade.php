    <x-admin-layout title="Detalles del Paciente" :breadcrumb="[
        ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['name' => 'Pacientes', 'url' => route('admin.patients.index')],
        ['name' => $patient->user->name],
    ]">

        <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Información Personal (Usuario)</h3>
                    <dl class="text-sm text-gray-600">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Nombre:</dt>
                            <dd>{{ $patient->user->name }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Email:</dt>
                            <dd>{{ $patient->user->email }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Cédula:</dt>
                            <dd>{{ $patient->user->id_number }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Teléfono:</dt>
                            <dd>{{ $patient->user->phone }}</dd>
                        </div>
                    </dl>
                </div>
                <div>
                    <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Información Médica y Contacto</h3>
                    <dl class="text-sm text-gray-600">
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Tipo de Sangre:</dt>
                            <dd>{{ $patient->bloodType ? $patient->bloodType->name : 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Dirección:</dt>
                            <dd>{{ $patient->address ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Contacto Emergencia:</dt>
                            <dd>{{ $patient->emergency_contact_name ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Teléfono Emergencia:</dt>
                            <dd>{{ $patient->emergency_contact_phone ?? 'N/A' }}</dd>
                        </div>
                        <div class="flex justify-between py-2 border-b border-gray-100">
                            <dt class="font-medium text-gray-700">Parentesco Emergencia:</dt>
                            <dd>{{ $patient->emergency_contact_relationship ?? 'N/A' }}</dd>
                        </div>
                    </dl>
                </div>
            </div>

            <div class="mt-8">
                <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">Historial y Observaciones</h3>
                <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                    <div>
                        <h4 class="font-medium text-gray-700 mt-2">Alergias</h4>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded mt-1">{{ $patient->allergies ?? 'Ninguna registrada' }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700 mt-2">Condiciones Crónicas</h4>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded mt-1">{{ $patient->chronic_conditions ?? 'Ninguna registrada' }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700 mt-2">Historial Familiar</h4>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded mt-1">{{ $patient->family_history ?? 'Ninguno registrado' }}</p>
                    </div>
                    <div>
                        <h4 class="font-medium text-gray-700 mt-2">Observaciones</h4>
                        <p class="text-sm text-gray-600 bg-gray-50 p-3 rounded mt-1">{{ $patient->observations ?? 'Ninguna' }}</p>
                    </div>
                </div>
            </div>

            <div class="mt-6 flex justify-end">
                <a href="{{ route('admin.patients.index') }}" class="px-4 py-2 font-bold text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                    Volver
                </a>
            </div>
        </div>

    </x-admin-layout>
