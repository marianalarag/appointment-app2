<?php

namespace App\Http\Controllers\Api;

use App\Http\Controllers\Controller;
use App\Models\DoctorSchedule;
use Illuminate\Http\Request;

class AvailabilityController extends Controller
{
    /**
     * Obtener slots disponibles para un doctor en una fecha específica
     */
    public function getSlots(Request $request)
    {
        $request->validate([
            'doctor_id' => 'required|exists:doctors,id',
            'date' => 'required|date|after:today',
            'slot_duration' => 'nullable|integer|min:15|max:120',
        ]);

        $slotDuration = $request->input('slot_duration', 30);

        $slots = DoctorSchedule::getAvailableSlotsForDoctor(
            $request->input('doctor_id'),
            $request->input('date'),
            $slotDuration
        );

        return response()->json([
            'success' => true,
            'data' => $slots,
            'message' => count($slots) > 0 ? 
                'Slots disponibles' : 
                'No hay horarios disponibles para esta fecha'
        ]);
    }
}
