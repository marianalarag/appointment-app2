<x-admin-layout
    title="Calendario | Simify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Calendario']
    ]"
>
    {{-- Leyenda de colores --}}
    <div class="flex flex-wrap items-center gap-4 mb-4 text-sm text-gray-600">
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-blue-500 inline-block"></span> Programado
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-green-500 inline-block"></span> Completado
        </span>
        <span class="flex items-center gap-2">
            <span class="w-3 h-3 rounded-full bg-red-500 inline-block"></span> Cancelado
        </span>
    </div>

    <x-wire-card>
        <div id="calendar"></div>
    </x-wire-card>

    @push('scripts')
        <script src="https://cdn.jsdelivr.net/npm/fullcalendar@6.1.15/index.global.min.js"></script>
        <script>
            document.addEventListener('DOMContentLoaded', function () {
                const calendarEl = document.getElementById('calendar');

                const calendar = new FullCalendar.Calendar(calendarEl, {
                    initialView: 'dayGridMonth',
                    locale: 'es',
                    headerToolbar: {
                        left:   'prev,next today',
                        center: 'title',
                        right:  'dayGridMonth,timeGridWeek,timeGridDay,listWeek',
                    },
                    buttonText: {
                        today: 'Hoy',
                        month: 'Mes',
                        week:  'Semana',
                        day:   'Día',
                        list:  'Lista',
                    },
                    events: @json($events),
                    height: 'auto',
                    eventClick: function (info) {
                        info.jsEvent.preventDefault();
                        const props = info.event.extendedProps;
                        const startTime = info.event.start
                            ? info.event.start.toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' })
                            : '—';
                        const endTime = info.event.end
                            ? info.event.end.toLocaleTimeString('es', { hour: '2-digit', minute: '2-digit' })
                            : '—';

                        Swal.fire({
                            title: info.event.title,
                            html: `<div class="text-left text-sm space-y-2 mt-2">
                                <p><span class="font-medium text-gray-500">Doctor:</span> ${props.doctor}</p>
                                <p><span class="font-medium text-gray-500">Estado:</span> ${props.status}</p>
                                <p><span class="font-medium text-gray-500">Horario:</span> ${startTime} – ${endTime}</p>
                            </div>`,
                            showCancelButton: true,
                            confirmButtonText: '<i class="fa-solid fa-stethoscope"></i>&nbsp;Consulta',
                            cancelButtonText:  '<i class="fa-solid fa-pen-to-square"></i>&nbsp;Editar',
                            confirmButtonColor: '#3085d6',
                            cancelButtonColor:  '#6366f1',
                            reverseButtons: true,
                        }).then(function (result) {
                            if (result.isConfirmed) {
                                window.location.href = props.consultationUrl;
                            } else if (result.dismiss === Swal.DismissReason.cancel) {
                                window.location.href = props.editUrl;
                            }
                        });
                    },
                });

                calendar.render();
            });
        </script>
    @endpush
</x-admin-layout>
