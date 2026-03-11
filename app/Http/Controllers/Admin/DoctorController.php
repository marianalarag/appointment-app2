<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Doctor;
use App\Models\Specialty;
use App\Models\User;
use Illuminate\Http\Request;
use Illuminate\Support\Facades\Hash;

class DoctorController extends Controller
{
    public function index()
    {
        $doctors = Doctor::with('user', 'specialty')->get();
        return view('admin.doctors.index', compact('doctors'));
    }

    public function create()
    {
        $specialties = Specialty::all();
        return view('admin.doctors.create', compact('specialties'));
    }

    public function store(Request $request)
    {
        $request->validate([
            'name' => 'required|string|max:255',
            'email' => 'required|email|unique:users',
            'password' => 'required|min:8|confirmed',
            'phone' => 'nullable|string',
            'address' => 'nullable|string',
            'specialty_id' => 'required|exists:specialties,id',
            'license_number' => 'required|string|unique:doctors',
            'biography' => 'nullable|string',
        ]);

        // Crear usuario
        $user = User::create([
            'name' => $request->name,
            'email' => $request->email,
            'password' => Hash::make($request->password),
            'phone' => $request->phone,
            'address' => $request->address,
        ]);

        // Asignar rol de doctor
        $user->assignRole('Doctor');

        // Crear doctor
        Doctor::create([
            'user_id' => $user->id,
            'specialty_id' => $request->specialty_id,
            'license_number' => $request->license_number,
            'biography' => $request->biography,
        ]);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => '¡Doctor creado!',
            'text' => 'El doctor ha sido registrado exitosamente.'
        ]);

        return redirect()->route('admin.doctors.index');
    }

    public function show(Doctor $doctor)
    {
        $doctor->load('user', 'specialty');
        return view('admin.doctors.show', compact('doctor'));
    }

    public function edit(Doctor $doctor)
    {
        $specialties = Specialty::all();
        $doctor->load('user');
        return view('admin.doctors.edit', compact('doctor', 'specialties'));
    }

    public function update(Request $request, Doctor $doctor)
    {
        $request->validate([
            'name' => 'required|string|min:3|max:255',
            'email' => 'required|email|unique:users,email,' . $doctor->user_id,
            'phone' => 'nullable|string|max:20',
            'address' => 'nullable|string|max:255',
            'specialty_id' => 'required|exists:specialties,id',
            'license_number' => 'required|string|min:5|max:20|regex:/^[A-Za-z0-9\-]+$/|unique:doctors,license_number,' . $doctor->id,
            'biography' => 'nullable|string|max:500',
        ]);

        try {
            // Actualizar usuario
            $doctor->user->update([
                'name' => $request->name,
                'email' => $request->email,
                'phone' => $request->phone,
                'address' => $request->address,
            ]);

            // Actualizar doctor
            $doctor->update([
                'specialty_id' => $request->specialty_id,
                'license_number' => $request->license_number,
                'biography' => $request->biography,
            ]);

            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Doctor actualizado!',
                'text' => 'La información ha sido actualizada correctamente.'
            ]);

            return redirect()->route('admin.doctors.index');

        } catch (\Exception $e) {
            return back()->withErrors(['error' => 'Error: ' . $e->getMessage()])->withInput();
        }
    }

    public function destroy($id)
    {
        try {
            $doctor = Doctor::findOrFail($id);
            $user = User::findOrFail($doctor->user_id);

            // Primero eliminar el doctor
            $doctor->delete();

            // Luego eliminar el usuario
            $user->delete();

            // ✅ USAR session()->flash IGUAL QUE EN STORE Y UPDATE
            session()->flash('swal', [
                'icon' => 'success',
                'title' => '¡Doctor eliminado!',
                'text' => 'El doctor ha sido eliminado correctamente.'
            ]);

            return redirect()->route('admin.doctors.index');

        } catch (\Exception $e) {
            session()->flash('swal', [
                'icon' => 'error',
                'title' => 'Error',
                'text' => 'No se pudo eliminar: ' . $e->getMessage()
            ]);

            return redirect()->route('admin.doctors.index');
        }
    }
}
