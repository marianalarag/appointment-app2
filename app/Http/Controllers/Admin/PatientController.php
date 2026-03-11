<?php

namespace App\Http\Controllers\Admin;

use App\Http\Controllers\Controller;
use App\Models\Patient;
use Illuminate\Http\Request;
use App\Models\BloodType;

class PatientController extends Controller
{
    /**
     * Display a listing of the resource.
     */
    public function index()
    {
        return view('admin.patients.index');
    }

    /**
     * Show the form for creating a new resource.
     */
    public function create()
    {
        return view('admin.patients.create');
    }

    /**
     * Store a newly created resource in storage.
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     */
    public function show(Patient $patient)
    {
        return view('admin.patients.show', compact('patient'));
    }

    /**
     * Show the form for editing the specified resource.
     */
    public function edit(Patient $patient)
    {
        $bloodTypes = BloodType::all();

        // Determinar qué tab debe estar activo basado en los errores de validación
        if (session('errors')) {
            $errors = session('errors');

            // Array para guardar qué tabs tienen errores
            $tabsWithErrors = [];

            if ($errors->hasAny(['allergies', 'chronic_conditions', 'surgical_history', 'family_history'])) {
                $tabsWithErrors[] = 'antecedentes';
            }

            if ($errors->hasAny(['blood_type_id', 'observations'])) {
                $tabsWithErrors[] = 'informacion-general';
            }

            if ($errors->hasAny(['emergency_contact_name', 'emergency_contact_phone', 'emergency_contact_relationship'])) {
                $tabsWithErrors[] = 'contacto-emergencia';
            }

            // Guardar en sesión para la vista
            session()->flash('tabs_with_errors', $tabsWithErrors);

            // Activar el primer tab con errores (o el último si prefieres)
            if (!empty($tabsWithErrors)) {
                session()->flash('active_tab', $tabsWithErrors[0]); // Primer tab con errores
                // session()->flash('active_tab', end($tabsWithErrors)); // Último tab con errores
            }
        }

        return view('admin.patients.edit', compact('patient', 'bloodTypes'));
    }

    /**
     * Update the specified resource in storage.
     */
    public function update(Request $request, Patient $patient)
    {
        // Limpiar el teléfono: quitar todo lo que no sea número
        if ($request->filled('emergency_contact_phone')) {
            $request->merge([
                'emergency_contact_phone' => preg_replace(
                    '/[^0-9]/',
                    '',
                    $request->emergency_contact_phone
                ),
            ]);
        }

        $data = $request->validate([
            // Antecedentes
            'allergies' => 'nullable|string|min:3|max:255',
            'chronic_conditions' => 'nullable|string|min:3|max:255',
            'surgical_history' => 'nullable|string|min:3|max:255',
            'family_history' => 'nullable|string|min:3|max:255',

            // Información general
            'blood_type_id' => 'nullable|exists:blood_types,id',
            'observations' => 'nullable|string|min:3|max:255',

            // Contacto de emergencia
            'emergency_contact_name' => 'nullable|string|min:3|max:255',
            'emergency_contact_phone' => 'nullable|digits:10',
            'emergency_contact_relationship' => 'nullable|string|min:3|max:255',
        ]);

        $patient->update($data);

        session()->flash('swal', [
            'icon' => 'success',
            'title' => 'Paciente actualizado',
            'text' => 'El paciente se actualizó correctamente',
        ]);

        return redirect()->route('admin.patients.edit', $patient);
    }


    /**
     * Remove the specified resource from storage.
     */
    public function destroy(Patient $patient)
    {
        //
    }
}
