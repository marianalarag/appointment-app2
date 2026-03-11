<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'blood_type_id',
        'allergies',
        'address',
        'emergency_contact_name',
        'emergency_contact_phone',
        'chronic_conditions',
        'family_history',
        'observations',
        'emergency_contact_relationship',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function bloodType()
    {
        return $this->belongsTo(BloodType::class);
    }

    public function appointments()
    {
        return $this->hasMany(Appointment::class);
    }
}
