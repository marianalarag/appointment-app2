<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Appointment extends Model
{
    use HasFactory;

    // Status constants
    const STATUS_PROGRAMADO = 1;
    const STATUS_COMPLETADO = 2;
    const STATUS_CANCELADO  = 3;

    protected $table = 'appointments';

    protected $fillable = [
        'doctor_id',
        'patient_id',
        'date',
        'start_time',
        'end_time',
        'duration',
        'reason',
        'status',
    ];

    protected $casts = [
        'date' => 'date',
    ];

    public function doctor()
    {
        return $this->belongsTo(Doctor::class);
    }

    public function patient()
    {
        return $this->belongsTo(Patient::class);
    }

    public function consultation()
    {
        return $this->hasOne(Consultation::class);
    }

    public function getStatusLabelAttribute(): string
    {
        return match((int) $this->status) {
            self::STATUS_PROGRAMADO => 'Programado',
            self::STATUS_COMPLETADO => 'Completado',
            self::STATUS_CANCELADO  => 'Cancelado',
            default                 => 'Desconocido',
        };
    }

    public function getStatusColorAttribute(): string
    {
        return match((int) $this->status) {
            self::STATUS_PROGRAMADO => 'blue',
            self::STATUS_COMPLETADO => 'green',
            self::STATUS_CANCELADO  => 'red',
            default                 => 'gray',
        };
    }

    public function scopeByDoctor($query, $doctorId)
    {
        return $query->where('doctor_id', $doctorId);
    }

    public function scopeByPatient($query, $patientId)
    {
        return $query->where('patient_id', $patientId);
    }

    public function scopeInDateRange($query, $startDate, $endDate)
    {
        return $query->whereBetween('date', [$startDate, $endDate]);
    }

    public function scopeActive($query)
    {
        return $query->where('status', '!=', self::STATUS_CANCELADO);
    }
}
