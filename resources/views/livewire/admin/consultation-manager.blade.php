<div class="space-y-6">

    {{-- ===================== HEADER ===================== --}}
    <div class="flex flex-col sm:flex-row sm:items-center sm:justify-between gap-4">
        <div>
            <h2 class="text-2xl font-bold text-gray-900 dark:text-gray-100">
                {{ $appointment->patient->user->name }}
            </h2>
            <p class="text-sm text-gray-500 dark:text-gray-400">
                DNI: {{ $appointment->patient->user->id_number ?? 'N/A' }}
            </p>
        </div>
        <div class="flex gap-2">
            <button wire:click="openHistorialModal"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-file-medical mr-2"></i>Ver Historia
            </button>
            <button wire:click="openConsultasModal"
                class="inline-flex items-center px-4 py-2 border border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-700 dark:text-gray-300 bg-white dark:bg-gray-800 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                <i class="fa-solid fa-clock-rotate-left mr-2"></i>Consultas Anteriores
            </button>
        </div>
    </div>

    {{-- ===================== TABS ===================== --}}
    <div class="border-b border-gray-200 dark:border-gray-700">
        <nav class="-mb-px flex space-x-8">
            <button wire:click="switchTab('consulta')"
                class="py-3 px-1 border-b-2 font-medium text-sm transition-colors
                    {{ $activeTab === 'consulta'
                        ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400'
                        : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fa-solid fa-stethoscope mr-2"></i>Consulta
            </button>
            <button wire:click="switchTab('receta')"
                class="py-3 px-1 border-b-2 font-medium text-sm transition-colors
                    {{ $activeTab === 'receta'
                        ? 'border-blue-600 text-blue-600 dark:text-blue-400 dark:border-blue-400'
                        : 'border-transparent text-gray-500 dark:text-gray-400 hover:text-gray-700 hover:border-gray-300' }}">
                <i class="fa-solid fa-prescription-bottle-medical mr-2"></i>Receta
            </button>
        </nav>
    </div>

    {{-- ===================== TAB: CONSULTA ===================== --}}
    @if($activeTab === 'consulta')
        <div class="space-y-5">
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Diagnóstico</label>
                <textarea wire:model="diagnosis" rows="4"
                    placeholder="Describa el diagnóstico del paciente aquí..."
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 resize-none"></textarea>
                @error('diagnosis')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Tratamiento</label>
                <textarea wire:model="treatment" rows="4"
                    placeholder="Describa el tratamiento recomendado aquí..."
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"></textarea>
                @error('treatment')
                    <p class="mt-1 text-sm text-red-600">{{ $message }}</p>
                @enderror
            </div>
            <div>
                <label class="block text-sm font-medium text-gray-700 dark:text-gray-300 mb-2">Notas</label>
                <textarea wire:model="notes" rows="4"
                    placeholder="Agregue notas adicionales sobre la consulta..."
                    class="w-full px-4 py-3 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500"></textarea>
            </div>
        </div>
    @endif

    {{-- ===================== TAB: RECETA ===================== --}}
    @if($activeTab === 'receta')
        <div class="space-y-4">
            {{-- Header row --}}
            <div class="hidden md:grid md:grid-cols-12 gap-3 px-4 py-2 bg-gray-50 dark:bg-gray-700 rounded-lg">
                <div class="col-span-5 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Medicamento</div>
                <div class="col-span-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Dosis</div>
                <div class="col-span-3 text-xs font-medium text-gray-500 dark:text-gray-400 uppercase">Frecuencia / Duración</div>
                <div class="col-span-1"></div>
            </div>

            @foreach($medications as $index => $med)
                <div class="grid grid-cols-12 gap-3 items-center border border-gray-200 dark:border-gray-700 rounded-lg p-3 bg-white dark:bg-gray-800"
                     wire:key="med-{{ $index }}">
                    <div class="col-span-12 md:col-span-5">
                        <input type="text"
                            wire:model="medications.{{ $index }}.medication"
                            placeholder="Ej. Amoxicilina 500mg"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="col-span-12 md:col-span-3">
                        <input type="text"
                            wire:model="medications.{{ $index }}.dose"
                            placeholder="Ej. 1 cada 8 horas"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="col-span-10 md:col-span-3">
                        <input type="text"
                            wire:model="medications.{{ $index }}.frequency"
                            placeholder="Ej. cada 8 horas por 7 días"
                            class="w-full px-3 py-2 border border-gray-300 dark:border-gray-600 rounded-lg bg-white dark:bg-gray-700 text-gray-900 dark:text-gray-100 focus:ring-2 focus:ring-blue-500 text-sm">
                    </div>
                    <div class="col-span-2 md:col-span-1 flex justify-end">
                        @if(count($medications) > 1)
                            <button type="button" wire:click="removeMedication({{ $index }})"
                                class="p-2 text-red-500 hover:text-red-700 hover:bg-red-50 dark:hover:bg-red-900/30 rounded-lg transition" title="Eliminar">
                                <i class="fa-solid fa-trash text-sm"></i>
                            </button>
                        @endif
                    </div>
                </div>
            @endforeach

            <button type="button" wire:click="addMedication"
                class="inline-flex items-center px-4 py-2 border-2 border-dashed border-gray-300 dark:border-gray-600 rounded-lg text-sm font-medium text-gray-500 dark:text-gray-400 hover:border-blue-400 hover:text-blue-600 dark:hover:text-blue-400 transition-colors w-full justify-center">
                <i class="fa-solid fa-plus mr-2"></i>Añadir Medicamento
            </button>
        </div>
    @endif

    {{-- ===================== SAVE BUTTON ===================== --}}
    <div class="flex justify-end pt-4 border-t border-gray-200 dark:border-gray-700">
        <button wire:click="save" wire:loading.attr="disabled"
            class="inline-flex items-center px-6 py-2.5 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400 text-white font-medium rounded-lg transition-colors shadow-sm">
            <span wire:loading.remove wire:target="save">
                <i class="fa-solid fa-floppy-disk mr-2"></i>Guardar Consulta
            </span>
            <span wire:loading wire:target="save">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i>Guardando...
            </span>
        </button>
    </div>

    {{-- ===================== MODAL: HISTORIA MÉDICA ===================== --}}
    @if($showHistorialModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center" x-data>
            <div class="absolute inset-0 bg-black bg-opacity-50" wire:click="closeHistorialModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-lg mx-4 p-6 z-10">
                <div class="flex items-center justify-between mb-4">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">
                        Historia médica del paciente
                    </h3>
                    <button wire:click="closeHistorialModal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>

                <div class="grid grid-cols-2 gap-6">
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Tipo de sangre:</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $appointment->patient->bloodType?->name ?? 'No registrado' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Alergias:</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $appointment->patient->allergies ?? 'No registradas' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Enfermedades crónicas:</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $appointment->patient->chronic_conditions ?? 'No registradas' }}
                        </p>
                    </div>
                    <div>
                        <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Antecedentes quirúrgicos:</p>
                        <p class="text-sm font-semibold text-gray-900 dark:text-gray-100">
                            {{ $appointment->patient->observations ?? 'No registrados' }}
                        </p>
                    </div>
                </div>

                <div class="mt-6 pt-4 border-t border-gray-200 dark:border-gray-700 text-right">
                    <a href="{{ route('admin.patients.show', $appointment->patient_id) }}"
                        class="inline-flex items-center text-sm font-medium text-blue-600 hover:text-blue-800 dark:text-blue-400 dark:hover:text-blue-300">
                        <i class="fa-regular fa-pen-to-square mr-1"></i>Ver / Editar Historia Médica
                    </a>
                </div>
            </div>
        </div>
    @endif

    {{-- ===================== MODAL: CONSULTAS ANTERIORES ===================== --}}
    @if($showConsultasModal)
        <div class="fixed inset-0 z-50 flex items-center justify-center" x-data>
            <div class="absolute inset-0 bg-black bg-opacity-50" wire:click="closeConsultasModal"></div>
            <div class="relative bg-white dark:bg-gray-800 rounded-xl shadow-2xl w-full max-w-2xl mx-4 z-10 flex flex-col max-h-[80vh]">
                <div class="flex items-center justify-between p-6 border-b border-gray-200 dark:border-gray-700">
                    <h3 class="text-lg font-bold text-gray-900 dark:text-gray-100">Consultas Anteriores</h3>
                    <button wire:click="closeConsultasModal"
                        class="text-gray-400 hover:text-gray-600 dark:hover:text-gray-300 transition-colors">
                        <i class="fa-solid fa-xmark text-xl"></i>
                    </button>
                </div>
                <div class="overflow-y-auto p-6 space-y-4">
                    @forelse($previousConsultations as $prev)
                        <div class="border border-gray-200 dark:border-gray-700 rounded-lg p-4 bg-gray-50 dark:bg-gray-700/50">
                            <div class="flex items-start justify-between mb-3">
                                <div>
                                    <div class="flex items-center text-blue-600 dark:text-blue-400 font-medium text-sm mb-1">
                                        <i class="fa-regular fa-calendar mr-2"></i>
                                        {{ $prev['date'] }} a las {{ $prev['start_time'] }}
                                    </div>
                                    <p class="text-xs text-gray-500 dark:text-gray-400">
                                        Atendido por: Dr(a). {{ $prev['doctor_name'] }}
                                    </p>
                                </div>
                                <a href="{{ route('admin.appointments.consultation', $prev['appointment_id']) }}"
                                    class="shrink-0 text-xs px-3 py-1 border border-gray-300 dark:border-gray-600 rounded-lg text-gray-600 dark:text-gray-300 hover:bg-gray-100 dark:hover:bg-gray-600 transition-colors">
                                    Consultar Detalle
                                </a>
                            </div>
                            <div class="text-sm text-gray-700 dark:text-gray-300 space-y-1">
                                <p><span class="font-medium">Diagnóstico:</span> {{ Str::limit($prev['diagnosis'], 80) }}</p>
                                <p><span class="font-medium">Tratamiento:</span> {{ Str::limit($prev['treatment'], 120) }}</p>
                                @if(!empty($prev['notes']))
                                    <p><span class="font-medium">Notas:</span> {{ Str::limit($prev['notes'], 80) }}</p>
                                @endif
                            </div>
                        </div>
                    @empty
                        <div class="text-center py-8 text-gray-500 dark:text-gray-400">
                            <i class="fa-regular fa-folder-open text-4xl mb-3"></i>
                            <p>No hay consultas anteriores registradas para este paciente.</p>
                        </div>
                    @endforelse
                </div>
            </div>
        </div>
    @endif

</div>
