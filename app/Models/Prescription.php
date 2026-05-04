<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Prescription extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'profile_id',
        'practitioner_type',
        'user_id',
        'title',
        'prescription_date',
        'medications',
        'lifestyle_advice',
        'notes',
        'status',
    ];

    protected $casts = [
        'prescription_date' => 'date',
        'medications' => 'encrypted:array',
        'lifestyle_advice' => 'encrypted',
        'notes' => 'encrypted',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function practitioner()
    {
        return $this->morphTo('practitioner', 'practitioner_type', 'profile_id');
    }

    public function patient()
    {
        return $this->belongsTo(User::class, 'user_id');
    }
}
