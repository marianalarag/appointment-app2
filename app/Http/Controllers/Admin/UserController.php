<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use App\Models\Patient;
use App\Models\Doctor; // 👈 IMPORTAR MODELO DOCTOR
use Illuminate\Http\Request;
use Spatie\Permission\Models\Role;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Facades\Log;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        $users = User::with('roles')->get();
        return view('admin.users.index', compact('users'));
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        $roles = Role::all();
        return view('admin.users.create', compact('roles'));
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        // Validación
        $data = $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users',
            'password' => 'required|string|min:8|confirmed',
            'id_number' => 'required|string|min:5|max:20|regex:/[A-Za-z0-9\-]+$/|unique:users',
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role' => 'required|exists:roles,id'
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Asignar rol
        $role = Role::findById($request->role);
        $user->assignRole($role);

        // 📌 CREAR REGISTRO SEGÚN EL ROL
        if ($role->name === 'Paciente') {
            $this->crearPaciente($user, $request);
        }
        // 👇 NUEVO: Crear registro en doctors si el rol es Doctor
        else if ($role->name === 'Doctor') {
            $this->crearDoctor($user, $request);
        }

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario creado correctamente',
            'text' => 'El usuario ha sido creado correctamente'
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Display the specified resource.
     */
    public function show(User $user)
    {
        return view('admin.users.show', compact('user'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(User $user)
    {
        $roles = Role::all();
        return view('admin.users.edit', compact('user', 'roles'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, User $user)
    {
        // Validación
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|string|email|unique:users,email,' . $user->id,
            'id_number' => 'required|string|min:5|max:20|regex:/[A-Za-z0-9\-]+$/|unique:users,id_number,' . $user->id,
            'phone' => 'required|digits_between:7,15',
            'address' => 'required|string|min:3|max:255',
            'role' => 'required|exists:roles,id',
            'password' => 'nullable|min:8|confirmed',
            'current_password' => [
                'required_unless:email,' . $user->email,
                function ($attribute, $value, $fail) use ($user) {
                    if (!Hash::check($value, $user->password)) {
                        $fail('La contraseña actual es incorrecta.');
                    }
                },
            ],
        ]);

        // Preparar datos
        $data = [
            'name' => $request->name,
            'email' => $request->email,
            'id_number' => $request->id_number,
            'phone' => $request->phone,
            'address' => $request->address,
        ];

        // Actualizar contraseña si se proporcionó
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Obtener rol anterior
        $oldRole = $user->roles->first();
        $oldRoleName = $oldRole ? $oldRole->name : null;

        // Actualizar usuario
        $user->update($data);

        // Sincronizar rol
        $role = Role::findById($request->role);
        $user->syncRoles([$role]);

        Log::info('Actualizando usuario ID: ' . $user->id, [
            'rol_anterior' => $oldRoleName,
            'rol_nuevo' => $role->name
        ]);

        // 📌 MANEJAR SEGÚN EL NUEVO ROL
        $this->manejarCambioRol($user, $oldRoleName, $role->name, $request);

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text' => 'El usuario ha sido actualizado correctamente'
        ]);

        return redirect()->route('admin.users.edit', $user->id);
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // No permitir que el usuario logueado se borre a sí mismo
        if (auth()->id() === $user->id) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminarte a ti mismo'
            ]);
            return redirect()->route('admin.users.index');
        }

        // Prevenir eliminación de usuario admin principal (id 1)
        if ($user->id === 1) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No puedes eliminar el usuario administrador principal'
            ]);
            return redirect()->route('admin.users.index');
        }

        // 📌 Eliminar registros relacionados
        if ($user->patient) {
            $user->patient->delete();
            Log::info('Registro de paciente eliminado para usuario: ' . $user->id);
        }

        // 👇 NUEVO: Eliminar registro de doctor si existe
        if ($user->doctor) {
            $user->doctor->delete();
            Log::info('Registro de doctor eliminado para usuario: ' . $user->id);
        }

        // Eliminar roles asociados al usuario
        $user->roles()->detach();

        // Eliminar el usuario
        $user->delete();

        // Mensaje de éxito
        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Usuario eliminado correctamente',
            'text' => 'El usuario ha sido eliminado correctamente'
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Método auxiliar para crear paciente
     */
    private function crearPaciente($user, $request)
    {
        Log::info('Creando paciente para usuario ID: ' . $user->id);

        if (!$user->patient) {
            try {
                Patient::create([
                    'user_id' => $user->id,
                    'address' => $request->address,
                    'allergies' => null,
                    'emergency_contact_name' => null,
                    'emergency_contact_phone' => null,
                    'emergency_contact_relationship' => null,
                    'chronic_conditions' => null,
                    'family_history' => null,
                    'observations' => null,
                ]);
                Log::info('✅ Paciente creado exitosamente');
            } catch (\Exception $e) {
                Log::error('❌ Error al crear paciente: ' . $e->getMessage());
            }
        }
    }

    /**
     * 👇 NUEVO: Método auxiliar para crear doctor
     */
    private function crearDoctor($user, $request)
    {
        Log::info('Creando doctor para usuario ID: ' . $user->id);

        if (!$user->doctor) {
            try {
                Doctor::create([
                    'user_id' => $user->id,
                    'specialty_id' => 1, // Por defecto, asignar primera especialidad
                    'license_number' => 'PENDIENTE-' . $user->id, // Temporal
                    'biography' => null,
                ]);
                Log::info('✅ Doctor creado exitosamente para usuario: ' . $user->name);
            } catch (\Exception $e) {
                Log::error('❌ Error al crear doctor: ' . $e->getMessage());
            }
        } else {
            Log::info('El usuario ya tiene registro de doctor');
        }
    }

    /**
     * 👇 NUEVO: Método para manejar cambios de rol
     */
    private function manejarCambioRol($user, $oldRoleName, $newRoleName, $request)
    {
        // Si el nuevo rol es Paciente
        if ($newRoleName === 'Paciente') {
            if ($user->patient) {
                $user->patient->update(['address' => $request->address]);
                Log::info('✅ Paciente actualizado');
            } else {
                $this->crearPaciente($user, $request);
            }
        }
        // Si el nuevo rol es Doctor
        else if ($newRoleName === 'Doctor') {
            if ($user->doctor) {
                // Actualizar dirección si existe relación
                Log::info('Doctor ya existe, no se actualizan datos médicos');
            } else {
                $this->crearDoctor($user, $request);
            }
        }

        // Si cambia de Paciente a otro rol
        if ($oldRoleName === 'Paciente' && $newRoleName !== 'Paciente') {
            if ($user->patient) {
                $user->patient->delete();
                Log::info('Registro de paciente eliminado por cambio de rol');
            }
        }

        // Si cambia de Doctor a otro rol
        if ($oldRoleName === 'Doctor' && $newRoleName !== 'Doctor') {
            if ($user->doctor) {
                $user->doctor->delete();
                Log::info('Registro de doctor eliminado por cambio de rol');
            }
        }
    }
}
