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
        <div class="overflow-x-auto">
            <table class="min-w-full divide-y divide-gray-200">
                <thead class="bg-gray-50">
                <tr>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Doctor</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Email</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Especialidad</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Cédula</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Biografía</th>
                    <th class="px-6 py-3 text-left text-xs font-medium text-gray-500 uppercase tracking-wider">Acciones</th>
                </tr>
                </thead>
                <tbody class="bg-white divide-y divide-gray-200">
                @foreach($doctors as $doctor)
                    <tr>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <div class="flex items-center">
                                <div class="flex-shrink-0 h-10 w-10">
                                    <img class="h-10 w-10 rounded-full object-cover"
                                         src="{{ $doctor->user->profile_photo_url }}"
                                         alt="{{ $doctor->user->name }}">
                                </div>
                                <div class="ml-4">
                                    <div class="text-sm font-medium text-gray-900">
                                        {{ $doctor->user->name }}
                                    </div>
                                    <div class="text-sm text-gray-500">
                                        {{ $doctor->user->phone ?? 'N/A' }}
                                    </div>
                                </div>
                            </div>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            {{ $doctor->user->email }}
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap">
                            <span class="px-2 inline-flex text-xs leading-5 font-semibold rounded-full bg-blue-100 text-blue-800">
                                {{ $doctor->specialty->name }}
                            </span>
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm text-gray-500">
                            @if($doctor->license_number)
                                {{ $doctor->license_number }}
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 text-sm text-gray-500 max-w-xs truncate">
                            @if($doctor->biography)
                                {{ Str::limit($doctor->biography, 50) }}
                            @else
                                <span class="text-gray-400">N/A</span>
                            @endif
                        </td>
                        <td class="px-6 py-4 whitespace-nowrap text-sm font-medium">
                            <div class="flex items-center space-x-2">
                                {{-- Botón Editar --}}
                                <x-wire-button href="{{ route('admin.doctors.edit', $doctor) }}" blue xs>
                                    <i class="fa-solid fa-pen-to-square"></i>
                                </x-wire-button>

                                {{-- Botón Ver Detalles --}}
                                <x-wire-button href="{{ route('admin.doctors.show', $doctor) }}" green xs>
                                    <i class="fa-solid fa-eye"></i>
                                </x-wire-button>

                                {{-- Formulario para Eliminar SIMPLE --}}
                                <form action="{{ route('admin.doctors.destroy', $doctor) }}"
                                      method="POST"
                                      class="delete-form"
                                      >
                                    @csrf
                                    @method('DELETE')
                                    <x-wire-button type="submit" red xs>
                                        <i class="fa-solid fa-trash"></i>
                                    </x-wire-button>
                                </form>
                            </div>
                        </td>
                    </tr>
                @endforeach
                </tbody>
            </table>
        </div>
    </x-wire-card>
</x-admin-layout>
