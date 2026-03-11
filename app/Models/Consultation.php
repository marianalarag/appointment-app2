<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class Consultation extends Model
{
    use HasFactory;

    protected $fillable = [
        'appointment_id',
        'diagnosis',
        'treatment',
        'notes',
        'prescriptions',
    ];

    protected $casts = [
        'prescriptions' => 'array',
    ];

    public function appointment()
    {
        return $this->belongsTo(Appointment::class);
    }
}
