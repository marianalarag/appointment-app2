<x-admin-layout
    title="Doctores | Simify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Doctores']
    ]"
>
    <x-slot name="actions">
        <a href="{{ route('admin.doctors.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center text-sm font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fa-solid fa-plus w-4 h-4"></i>
            <span class="ml-1">Nuevo Doctor</span>
        </a>
    </x-slot>

    <x-wire-card>
        @livewire('admin.doctor-table')
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
