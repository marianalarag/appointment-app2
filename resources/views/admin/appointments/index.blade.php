<x-admin-layout
    title="Citas Médicas"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Citas Médicas']
    ]"
>
    <x-slot name="actions">
        <a href="{{ route('admin.appointments.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center text-sm font-medium transition-colors shadow-sm">
            <i class="fa-solid fa-plus w-4 h-4"></i>
            <span class="ml-1">+ Nuevo</span>
        </a>
    </x-slot>

    <x-wire-card>
        @livewire('admin.appointment-table')
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