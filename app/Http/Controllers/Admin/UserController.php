<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;
use Spatie\Permission\Models\Role;

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
        $request->validate([
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email',
            'password'  => 'required|min:8|confirmed',
            'role'      => 'required|exists:roles,id',

            // Nuevos campos
            'id_number' => 'nullable|string|max:50',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:500',
        ]);

        // Crear usuario
        $user = User::create([
            'name'      => $request->name,
            'email'     => $request->email,
            'password'  => Hash::make($request->password),
            'id_number' => $request->id_number,
            'phone'     => $request->phone,
            'address'   => $request->address,
        ]);

        // Asignar rol
        $role = Role::findById($request->role);
        $user->assignRole($role);

        // Mensaje de éxito
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario creado correctamente',
            'text'  => 'El usuario ha sido creado correctamente',
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
            'name'      => 'required|string|max:255',
            'email'     => 'required|email|unique:users,email,' . $user->id,
            'password'  => 'nullable|min:8|confirmed',
            'role'      => 'required|exists:roles,id',

            // Nuevos campos
            'id_number' => 'nullable|string|max:50',
            'phone'     => 'nullable|string|max:20',
            'address'   => 'nullable|string|max:500',
        ]);

        // Datos base
        $data = [
            'name'      => $request->name,
            'email'     => $request->email,
            'id_number' => $request->id_number,
            'phone'     => $request->phone,
            'address'   => $request->address,
        ];

        // Actualizar contraseña si viene
        if ($request->filled('password')) {
            $data['password'] = Hash::make($request->password);
        }

        // Actualizar usuario
        $user->update($data);

        // Sincronizar rol
        $role = Role::findById($request->role);
        $user->syncRoles([$role]);

        // Mensaje de éxito
        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario actualizado correctamente',
            'text'  => 'El usuario ha sido actualizado correctamente',
        ]);

        return redirect()->route('admin.users.index');
    }

    /**
     * Remove the specified resource from storage.
     */
    public function destroy(User $user)
    {
        // Proteger admin principal
        if ($user->id === 1) {
            session()->flash('swal', [
                'icon'  => 'error',
                'title' => 'Acción no permitida',
                'text'  => 'No puedes eliminar el usuario administrador principal',
            ]);

            return redirect()->route('admin.users.index');
        }

        $user->delete();

        session()->flash('swal', [
            'icon'  => 'success',
            'title' => 'Usuario eliminado',
            'text'  => 'El usuario ha sido eliminado correctamente',
        ]);

        return redirect()->route('admin.users.index');
    }
}
