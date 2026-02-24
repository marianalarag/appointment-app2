<x-admin-layout
    title="Crear Doctor"
    :breadcrumb="[
        ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['name' => 'Doctores', 'url' => route('admin.doctors.index')],
        ['name' => 'Nuevo'],
    ]"
>

    <x-wire-card>
        <form action="{{ route('admin.doctors.store') }}" method="POST">
            @csrf

            <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
                {{-- Información de Usuario --}}
                <div class="mb-4">
                    <label for="name" class="block text-sm font-medium text-gray-700">Nombre completo</label>
                    <input type="text"
                           name="name"
                           id="name"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ old('name') }}"
                           required>
                    @error('name')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="email" class="block text-sm font-medium text-gray-700">Email</label>
                    <input type="email"
                           name="email"
                           id="email"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ old('email') }}"
                           required>
                    @error('email')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password" class="block text-sm font-medium text-gray-700">Contraseña</label>
                    <input type="password"
                           name="password"
                           id="password"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                    @error('password')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="password_confirmation" class="block text-sm font-medium text-gray-700">Confirmar Contraseña</label>
                    <input type="password"
                           name="password_confirmation"
                           id="password_confirmation"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           required>
                </div>

                <div class="mb-4">
                    <label for="phone" class="block text-sm font-medium text-gray-700">Teléfono</label>
                    <input type="text"
                           name="phone"
                           id="phone"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ old('phone') }}">
                    @error('phone')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="address" class="block text-sm font-medium text-gray-700">Dirección</label>
                    <input type="text"
                           name="address"
                           id="address"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ old('address') }}">
                    @error('address')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                {{-- Información Profesional del Doctor --}}
                <div class="mb-4">
                    <label for="specialty_id" class="block text-sm font-medium text-gray-700">Especialidad</label>
                    <select name="specialty_id"
                            id="specialty_id"
                            class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                            required>
                        <option value="">Seleccionar especialidad</option>
                        @foreach($specialties as $specialty)
                            <option value="{{ $specialty->id }}" {{ old('specialty_id') == $specialty->id ? 'selected' : '' }}>
                                {{ $specialty->name }}
                            </option>
                        @endforeach
                    </select>
                    @error('specialty_id')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="mb-4">
                    <label for="license_number" class="block text-sm font-medium text-gray-700">Cédula Profesional</label>
                    <input type="text"
                           name="license_number"
                           id="license_number"
                           class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                           value="{{ old('license_number') }}"
                           required>
                    @error('license_number')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>

                <div class="col-span-1 md:col-span-2 mb-4">
                    <label for="biography" class="block text-sm font-medium text-gray-700">Biografía</label>
                    <textarea name="biography"
                              id="biography"
                              rows="4"
                              class="block w-full mt-1 border-gray-300 rounded-md shadow-sm focus:ring-blue-500 focus:border-blue-500 sm:text-sm"
                              placeholder="Experiencia profesional, especializaciones, trayectoria...">{{ old('biography') }}</textarea>
                    @error('biography')
                    <p class="mt-2 text-sm text-red-600">{{ $message }}</p>
                    @enderror
                </div>
            </div>

            <div class="flex justify-end mt-6">
                <a href="{{ route('admin.doctors.index') }}"
                   class="px-4 py-2 mr-2 font-bold text-gray-700 bg-gray-200 rounded hover:bg-gray-300">
                    Cancelar
                </a>
                <button type="submit"
                        class="px-4 py-2 font-bold text-white bg-blue-500 rounded hover:bg-blue-700">
                    Guardar Doctor
                </button>
            </div>
        </form>
    </x-wire-card>

    @push('js')
        <script>
            @if (session()->has('swal'))
            Swal.fire({
                icon: '{{ session('swal')['icon'] }}',
                title: '{{ session('swal')['title'] }}',
                text: '{{ session('swal')['text'] }}',
                confirmButtonColor: '#3085d6'
            });
            @endif
        </script>
    @endpush
</x-admin-layout>
