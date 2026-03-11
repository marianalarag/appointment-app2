<x-admin-layout
    title="Horarios de {{ $doctor->user->name }}"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores', 'href' => route('admin.doctors.index')],
        ['name' => 'Horarios de ' . $doctor->user->name]
    ]"
>
    <x-wire-card>
        <div class="mb-6">
            <div class="flex items-center">
                <img src="{{ $doctor->user->profile_photo_url }}" 
                     alt="{{ $doctor->user->name }}"
                     class="w-16 h-16 rounded-full object-cover">
                <div class="ml-4">
                    <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                        {{ $doctor->user->name }}
                    </h2>
                    <p class="text-gray-600 dark:text-gray-400">
                        Especialidad: {{ $doctor->specialty->name ?? 'Sin especialidad' }}
                    </p>
                </div>
            </div>
        </div>

        <livewire:manage-doctor-schedules :doctorId="$doctor->id" />
    </x-wire-card>

    @push('js')
        <script>
            Livewire.on('notification', (data) => {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? '¡Éxito!' : 'Error',
                    text: data.message,
                    confirmButtonColor: '#3085d6'
                });
            });
        </script>
    @endpush
</x-admin-layout>