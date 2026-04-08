<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class ReferralCommissionRate extends Model
{
    protected $fillable = [
        'country_id',
        'type',
        'referrer_role',
        'referred_role',
        'company_commission_percent',
        'referrer_commission_percent',
    ];

    protected $casts = [
        'company_commission_percent' => 'decimal:2',
        'referrer_commission_percent' => 'decimal:2',
    ];

    public function country()
    {
        return $this->belongsTo(Country::class);
    }
}

