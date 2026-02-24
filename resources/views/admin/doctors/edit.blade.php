<x-admin-layout
    title="Editar Doctor | Simify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Editar']
    ]"
>

    <form action="{{ route('admin.doctors.update', $doctor) }}" method="POST">
        @csrf
        @method('PUT')

        {{-- Encabezado --}}
        <x-wire-card class="mb-8">
            <div class="lg:flex justify-between items-center">
                <div class="flex items-center">
                    <img src="{{ $doctor->user->profile_photo_url }}"
                         alt="{{ $doctor->user->name }}"
                         class="w-20 h-20 rounded-full object-cover object-center">
                    <div class="ml-4">
                        <p class="text-2xl font-bold text-gray-900">
                            {{ $doctor->user->name }}
                        </p>
                        <div class="flex items-center mt-1 space-x-4">
                            <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $doctor->specialty->name }}
                            </span>
                            <span class="text-sm text-gray-500">
                                Cédula: {{ $doctor->license_number ?? 'N/A' }}
                            </span>
                        </div>
                    </div>
                </div>

                <div class="flex space-x-3 mt-6 lg:mt-0">
                    <x-wire-button outline gray href="{{ route('admin.doctors.index') }}">
                        Volver
                    </x-wire-button>

                    <x-wire-button type="submit" blue>
                        <i class="fa-solid fa-check"></i>
                        Guardar cambios
                    </x-wire-button>
                </div>
            </div>
        </x-wire-card>

        {{-- Card principal con tabs --}}
        <x-wire-card>
            <x-tabs active="informacion-profesional">

                {{-- HEADER DE TABS --}}
                <x-slot name="header">
                    <x-tabs-link tab="informacion-profesional">
                        <i class="fa-solid fa-briefcase me-2"></i>
                        Información profesional
                    </x-tabs-link>

                    <x-tabs-link tab="biografia">
                        <i class="fa-solid fa-file-lines me-2"></i>
                        Biografía
                    </x-tabs-link>

                    <x-tabs-link tab="datos-personales">
                        <i class="fa-solid fa-user me-2"></i>
                        Datos personales
                    </x-tabs-link>
                </x-slot>

                {{-- TAB: INFORMACIÓN PROFESIONAL --}}
                <x-tab-content tab="informacion-profesional">
                    <div class="bg-blue-50 border-l-4 border-blue-500 p-4 mb-6 rounded-r-lg shadow-sm">
                        <div class="flex justify-between items-center gap-4">
                            <div class="flex gap-4">
                                <i class="fa-solid fa-user-md text-blue-500 text-xl mt-1"></i>
                                <div>
                                    <h3 class="text-blue-800 font-bold">Cuenta de usuario</h3>
                                    <p class="text-sm text-blue-700 mt-1">
                                        Los datos de acceso deben gestionarse desde la cuenta de usuario.
                                    </p>
                                </div>
                            </div>

                            <a href="{{ route('admin.users.edit', $doctor->user) }}"
                               target="_blank"
                               class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 text-white font-medium rounded-lg">
                                Editar usuario
                                <i class="fa-solid fa-arrow-up-right-from-square ml-2"></i>
                            </a>
                        </div>
                    </div>

                    <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                        <x-wire-native-select name="specialty_id" label="Especialidad" required>
                            <option value="">Seleccionar especialidad</option>
                            @foreach($specialties as $specialty)
                                <option value="{{ $specialty->id }}"
                                    {{ old('specialty_id', $doctor->specialty_id) == $specialty->id ? 'selected' : '' }}>
                                    {{ $specialty->name }}
                                </option>
                            @endforeach
                        </x-wire-native-select>

                        <x-wire-input
                            name="license_number"
                            label="Cédula Profesional"
                            required
                            :value="old('license_number', $doctor->license_number)"
                            placeholder="12345678" />
                    </div>
                </x-tab-content>

                {{-- TAB: BIOGRAFÍA --}}
                <x-tab-content tab="biografia">
                    <x-wire-textarea
                        name="biography"
                        label="Biografía"
                        rows="6"
                        placeholder="Experiencia profesional, especializaciones, trayectoria...">
                        {{ old('biography', $doctor->biography) }}
                    </x-wire-textarea>
                </x-tab-content>

                {{-- TAB: DATOS PERSONALES --}}
                <x-tab-content tab="datos-personales">
                    <div class="space-y-4">
                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <x-wire-input
                                name="name"
                                label="Nombre completo"
                                required
                                :value="old('name', $doctor->user->name)" />

                            <x-wire-input
                                name="email"
                                label="Email"
                                type="email"
                                required
                                :value="old('email', $doctor->user->email)" />
                        </div>

                        <div class="grid grid-cols-1 lg:grid-cols-2 gap-4">
                            <x-wire-input
                                name="phone"
                                label="Teléfono"
                                :value="old('phone', $doctor->user->phone)"
                                placeholder="+52 999 123 4567" />

                            <x-wire-input
                                name="address"
                                label="Dirección"
                                :value="old('address', $doctor->user->address)"
                                placeholder="Dirección completa" />
                        </div>
                    </div>
                </x-tab-content>

            </x-tabs>
        </x-wire-card>
    </form>
</x-admin-layout>
