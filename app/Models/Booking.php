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
        'download_token',
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
        'original_booking_date',
        'original_booking_time',
        'rescheduled_at',
        'rescheduled_by',
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
        'original_booking_date' => 'date',
        'rescheduled_at' => 'datetime',
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

    public function referralRequests()
    {
        return $this->hasMany(ReferralRequest::class);
    }

    public function transactions()
    {
        return $this->hasMany(Transaction::class);
    }

    /**
     * Get the status including 'missed' for past sessions.
     */
    public function getEffectiveStatusAttribute()
    {
        if (in_array($this->status, ['completed', 'cancelled'])) {
            return $this->status;
        }

        // Use the practitioner's timezone if possible, fallback to UTC
        $timezone = 'UTC';
        try {
            if ($this->practitioner && $this->practitioner->user) {
                $timezone = derive_timezone_from_user($this->practitioner->user);
            }
        } catch (\Exception $e) { }

        // For multi-session bookings, check the LAST session
        $sessions = $this->additional_info['sessions'] ?? [];
        if (!empty($sessions)) {
            $lastSession = collect($sessions)->last();
            $date = !empty($lastSession['day']) && $lastSession['day'] !== 'Day' ? $lastSession['day'] : $this->booking_date->format('Y-m-d');
            $time = !empty($lastSession['time']) && $lastSession['time'] !== 'Time' ? $lastSession['time'] : $this->booking_time;
            
            try {
                $lastSessionTime = \Carbon\Carbon::parse($date . ' ' . $time, $timezone);
                // If the last session started more than 60 mins ago and booking not completed
                if ($lastSessionTime->addMinutes(60)->isPast()) {
                    return 'missed';
                }
            } catch (\Exception $e) { }
        } else {
            // Single session
            try {
                $bookingDateTime = \Carbon\Carbon::parse($this->booking_date->format('Y-m-d') . ' ' . $this->booking_time, $timezone);
                if ($bookingDateTime->addMinutes(60)->isPast()) {
                    return 'missed';
                }
            } catch (\Exception $e) { }
        }

        return $this->status;
    }

    /**
     * Check if a specific session from additional_info has passed.
     */
    public function isSessionPassed($session, $timezone = 'UTC')
    {
        $date = !empty($session['day']) && $session['day'] !== 'Day' ? $session['day'] : $this->booking_date->format('Y-m-d');
        $time = !empty($session['time']) && $session['time'] !== 'Time' ? $session['time'] : $this->booking_time;
        
        try {
            return \Carbon\Carbon::parse($date . ' ' . $time, $timezone)->addMinutes(15)->isPast();
        } catch (\Exception $e) {
            return false;
        }
    }
}
