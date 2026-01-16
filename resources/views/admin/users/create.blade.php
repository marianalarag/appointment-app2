<x-admin-layout
    title="Crear Usuario | Simify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Usuarios', 'href' => route('admin.users.index')],
        ['name' => 'Nuevo Usuario'],
    ]"
>
    <x-wire-card>
        <form action="{{ route('admin.users.store') }}" method="POST">
            @csrf

            {{-- Nombre y Email --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-wire-input
                    label="Nombre"
                    name="name"
                    placeholder="Nombre completo"
                    value="{{ old('name') }}"
                    required
                />

                <x-wire-input
                    label="Email"
                    name="email"
                    type="email"
                    placeholder="correo@ejemplo.com"
                    value="{{ old('email') }}"
                    required
                />
            </div>

            {{-- Identificación y Teléfono --}}
            <div class="grid grid-cols-1 md:grid-cols-3 gap-4 mt-4">
                <x-wire-input
                    label="Número de Identificación"
                    name="id_number"
                    placeholder="12345678"
                    value="{{ old('id_number') }}"
                />

                <x-wire-input
                    label="Teléfono"
                    name="phone"
                    placeholder="+5219990000000"
                    value="{{ old('phone') }}"
                />
            </div>

            {{-- Dirección --}}
            <div class="mt-4">
                <x-wire-textarea
                    label="Dirección"
                    name="address"
                    placeholder="Dirección completa"
                    rows="3"
                >
                    {{ old('address') }}
                </x-wire-textarea>
            </div>

            {{-- Contraseñas --}}
            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <x-wire-input
                    label="Contraseña"
                    name="password"
                    type="password"
                    placeholder="********"
                    required
                />

                <x-wire-input
                    label="Confirmar Contraseña"
                    name="password_confirmation"
                    type="password"
                    placeholder="********"
                    required
                />
            </div>

            {{-- Texto informativo --}}
            <div class="mt-6">
                <p class="text-sm text-gray-600">
                    Define los permisos y accesos del usuario
                </p>
            </div>

            {{-- Rol --}}
            <div class="mt-2">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">
                    Rol
                </label>

                <select
                    name="role"
                    id="role"
                    class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500"
                    required
                >
                    <option value="">Seleccionar Rol</option>
                    @foreach ($roles as $role)
                        <option
                            value="{{ $role->id }}"
                            {{ old('role') == $role->id ? 'selected' : '' }}
                        >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>

                @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            {{-- Botones --}}
            <div class="flex justify-end mt-6 space-x-3">
                <x-wire-button href="{{ route('admin.users.index') }}" gray>
                    Cancelar
                </x-wire-button>

                <x-wire-button type="submit" blue>
                    <i class="fa-solid fa-save mr-2"></i>
                    Guardar Usuario
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
