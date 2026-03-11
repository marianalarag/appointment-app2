<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class DoctorSchedule extends Model
{
    use HasFactory;

    protected $table = 'doctor_schedules';

    protected $fillable = [
        'doctor_id',
        'day_of_week',
        'start_time',
        'end_time',
        'is_active',
    ];

    protected $casts = [
        'day_of_week' => 'integer',
        'is_active' => 'boolean',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    /**
     * Obtener los días de la semana disponibles
     */
    public static function getDaysOfWeek()
    {
        return [
            1 => 'Lunes',
            2 => 'Martes',
            3 => 'Miércoles',
            4 => 'Jueves',
            5 => 'Viernes',
            6 => 'Sábado',
            7 => 'Domingo',
        ];
    }

    /**
     * Obtener horarios disponibles para un doctor en una fecha específica
     */
    public static function getAvailableSlotsForDoctor($doctorId, $date, $slotDuration = 30)
    {
        $dayOfWeek = \Carbon\Carbon::parse($date)->dayOfWeek;
        // Carbon devuelve 0-6 (Sunday-Saturday), nosotros usamos 1-7 (Monday-Sunday)
        $dayOfWeek = $dayOfWeek === 0 ? 7 : $dayOfWeek;

        $schedules = self::where('doctor_id', $doctorId)
            ->where('day_of_week', $dayOfWeek)
            ->where('is_active', true)
            ->get();

        if ($schedules->isEmpty()) {
            return [];
        }

        $slots = [];
        foreach ($schedules as $schedule) {
            $startTime = \Carbon\Carbon::createFromTimeString($schedule->start_time);
            $endTime = \Carbon\Carbon::createFromTimeString($schedule->end_time);

            while ($startTime < $endTime) {
                $slotEnd = $startTime->copy()->addMinutes($slotDuration);
                
                // Verificar si este slot está disponible
                $hasConflict = Appointment::where('doctor_id', $doctorId)
                    ->where('date', $date)
                    ->where(function ($query) use ($startTime, $slotEnd) {
                        $query->whereRaw("TIME(start_time) < ?", [$slotEnd->format('H:i:s')])
                              ->whereRaw("TIME(end_time) > ?", [$startTime->format('H:i:s')]);
                    })
                    ->where('status', '!=', Appointment::STATUS_CANCELADO)
                    ->exists();

                if (!$hasConflict) {
                    $slots[] = [
                        'time' => $startTime->format('H:i'),
                        'display' => $startTime->format('H:i') . ' - ' . $slotEnd->format('H:i'),
                    ];
                }

                $startTime = $slotEnd;
            }
        }

        return $slots;
    }
}
