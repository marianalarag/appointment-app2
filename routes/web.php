<?php

use Illuminate\Support\Facades\Route;
use App\Http\Controllers\Admin\RoleController;
use App\Http\Controllers\Admin\UserController;
use App\Http\Controllers\Admin\PatientController;
use App\Http\Controllers\Admin\DoctorController;
use App\Http\Controllers\Admin\AppointmentController;
use App\Http\Controllers\Admin\CalendarController;

Route::redirect('/', '/admin');

Route::middleware([
    'auth:sanctum',
    config('jetstream.auth_session'),
    'verified',
])->group(function () {
    // Ruta del perfil CON LIVEWIRE
    Route::get('/user/profile', [\Laravel\Jetstream\Http\Controllers\Livewire\UserProfileController::class, 'show'])
        ->name('profile.show');

    Route::get('/dashboard', function () {
        return view('dashboard');
    })->name('dashboard');

    // =============================================
    // RUTAS ADMIN (con prefijo /admin)
    // =============================================
    Route::prefix('admin')->name('admin.')->group(function () {

        // Dashboard admin
        Route::get('/', function () {
            return view('admin.dashboard');
        })->name('dashboard');

        // CRUD de Usuarios
        Route::resource('users', UserController::class);

        // CRUD de Pacientes
        Route::resource('patients', PatientController::class);

        // CRUD de Roles (si existe)
        Route::resource('roles', RoleController::class);

        // CRUD de Doctores
        Route::resource('doctors', DoctorController::class);

        // CRUD de Citas Médicas
        Route::resource('appointments', AppointmentController::class);

        // Horarios de doctores
        Route::get('doctors/{doctor}/schedules', function (\App\Models\Doctor $doctor) {
            return view('admin.doctors.schedules', compact('doctor'));
        })->name('doctors.schedules');

        // Consulta médica
        Route::get('appointments/{appointment}/consultation', [AppointmentController::class, 'consultation'])
            ->name('appointments.consultation');

        // Calendario de citas
        Route::get('calendar', [CalendarController::class, 'index'])->name('calendar');

        // Soporte
        Route::get('support', fn () => view('admin.support.index'))->name('support');
    });
});