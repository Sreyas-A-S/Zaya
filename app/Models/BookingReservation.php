<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class BookingReservation extends Model
{
    use HasFactory;

    protected $table = 'booking_reservations';

    protected $fillable = [
        'user_id',
        'practitioner_id',
        'booking_date',
        'booking_time',
        'reservation_token',
        'status',
        'booking_data',
        'expires_at',
    ];

    protected $casts = [
        'booking_date' => 'date',
        'expires_at' => 'datetime',
        'booking_data' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function isExpired(): bool
    {
        return $this->expires_at->isPast();
    }

    public function isActive(): bool
    {
        return $this->status === 'reserved' && !$this->isExpired();
    }
}
