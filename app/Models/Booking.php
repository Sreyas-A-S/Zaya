<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'practitioner_id',
        'service_ids',
        'mode',
        'conditions',
        'situation',
        'need_translator',
        'from_language',
        'to_language',
        'booking_date',
        'booking_time',
        'total_price',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
    ];

    protected $casts = [
        'service_ids' => 'array',
        'need_translator' => 'boolean',
        'booking_date' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }
}
