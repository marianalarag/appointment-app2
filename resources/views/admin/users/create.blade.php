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

            <div class="space-y-4">
                <div class="grid lg:grid-cols-2 gap-4">
                    <x-wire-input
                        name="name"
                        label="Nombre"
                        required
                        :value="old('name')"
                        placeholder="Nombre completo"
                        autocomplete="name"
                    />

                    <x-wire-input
                        name="email"
                        label="Email"
                        required
                        :value="old('email')"
                        placeholder="correo@ejemplo.com"
                        autocomplete="email"
                        inputmode="email"
                    />

                    <x-wire-input
                        name="password"
                        label="Contraseña"
                        type="password"
                        required
                        placeholder="Mínimo 8 caracteres"
                        autocomplete="new-password"
                        inputmode="password"
                    />

                    <x-wire-input
                        name="password_confirmation"
                        label="Confirmar contraseña"
                        type="password"
                        required
                        placeholder="Repita la contraseña"
                        autocomplete="new-password"
                        inputmode="password"
                    />

                    <x-wire-input
                        name="id_number"
                        label="Número de Identificación"
                        :value="old('id_number')"
                        placeholder="Ej. 12345678"
                        autocomplete="off"
                        inputmode="numeric"
                    />

                    <x-wire-input
                        name="phone"
                        label="Teléfono"
                        :value="old('phone')"
                        placeholder="+5219990000000"
                        autocomplete="tel"
                        inputmode="tel"
                    />
                </div>

                <x-wire-input
                    name="address"
                    label="Dirección"
                    :value="old('address')"
                    placeholder="Dirección completa"
                    autocomplete="street-address"
                />
            </div>

            <div class="space-y-1 mt-6">
                <x-wire-native-select
                    name="role"
                    label="Rol"
                    required
                >
                    <option value="">
                        Seleccionar Rol
                    </option>
                    @foreach ($roles as $role)
                        <option
                            value="{{ $role->id }}"
                            @selected(old('role') == $role->id)
                        >
                            {{ $role->name }}
                        </option>
                    @endforeach
                </x-wire-native-select>

                <p class="text-sm text-gray-500">
                    Define los permisos y accesos del usuario
                </p>

                @error('role')
                <p class="text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>

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
