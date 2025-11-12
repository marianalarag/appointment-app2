<x-admin-layout title="Editar Usuario | Simify" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Usuarios', 'href' => route('admin.users.index')],
    ['name' => 'Editar Usuario'],
]">
    <x-wire-card>
        <form action="{{ route('admin.users.update', $user) }}" method="POST">
            @csrf
            @method('PUT')

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4">
                <x-wire-input
                    label="Nombre"
                    name="name"
                    placeholder="Nombre completo"
                    value="{{ old('name', $user->name) }}"
                    required
                />

                <x-wire-input
                    label="Email"
                    name="email"
                    type="email"
                    placeholder="correo@ejemplo.com"
                    value="{{ old('email', $user->email) }}"
                    required
                />
            </div>

            <div class="grid grid-cols-1 md:grid-cols-2 gap-4 mt-4">
                <x-wire-input
                    label="Contraseña (dejar en blanco para no cambiar)"
                    name="password"
                    type="password"
                    placeholder="********"
                />

                <x-wire-input
                    label="Confirmar Contraseña"
                    name="password_confirmation"
                    type="password"
                    placeholder="********"
                />
            </div>

            <div class="mt-4">
                <label for="role" class="block text-sm font-medium text-gray-700 mb-2">Rol</label>
                <select name="role" id="role" class="w-full border-gray-300 rounded-md shadow-sm focus:border-blue-500 focus:ring-blue-500" required>
                    <option value="">Seleccionar Rol</option>
                    @foreach($roles as $role)
                        <option value="{{ $role->id }}" {{ (old('role', $user->roles->first()->id ?? '') == $role->id) ? 'selected' : '' }}>
                            {{ $role->name }}
                        </option>
                    @endforeach
                </select>
                @error('role')
                <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

            <div class="flex justify-end mt-6 space-x-3">
                <x-wire-button href="{{ route('admin.users.index') }}" gray>
                    Cancelar
                </x-wire-button>
                <x-wire-button type="submit" blue>
                    <i class="fa-solid fa-save mr-2"></i>
                    Actualizar Usuario
                </x-wire-button>
            </div>
        </form>
    </x-wire-card>
</x-admin-layout>
