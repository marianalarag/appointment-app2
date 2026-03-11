<x-admin-layout
    title="Consulta"
    :breadcrumbs="[
        ['name' => 'Dashboard',     'href' => route('admin.dashboard')],
        ['name' => 'Citas',         'href' => route('admin.appointments.index')],
        ['name' => 'Consulta']
    ]"
>
    <x-slot name="actions">
        <a href="{{ route('admin.appointments.index') }}"
            class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
            <i class="fa-solid fa-arrow-left mr-2"></i>Volver a Citas
        </a>
    </x-slot>

    <x-wire-card>
        <livewire:admin.consultation-manager :appointment="$appointment" />
    </x-wire-card>

    @push('js')
        <script>
            Livewire.on('notification', (data) => {
                Swal.fire({
                    icon: data.type,
                    title: data.type === 'success' ? '¡Éxito!' : 'Aviso',
                    text: data.message,
                    confirmButtonColor: '#3085d6',
                    timer: 3000,
                    timerProgressBar: true
                });
            });
        </script>
    @endpush
</x-admin-layout>
