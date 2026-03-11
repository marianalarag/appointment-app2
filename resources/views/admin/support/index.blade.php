<x-admin-layout
    title="Soporte | Simify"
    :breadcrumbs="[
        ['name' => 'Dashboard', 'href' => route('admin.dashboard')],
        ['name' => 'Soporte']
    ]"
>
    <div class="grid grid-cols-1 lg:grid-cols-3 gap-6 mt-2">

        {{-- Información del sistema --}}
        <div class="lg:col-span-2 space-y-6">

            <x-wire-card>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-full bg-blue-100 flex items-center justify-center">
                        <i class="fa-solid fa-circle-question text-blue-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Centro de Ayuda</h3>
                </div>

                <div class="space-y-4">
                    <div class="border border-gray-200 dark:border-gray-700 rounded-lg divide-y divide-gray-200 dark:divide-gray-700">

                        <details class="group p-4">
                            <summary class="flex items-center justify-between cursor-pointer text-sm font-medium text-gray-800 dark:text-gray-200 list-none">
                                ¿Cómo crear una cita médica?
                                <i class="fa-solid fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                            </summary>
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                Dirígete a <strong>Citas médicas</strong> en el menú lateral y haz clic en <strong>+ Nuevo</strong>. Selecciona la fecha, especialidad y doctor. Se mostrarán los horarios disponibles; elige uno y completa los datos del paciente.
                            </p>
                        </details>

                        <details class="group p-4">
                            <summary class="flex items-center justify-between cursor-pointer text-sm font-medium text-gray-800 dark:text-gray-200 list-none">
                                ¿Cómo gestionar los horarios de un doctor?
                                <i class="fa-solid fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                            </summary>
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                Ve a <strong>Doctores</strong>, busca al doctor y haz clic en el botón de calendario <i class="fa-solid fa-calendar-alt"></i>. Verás una grilla semanal con franjas de 15 minutos; activa los casilleros donde el doctor atiende y guarda.
                            </p>
                        </details>

                        <details class="group p-4">
                            <summary class="flex items-center justify-between cursor-pointer text-sm font-medium text-gray-800 dark:text-gray-200 list-none">
                                ¿Cómo registrar la consulta de una cita?
                                <i class="fa-solid fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                            </summary>
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                En el listado de <strong>Citas médicas</strong> haz clic en el ícono <i class="fa-solid fa-stethoscope"></i>. Desde ahí puedes registrar el diagnóstico, tratamiento, notas y receta médica del paciente.
                            </p>
                        </details>

                        <details class="group p-4">
                            <summary class="flex items-center justify-between cursor-pointer text-sm font-medium text-gray-800 dark:text-gray-200 list-none">
                                ¿Cómo agregar un nuevo doctor al sistema?
                                <i class="fa-solid fa-chevron-down text-gray-400 group-open:rotate-180 transition-transform"></i>
                            </summary>
                            <p class="mt-3 text-sm text-gray-600 dark:text-gray-400">
                                Puedes ir a <strong>Usuarios → Nuevo</strong> y asignar el rol <em>Doctor</em> — el sistema creará el perfil médico automáticamente. O ir directamente a <strong>Doctores → Nuevo Doctor</strong> para ingresar todos los datos en un solo formulario.
                            </p>
                        </details>

                    </div>
                </div>
            </x-wire-card>

            {{-- Información de contacto --}}
            <x-wire-card>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-full bg-green-100 flex items-center justify-center">
                        <i class="fa-solid fa-headset text-green-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Contacto de Soporte</h3>
                </div>

                <div class="grid grid-cols-1 sm:grid-cols-2 gap-4">
                    <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <i class="fa-solid fa-envelope text-blue-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Email</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">soporte@simify.com</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <i class="fa-solid fa-phone text-green-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Teléfono</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">+1 (800) 000-0000</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <i class="fa-solid fa-clock text-purple-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Atención</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Lun – Vie, 9:00 – 18:00</p>
                        </div>
                    </div>
                    <div class="flex items-start gap-3 p-4 bg-gray-50 dark:bg-gray-700/50 rounded-lg">
                        <i class="fa-solid fa-comment-dots text-orange-500 mt-0.5"></i>
                        <div>
                            <p class="text-xs font-medium text-gray-500 dark:text-gray-400 uppercase mb-1">Chat en vivo</p>
                            <p class="text-sm font-medium text-gray-900 dark:text-gray-100">Disponible en el portal</p>
                        </div>
                    </div>
                </div>
            </x-wire-card>

        </div>

        {{-- Panel lateral: info del sistema --}}
        <div class="space-y-6">

            <x-wire-card>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-full bg-gray-100 flex items-center justify-center">
                        <i class="fa-solid fa-info text-gray-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Información del sistema</h3>
                </div>

                <div class="space-y-3 text-sm">
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Aplicación</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ config('app.name') }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Laravel</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ app()->version() }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">PHP</span>
                        <span class="font-medium text-gray-900 dark:text-gray-100">{{ PHP_VERSION }}</span>
                    </div>
                    <div class="flex justify-between">
                        <span class="text-gray-500 dark:text-gray-400">Entorno</span>
                        <span class="font-medium">
                            <span class="px-2 py-0.5 rounded-full text-xs
                                {{ app()->environment('production') ? 'bg-green-100 text-green-700' : 'bg-yellow-100 text-yellow-700' }}">
                                {{ app()->environment() }}
                            </span>
                        </span>
                    </div>
                </div>
            </x-wire-card>

            <x-wire-card>
                <div class="flex items-center gap-3 mb-5">
                    <div class="w-10 h-10 rounded-full bg-indigo-100 flex items-center justify-center">
                        <i class="fa-solid fa-book text-indigo-600 text-lg"></i>
                    </div>
                    <h3 class="text-lg font-semibold text-gray-900 dark:text-gray-100">Recursos</h3>
                </div>

                <div class="space-y-2 text-sm">
                    <a href="https://laravel.com/docs" target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <span class="font-medium text-gray-800 dark:text-gray-200">Documentación Laravel</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-gray-400 text-xs"></i>
                    </a>
                    <a href="https://livewire.laravel.com/docs" target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <span class="font-medium text-gray-800 dark:text-gray-200">Documentación Livewire</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-gray-400 text-xs"></i>
                    </a>
                    <a href="https://tailwindcss.com/docs" target="_blank"
                        class="flex items-center justify-between p-3 rounded-lg border border-gray-200 dark:border-gray-700 hover:bg-gray-50 dark:hover:bg-gray-700 transition-colors">
                        <span class="font-medium text-gray-800 dark:text-gray-200">Documentación Tailwind</span>
                        <i class="fa-solid fa-arrow-up-right-from-square text-gray-400 text-xs"></i>
                    </a>
                </div>
            </x-wire-card>

        </div>
    </div>
</x-admin-layout>
