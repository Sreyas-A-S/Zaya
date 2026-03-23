<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Referral extends Model
{
    use HasFactory;

    protected $fillable = [
        'referral_no',
        'booking_id',
        'user_id',
        'referred_by_id',
        'referred_to_id',
        'service_ids',
        'amount',
        'status',
        'razorpay_order_id',
        'razorpay_payment_id',
    ];

    protected $casts = [
        'service_ids' => 'array',
        'amount' => 'decimal:2',
    ];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function referredBy()
    {
        return $this->belongsTo(User::class, 'referred_by_id');
    }

    public function referredTo()
    {
        return $this->belongsTo(User::class, 'referred_to_id');
    }
}
