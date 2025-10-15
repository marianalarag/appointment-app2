<x-admin-layout :breadcrumbs="[
    [
        'name' => 'Dashboard',
        'href' => route('admin.dashboard'),
        'active' => request()->routeIs('admin.dashboard'),
    ],
    [
        'name' => 'Profile',
        'href' => route('profile.show'),
        'active' => request()->routeIs('profile.show'),
    ],
]">
    Hola desde admin
</x-admin-layout>


