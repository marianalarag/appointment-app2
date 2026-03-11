<x-admin-layout
    title="Editar Cita #{{ $appointment->id }}"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas Médicas', 'href' => route('admin.appointments.index')],
        ['name' => 'Editar Cita #' . $appointment->id]
    ]"
>
    <x-wire-card>
        <livewire:edit-appointment :appointmentId="$appointment->id" />
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

            Livewire.on('appointment-updated', () => {
                Swal.fire({
                    icon: 'success',
                    title: '¡Cita actualizada!',
                    text: 'La cita se ha actualizado exitosamente.',
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