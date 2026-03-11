<x-admin-layout
    title="Crear Cita"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas Médicas', 'href' => route('admin.appointments.index')],
        ['name' => 'Nueva Cita']
    ]"
>
    <x-wire-card>
        <livewire:create-appointment />
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

            Livewire.on('appointment-created', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Cita creada!',
                    text: 'La cita se ha creado exitosamente.',
                    timer: 1500,
                    timerProgressBar: true,
                    showConfirmButton: false
                }).then(() => {
                    window.location.href = '{{ route("admin.appointments.index") }}';
                });
            });
        </script>
    @endpush
</x-admin-layout>   