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
        'languages_spoken' => 'array',
    ];

    /**
     * Gracefully handle encrypted consultation_preferences
     * fallback to raw or empty array if decryption fails
     */
    public function getConsultationPreferencesAttribute($value)
    {
        if (empty($value)) return [];

        try {
            // Try decrypting with serialization (default)
            $decrypted = decrypt($value);
            return is_string($decrypted) ? json_decode($decrypted, true) : $decrypted;
        } catch (\Throwable $e) {
            try {
                // Fallback: Try decrypting WITHOUT serialization
                $decrypted = decrypt($value, false);
                $decoded = json_decode($decrypted, true);
                if (json_last_error() === JSON_ERROR_NONE) {
                    return $decoded;
                }
                return is_array($decrypted) ? $decrypted : [];
            } catch (\Throwable $e2) {
                // Fallback: If it's already a JSON string (not encrypted), try to decode it
                $decoded = json_decode($value, true);
                if (json_last_error() === JSON_ERROR_NONE && is_array($decoded)) {
                    return $decoded;
                }
                return [];
            }
        }
    }

    public function setConsultationPreferencesAttribute($value)
    {
        if (is_null($value)) {
            $this->attributes['consultation_preferences'] = null;
        } else {
            $this->attributes['consultation_preferences'] = encrypt(is_array($value) ? json_encode($value) : $value);
        }
    }

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
