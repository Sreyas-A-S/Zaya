<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PromoCode extends Model
{
    use HasFactory;

    protected $fillable = [
        'code',
        'type',
        'usage_type',
        'reward',
        'description',
        'benefits',
        'usage_limit',
        'used_count',
        'expiry_date',
        'status',
    ];

    protected $casts = [
        'expiry_date' => 'datetime',
        'status' => 'boolean',
        'used_count' => 'integer',
        'usage_limit' => 'integer',
        'reward' => 'decimal:2',
        'benefits' => 'array',
    ];
}
