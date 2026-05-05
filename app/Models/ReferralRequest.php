<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralRequest extends Model
{
    protected $fillable = ['booking_id', 'requester_id', 'recipient_id', 'expert_type', 'note', 'status'];

    public function booking()
    {
        return $this->belongsTo(Booking::class);
    }

    public function requester()
    {
        return $this->belongsTo(User::class, 'requester_id');
    }

    public function recipient()
    {
        return $this->belongsTo(User::class, 'recipient_id');
    }
}
