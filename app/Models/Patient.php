<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Patient extends Model
{
    use HasFactory;

    protected $table = 'patients';
    protected $guarded = [];

    protected $casts = [
        'dob' => 'date',
        'consultation_preferences' => 'encrypted:json',
        'languages_spoken' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function getCityStateAttribute()
    {
        if ($this->city && $this->state) {
            return $this->city . ', ' . $this->state;
        }
        return $this->city ?: ($this->state ?: 'Location not set');
    }

    public function getAddressAttribute()
    {
        $parts = array_filter([$this->address_line_1, $this->address_line_2]);
        return count($parts) > 0 ? implode(', ', $parts) : null;
    }
}
