<x-admin-layout
    title="Pacientes | Simify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Pacientes']
    ]"
>
    <x-slot name="actions">
        <a href="{{ route('admin.patients.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center text-sm font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fa-solid fa-plus w-4 h-4"></i>
            <span class="ml-1">Nuevo Paciente</span>
        </a>
    </x-slot>

    <div class="p-6 overflow-hidden bg-white shadow-xl sm:rounded-lg">
        @livewire('admin.patient-table')
    </div>

    @push('js')
        <script>
            function handleDelete(patientId) {
                Swal.fire({
                    title: '¿Estás seguro?',
                    text: "¡No podrás revertir esto!",
                    icon: 'warning',
                    showCancelButton: true,
                    confirmButtonColor: '#3085d6',
                    cancelButtonColor: '#d33',
                    confirmButtonText: 'Sí, ¡eliminar!',
                    cancelButtonText: 'Cancelar'
                }).then((result) => {
                    if (result.isConfirmed) {
                        document.getElementById('delete-form-' + patientId).submit();
                    }
                })
            }

            @if (session()->has('swal'))
            Swal.fire({
                icon: '{{ session('swal')['icon'] }}',
                title: '{{ session('swal')['title'] }}',
                text: '{{ session('swal')['text'] }}',
            });
            @endif
        </script>
    @endpush
</x-admin-layout>
