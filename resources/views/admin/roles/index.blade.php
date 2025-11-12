<x-admin-layout title="Roles | Simify" :breadcrumbs="[
    ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
    ['name' => 'Roles']
]">
    <x-slot name="actions">
        <a href="{{ route('admin.roles.create') }}"
           class="bg-blue-600 hover:bg-blue-700 text-white px-4 py-2 rounded-md inline-flex items-center text-sm font-medium transition-colors shadow-sm focus:outline-none focus:ring-2 focus:ring-blue-500 focus:ring-offset-2">
            <i class="fa-solid fa-plus w-4 h-4"></i>
            <span class="ml-1">Nuevo</span>
        </a>
    </x-slot>
    @livewire('admin.datatables.role-table')
</x-admin-layout>
