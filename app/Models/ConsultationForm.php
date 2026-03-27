<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class ConsultationForm extends Model
{
    use HasFactory;

    protected $fillable = [
        'booking_id',
        'doctor_id',
        'payload',
    ];

    protected $casts = [
        'payload' => 'encrypted:array',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function doctor()
    {
        return $this->belongsTo(User::class, 'doctor_id');
    }
}
