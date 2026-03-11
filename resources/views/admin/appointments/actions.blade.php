<div class="flex items-center space-x-1">
    <a href="{{ route('admin.appointments.edit', $appointment) }}"
        class="inline-flex items-center justify-center w-8 h-8 bg-blue-600 hover:bg-blue-700 text-white rounded-lg transition-colors"
        title="Editar">
        <i class="fa-solid fa-pen-to-square text-xs"></i>
    </a>
    <a href="{{ route('admin.appointments.consultation', $appointment) }}"
        class="inline-flex items-center justify-center w-8 h-8 bg-green-600 hover:bg-green-700 text-white rounded-lg transition-colors"
        title="Atender consulta">
        <i class="fa-solid fa-stethoscope text-xs"></i>
    </a>
</div>
