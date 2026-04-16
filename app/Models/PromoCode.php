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

    /**
     * Increment used_count only when code is active, not expired, and within usage limit.
     */
    public function incrementUsageIfAvailable(): bool
    {
        $updated = static::query()
            ->whereKey($this->id)
            ->where('status', true)
            ->where(function ($q) {
                $q->whereNull('expiry_date')
                    ->orWhereDate('expiry_date', '>=', now()->toDateString());
            })
            ->where(function ($q) {
                $q->whereNull('usage_limit')
                    ->orWhereColumn('used_count', '<', 'usage_limit');
            })
            ->increment('used_count');

        if ($updated > 0) {
            $this->refresh();
            return true;
        }

        return false;
    }
}
