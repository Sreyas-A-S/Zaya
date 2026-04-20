<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class CoinSetting extends Model
{
    protected $fillable = ['currency_code', 'coin_value', 'referral_coins', 'status'];
}
