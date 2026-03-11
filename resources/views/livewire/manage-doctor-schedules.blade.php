<div class="space-y-4">

    {{-- ── Header ── --}}
    <div class="flex items-center justify-between">
        <h3 class="text-xl font-bold text-gray-900 dark:text-gray-100">Gestor de horarios</h3>
        <button wire:click="saveSchedule" wire:loading.attr="disabled"
            class="inline-flex items-center px-4 py-2 bg-blue-600 hover:bg-blue-700 disabled:bg-blue-400
                   text-white text-sm font-medium rounded-lg transition-colors shadow-sm">
            <span wire:loading.remove wire:target="saveSchedule">
                <i class="fa-solid fa-floppy-disk mr-2"></i>Guardar horario
            </span>
            <span wire:loading wire:target="saveSchedule">
                <i class="fa-solid fa-spinner fa-spin mr-2"></i>Guardando...
            </span>
        </button>
    </div>

    {{-- ── Grid ── --}}
    <div class="overflow-x-auto rounded-lg border border-gray-200 dark:border-gray-700">
        <table class="min-w-full border-collapse text-sm">

            {{-- Header row: DÍA/HORA + day names --}}
            <thead>
                <tr class="bg-gray-50 dark:bg-gray-700">
                    <th class="w-36 px-4 py-3 text-left text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-r border-gray-200 dark:border-gray-600 sticky left-0 bg-gray-50 dark:bg-gray-700 z-10">
                        DÍA/HORA
                    </th>
                    @foreach($days as $dayNum => $dayName)
                        <th class="px-3 py-3 text-center text-xs font-semibold text-gray-500 dark:text-gray-400 uppercase tracking-wider border-b border-r border-gray-200 dark:border-gray-600 min-w-[130px]">
                            {{ strtoupper($dayName) }}
                        </th>
                    @endforeach
                </tr>
            </thead>

            <tbody class="bg-white dark:bg-gray-800">

                @foreach($hours as $hour)
                    {{-- ── Hour header row: "08:00:00" + Todos per day ── --}}
                    <tr class="border-t-2 border-gray-300 dark:border-gray-500 bg-gray-50 dark:bg-gray-700/50">
                        <td class="px-4 py-2 border-r border-gray-200 dark:border-gray-600 sticky left-0 bg-gray-50 dark:bg-gray-700/50 z-10">
                            <label class="flex items-center gap-2 cursor-pointer select-none">
                                <input type="checkbox"
                                    wire:click="toggleAllForHour('{{ $hour }}')"
                                    {{ $todosChecked['all'][$hour] ? 'checked' : '' }}
                                    class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                <span class="font-mono text-xs font-semibold text-gray-700 dark:text-gray-300">
                                    {{ $hour }}:00:00
                                </span>
                            </label>
                        </td>
                        @foreach(array_keys($days) as $dayNum)
                            <td class="px-3 py-2 text-center border-r border-gray-200 dark:border-gray-600">
                                <label class="inline-flex items-center gap-1 cursor-pointer select-none">
                                    <input type="checkbox"
                                        wire:click="toggleAllForHourDay({{ $dayNum }}, '{{ $hour }}')"
                                        {{ $todosChecked[$dayNum][$hour] ? 'checked' : '' }}
                                        class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                    <span class="text-xs text-gray-500 dark:text-gray-400">Todos</span>
                                </label>
                            </td>
                        @endforeach
                    </tr>

                    {{-- ── 4 slot rows per hour ── --}}
                    @foreach($this->getSlotsForHour($hour) as $slotKey)
                        <tr class="border-t border-gray-100 dark:border-gray-700/50 hover:bg-blue-50/30 dark:hover:bg-blue-900/10 transition-colors">
                            <td class="px-4 py-1.5 border-r border-gray-200 dark:border-gray-600 sticky left-0 bg-white dark:bg-gray-800 z-10"></td>
                            @foreach(array_keys($days) as $dayNum)
                                <td class="px-3 py-1.5 text-center border-r border-gray-200 dark:border-gray-600">
                                    <label class="inline-flex items-center gap-1.5 cursor-pointer select-none">
                                        <input type="checkbox"
                                            wire:model="grid.{{ $dayNum }}.{{ $slotKey }}"
                                            class="h-4 w-4 rounded border-gray-300 text-blue-600 focus:ring-blue-500 cursor-pointer">
                                        <span class="text-xs text-gray-600 dark:text-gray-400 whitespace-nowrap font-mono">
                                            {{ $this->slotLabel($slotKey) }}
                                        </span>
                                    </label>
                                </td>
                            @endforeach
                        </tr>
                    @endforeach

                @endforeach

            </tbody>
        </table>
    </div>

</div>
