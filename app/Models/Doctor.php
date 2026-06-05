<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->slug) {
                $name = trim(($model->first_name ?? '') . ' ' . ($model->last_name ?? ''));
                if (empty($name)) {
                    $name = ($model->user ? $model->user->name : null) ?? 'doctor-' . time();
                }
                $baseSlug = \Illuminate\Support\Str::slug($name);
                $slug = $baseSlug ?: 'doctor-' . time();
                $count = 1;
                while (\App\Models\Doctor::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $model->slug = $slug;
            }
        });
    }

    protected $table = 'doctors';
    protected $guarded = [];

    protected $casts = [
        'specialization' => 'array',
        'other_modalities' => 'array',
        'degree_certificates_path' => 'array',
        'consultation_expertise' => 'array',
        'health_conditions_treated' => 'array',
        'panchakarma_procedures' => 'array',
        'external_therapies' => 'array',
        'consultation_modes' => 'array',
        'languages_spoken' => 'array',
        'social_links' => 'array',
        'dob' => 'date',
        'panchakarma_consultation' => 'boolean',
        'ayush_registration_confirmed' => 'boolean',
        'ayush_guidelines_agreed' => 'boolean',
        'document_verification_consented' => 'boolean',
        'policies_agreed' => 'boolean',
        'prescription_understanding_agreed' => 'boolean',
        'confidentiality_consented' => 'boolean',
        'booking_window_days' => 'integer',
        'default_slot_duration' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userServices()
    {
        return $this->hasMany(UserService::class, 'user_id', 'user_id');
    }

    public function reviews()
    {
        // Doctors use practitioner_id in practitioner_reviews table for simplicity in generic review system
        return $this->hasMany(PractitionerReview::class, 'practitioner_id', 'id');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', true)->avg('rating') ?? 0;
    }

    public function getProfileBioAttribute()
    {
        return $this->short_doctor_bio ?? ($this->short_bio ?? '');
    }

    public function getCityStateAttribute()
    {
        if ($this->city && $this->state) {
            return $this->city . ', ' . $this->state;
        }
        return $this->city ?: ($this->state ?: ($this->country ?? 'Location not set'));
    }

    public function getSubtitleDisplayAttribute()
    {
        $specialization = (array) ($this->specialization ?? []);
        $subtitle = $specialization[0] ?? ($this->consultation_expertise[0] ?? 'Ayurvedic Doctor');
        return str_replace('_', ' ', ucfirst($subtitle));
    }

    public function getExpertisesListAttribute()
    {
        $list = array_merge(
            (array) ($this->health_conditions_treated ?? []),
            (array) ($this->specialization ?? []),
            (array) ($this->consultation_expertise ?? []),
            (array) ($this->panchakarma_procedures ?? []),
            (array) ($this->external_therapies ?? [])
        );
        return array_values(array_unique(array_filter($list, fn ($v) => trim((string) $v) !== '')));
    }

    public function getConditionsListAttribute()
    {
        $list = array_merge(
            (array) ($this->health_conditions_treated ?? [])
        );
        return array_values(array_unique(array_filter($list, fn ($v) => trim((string) $v) !== '')));

    }
    // Aliases for Profile Completion and Generic Views
    public function getStateCouncilNameAttribute()
    {
        return $this->state_ayurveda_council_name;
    }

    public function setStateCouncilNameAttribute($value)
    {
        $this->attributes['state_ayurveda_council_name'] = $value;
    }

    public function getBankHolderNameAttribute()
    {
        return $this->bank_account_holder_name;
    }

    public function setBankHolderNameAttribute($value)
    {
        $this->attributes['bank_account_holder_name'] = $value;
    }

    public function getRegistrationCertificatePathAttribute()
    {
        return $this->reg_certificate_path;
    }

    public function setRegistrationCertificatePathAttribute($value)
    {
        $this->attributes['reg_certificate_path'] = $value;
    }

    public function getPanCardPathAttribute()
    {
        return $this->pan_upload_path;
    }

    public function setPanCardPathAttribute($value)
    {
        $this->attributes['pan_upload_path'] = $value;
    }


    public function getAadhaarCardPathAttribute()
    {
        return $this->aadhaar_upload_path;
    }

    public function setAadhaarCardPathAttribute($value)
    {
        $this->attributes['aadhaar_upload_path'] = $value;
    }

    public function getSignaturePathAttribute()
    {
        return $this->digital_signature_path;
    }

    public function setSignaturePathAttribute($value)
    {
        $this->attributes['digital_signature_path'] = $value;
    }

    public function getCancelledChequePathAttribute($value)
    {
        return $value ?? ($this->attributes['cancelled_cheque_path'] ?? null);
    }

    public function getReminderLeadTimeAttribute($value)
    {
        if (empty($value)) {
            return [60];
        }
        if (is_array($value)) {
            return $value;
        }
        $decoded = json_decode($value, true);
        if (is_array($decoded)) {
            return array_map('intval', $decoded);
        }
        if (is_string($value) && strpos($value, ',') !== false) {
            return array_map('intval', explode(',', $value));
        }
        return [(int) $value];
    }

    public function setReminderLeadTimeAttribute($value)
    {
        if (is_array($value)) {
            $this->attributes['reminder_lead_time'] = json_encode(array_values(array_unique(array_map('intval', $value))));
        } else {
            $this->attributes['reminder_lead_time'] = $value;
        }
    }
}

