<x-admin-layout title="Pacientes | Simify" :breadcrumbs="[
    ['name'=> 'Dashboard', 'href'=> route('admin.dashboard')],
    ['name'=> 'Pacientes', 'href'=> route('admin.patients.index')],
    ['name'=> 'Editar'],
]">

    <form action="{{ route('admin.patients.update', $patient) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Encabezado --}}
        <x-wire-card class="mb-8">
            <div class="lg:flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ $patient->user->profile_photo_url }}"
                         alt="{{ $patient->user->name }}"
                         class="w-20 h-20 rounded-full object-cover object-center">
                    <div>
                        <p class="text-2xl font-bold text-gray-900 ml-4">
                            {{ $patient->user->name }}
                        </p>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.patients.index') }}">
                        Volver
                    </x-wire-button>

                    <x-wire-button type="submit">
                        <i class="fa-solid fa-check"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        {{-- Tabs --}}
        <x-wire-card>
            <x-tabs active="datos-personales">

                {{-- HEADER --}}
                <x-slot name="header">
                    <x-tabs-link tab="datos-personales">
                        <i class="fa-solid fa-user me-2"></i>
                        Datos personales
                    </x-tabs-link>

                    <x-tabs-link
                        tab="antecedentes"
                        :error="$errors->hasAny(['allergies', 'chronic_conditions', 'surgical_history', 'family_history'])"
                    >
                        <i class="fa-solid fa-file-lines me-2"></i>
                        Antecedentes
                    </x-tabs-link>

                    <x-tabs-link
                        tab="informacion-general"
                        :error="$errors->hasAny(['blood_type_id', 'observations'])"
                    >
                        <i class="fa-solid fa-info me-2"></i>
                        Información general
                    </x-tabs-link>

                    <x-tabs-link
                        tab="contacto-emergencia"
                        :error="$errors->hasAny(['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'])"
                    >
                        <i class="fa-solid fa-heart me-2"></i>
                        Contacto de emergencia
                    </x-tabs-link>
                </x-slot>

                {{-- TAB: DATOS PERSONALES --}}
                <x-tab-content tab="datos-personales">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex justify-between items-center gap-4">
                            <div class="flex gap-4">
                                <i class="fa-solid fa-user-gear text-blue-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="text-blue-800 font-bold">Edición de usuario</h3>
                                    <p class="text-sm text-blue-700 mt-1">
                                        La información de acceso debe gestionarse desde la cuenta de usuario.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('admin.users.edit', $patient->user) }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                Editar usuario
                                <i class="fa-solid fa-arrow-up-right-from-square ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <div>
                            <label class="text-gray-500 font-semibold text-sm">Teléfono</label>
                            <p>{{ $patient->user->phone ?? 'N/A' }}</p>
                        </div>

                        <div>
                            <label class="text-gray-500 font-semibold text-sm">Email</label>
                            <p>{{ $patient->user->email ?? 'N/A' }}</p>
                        </div>

                        <div class="lg:col-span-2">
                            <label class="text-gray-500 font-semibold text-sm">Dirección</label>
                            <p>{{ $patient->user->address ?? 'N/A' }}</p>
                        </div>
                    </div>
                </x-tab-content>

                {{-- TAB: ANTECEDENTES --}}
                <x-tab-content tab="antecedentes">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <x-wire-textarea name="allergies" label="Alergias" rows="4">
                            {{ old('allergies', $patient->allergies) }}
                        </x-wire-textarea>

                        <x-wire-textarea name="chronic_conditions" label="Enfermedades Crónicas" rows="4">
                            {{ old('chronic_conditions', $patient->chronic_conditions) }}
                        </x-wire-textarea>

                        <x-wire-textarea name="surgical_history" label="Antecedentes Quirúrgicos" rows="4">
                            {{ old('surgical_history', $patient->surgical_history) }}
                        </x-wire-textarea>

                        <x-wire-textarea name="family_history" label="Antecedentes Familiares" rows="4">
                            {{ old('family_history', $patient->family_history) }}
                        </x-wire-textarea>
                    </div>
                </x-tab-content>

                {{-- TAB: INFORMACIÓN GENERAL --}}
                <x-tab-content tab="informacion-general">
                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <x-wire-native-select name="blood_type_id" label="Tipo de sangre">
                            <option value="">Selecciona un tipo de sangre</option>
                            @foreach ($bloodTypes as $bloodType)
                                <option value="{{ $bloodType->id }}"
                                    @selected(old('blood_type_id', $patient->blood_type_id) == $bloodType->id)>
                                    {{ $bloodType->name }}
                                </option>
                            @endforeach
                        </x-wire-native-select>

                        <div class="lg:col-span-2">
                            <x-wire-textarea
                                name="observations"
                                label="Observaciones"
                                rows="4">
                                {{ old('observations', $patient->observations) }}
                            </x-wire-textarea>
                        </div>
                    </div>
                </x-tab-content>

                {{-- TAB: CONTACTO EMERGENCIA --}}
                <x-tab-content tab="contacto-emergencia">
                    <div class="space-y-4">
                        <x-wire-input
                            name="emergency_contact_name"
                            label="Nombre de contacto"
                            placeholder="Ej: María González"
                            :value="old('emergency_contact_name', $patient->emergency_contact_name)" />

                        <x-wire-phone
                            name="emergency_contact_phone"
                            x-init="$nextTick(() => $el.dispatchEvent(new Event('input')))"
                            label="Teléfono de contacto"
                            :value="old('emergency_contact_phone', $patient->emergency_contact_phone_formatted)"
                            mask="(###) ###-####"
                            placeholder="(999) 999-9999"
                            :error="$errors->has('emergency_contact_phone')" />

                        <x-wire-input
                            name="emergency_contact_relationship"
                            label="Relación"
                            placeholder="Ej: Esposa, Hermano, Madre"
                            :value="old('emergency_contact_relationship', $patient->emergency_contact_relationship)" />
                    </div>
                </x-tab-content>

            </x-tabs>
        </x-wire-card>
    </form>

</x-admin-layout>
