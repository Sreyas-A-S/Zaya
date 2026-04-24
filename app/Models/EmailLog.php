<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class EmailLog extends Model
{
    protected $fillable = [
        'booking_id',
        'to',
        'subject',
        'body',
        'status',
        'duration',
        'error_message',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }
}
