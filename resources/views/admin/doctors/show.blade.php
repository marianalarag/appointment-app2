<x-admin-layout
    title="Detalles del Doctor"
    :breadcrumb="[
        ['name' => 'Dashboard', 'url' => route('admin.dashboard')],
        ['name' => 'Doctores', 'url' => route('admin.doctors.index')],
        ['name' => $doctor->user->name],
    ]"
>

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
                    </div>
                </div>
            </div>

            <div class="flex space-x-3 mt-6 lg:mt-0">
                <a href="{{ route('admin.doctors.edit', $doctor) }}"
                   class="px-4 py-2 bg-blue-600 text-white font-medium rounded-lg hover:bg-blue-700">
                    <i class="fa-solid fa-pencil mr-2"></i>
                    Editar
                </a>
                <a href="{{ route('admin.doctors.index') }}"
                   class="px-4 py-2 bg-gray-200 text-gray-700 font-medium rounded-lg hover:bg-gray-300">
                    Volver
                </a>
            </div>
        </div>
    </x-wire-card>

    <div class="grid grid-cols-1 gap-6 md:grid-cols-2">
        {{-- Información Profesional --}}
        <x-wire-card>
            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                <i class="fa-solid fa-briefcase mr-2 text-blue-600"></i>
                Información Profesional
            </h3>
            <dl class="text-sm text-gray-600">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="font-medium text-gray-700">Especialidad:</dt>
                    <dd>
                        <span class="px-2 py-1 text-xs font-semibold rounded-full bg-blue-100 text-blue-800">
                            {{ $doctor->specialty->name }}
                        </span>
                    </dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="font-medium text-gray-700">Cédula Profesional:</dt>
                    <dd>{{ $doctor->license_number ?? 'N/A' }}</dd>
                </div>
            </dl>
        </x-wire-card>

        {{-- Información Personal --}}
        <x-wire-card>
            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                <i class="fa-solid fa-user mr-2 text-green-600"></i>
                Información Personal
            </h3>
            <dl class="text-sm text-gray-600">
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="font-medium text-gray-700">Email:</dt>
                    <dd>{{ $doctor->user->email }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="font-medium text-gray-700">Teléfono:</dt>
                    <dd>{{ $doctor->user->phone ?? 'N/A' }}</dd>
                </div>
                <div class="flex justify-between py-2 border-b border-gray-100">
                    <dt class="font-medium text-gray-700">Dirección:</dt>
                    <dd>{{ $doctor->user->address ?? 'N/A' }}</dd>
                </div>
            </dl>
        </x-wire-card>

        {{-- Biografía (ocupa toda la fila) --}}
        <x-wire-card class="md:col-span-2">
            <h3 class="text-lg font-medium text-gray-900 border-b pb-2 mb-4">
                <i class="fa-solid fa-file-lines mr-2 text-purple-600"></i>
                Biografía
            </h3>
            <div class="text-sm text-gray-600 bg-gray-50 p-4 rounded-lg">
                @if($doctor->biography)
                    {{ $doctor->biography }}
                @else
                    <span class="text-gray-400">N/A</span>
                @endif
            </div>
        </x-wire-card>
    </div>

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
