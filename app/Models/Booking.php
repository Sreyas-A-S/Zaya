<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Booking extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'profile_id',
        'practitioner_type',
        'invoice_no',
        'service_ids',
        'mode',
        'conditions',
        'situation',
        'need_translator',
        'from_language',
        'to_language',
        'language_id',
        'translator_id',
        'booking_date',
        'booking_time',
        'subtotal',
        'total_price',
        'promo_code',
        'discount_amount',
        'coins_used',
        'coin_discount',
        'currency',
        'status',
        'is_test',
        'reminder_sent',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_payment_url',
        'recording_url',
        'payment_details',
        'additional_info'
    ];

    protected $casts = [
        'service_ids' => 'array',
        'need_translator' => 'boolean',
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'coin_discount' => 'decimal:2',
        'is_test' => 'boolean',
        'reminder_sent' => 'boolean',
        'payment_details' => 'encrypted:array',
        'conditions' => 'encrypted:array',
        'situation' => 'encrypted',
        'additional_info' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function practitioner()
    {
        return $this->morphTo('practitioner', 'practitioner_type', 'profile_id');
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function translator()
    {
        return $this->belongsTo(Translator::class);
    }

    public function consultationForms()
    {
        return $this->hasMany(ConsultationForm::class);
    }

    public function referral()
    {
        return $this->hasOne(Referral::class, 'referral_no', 'invoice_no');
    }

    /**
     * Referrals created from this booking session.
     */
    public function referralsFromThisSession()
    {
        return $this->hasMany(Referral::class, 'booking_id');
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }
}
