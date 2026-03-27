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
        'total_price',
        'currency',
        'status',
        'reminder_sent',
        'razorpay_order_id',
        'razorpay_payment_id',
        'razorpay_payment_url',
        'recording_url',
        'payment_details'
    ];

    protected $casts = [
        'service_ids' => 'array',
        'need_translator' => 'boolean',
        'booking_date' => 'date',
        'total_price' => 'decimal:2',
        'reminder_sent' => 'boolean',
        'payment_details' => 'encrypted:array',
        'conditions' => 'encrypted',
        'situation' => 'encrypted',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function practitioner()
    {
        return $this->belongsTo(Practitioner::class);
    }

    public function language()
    {
        return $this->belongsTo(Language::class);
    }

    public function translator()
    {
        return $this->belongsTo(Translator::class);
    }

    public function consultationForm()
    {
        return $this->hasOne(ConsultationForm::class);
    }
}
