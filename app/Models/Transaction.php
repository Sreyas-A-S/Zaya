<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Transaction extends Model
{
    use HasFactory;

    protected $fillable = [
        'transaction_no',
        'user_id',
        'practitioner_id',
        'referrer_id',
        'booking_id',
        'referral_id',
        'country_id',
        'total_amount',
        'subtotal',
        'currency',
        'company_share',
        'practitioner_share',
        'referrer_share',
        'company_commission_percent',
        'referrer_commission_percent',
        'coins_used',
        'coin_discount',
        'payment_id',
        'status',
        'type',
    ];

    protected $casts = [
        'total_amount' => 'decimal:2',
        'company_share' => 'decimal:2',
        'practitioner_share' => 'decimal:2',
        'referrer_share' => 'decimal:2',
        'company_commission_percent' => 'decimal:2',
        'referrer_commission_percent' => 'decimal:2',
        'coin_discount' => 'decimal:2',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function practitioner()
    {
        return $this->belongsTo(User::class, 'practitioner_id');
    }

    public function referrer()
    {
        return $this->belongsTo(User::class, 'referrer_id');
    }

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function referral()
    {
        return $this->belongsTo(Referral::class);
    }

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}
