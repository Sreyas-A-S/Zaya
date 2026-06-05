<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->slug) {
                $name = trim(($model->first_name ?? '') . ' ' . ($model->last_name ?? ''));
                if (empty($name)) {
                    $name = ($model->user ? $model->user->name : null) ?? 'practitioner-' . time();
                }
                $baseSlug = \Illuminate\Support\Str::slug($name);
                if (empty($baseSlug)) {
                    $baseSlug = 'practitioner-' . time();
                }
                $slug = $baseSlug;
                $count = 1;
                while (\App\Models\Practitioner::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $model->slug = $slug;
            }
        });
    }

    protected $table = 'practitioners';
    protected $fillable = [
        'user_id',
        'payout_currency',
        'status',
        'booking_window_days',
        'reminder_lead_time',
        'min_notice_hours',
        'first_name',
        'last_name',
        'slug',
        'gender',
        'dob',
        'nationality',
        'profile_photo_path',
        'residential_address',
        'zip_code',
        'phone',
        'website_url',
        'social_links',
        'consultations',
        'body_therapies',
        'other_modalities',
        'specialization',
        'health_conditions_treated',
        'additional_courses',
        'languages_spoken',
        'can_translate_english',
        'profile_bio',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'country',
        'doc_cover_letter',
        'doc_certificates',
        'doc_experience',
        'doc_registration',
        'doc_ethics',
        'doc_contract',
        'doc_id_proof',
        'bank_name',
        'account_number',
        'ifsc_code',
        'swift_code',
        'cancelled_cheque_path',
        'bank_account_holder_name',
        'upi_id',
    ];

    protected $casts = [
        'consultations' => 'array',
        'body_therapies' => 'array',
        'other_modalities' => 'array',
        'specialization' => 'array',
        'health_conditions_treated' => 'array',
        'languages_spoken' => 'array',
        'dob' => 'date',
        'can_translate_english' => 'boolean',
        'social_links' => 'array',
        'booking_window_days' => 'integer',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function userServices()
    {
        return $this->hasMany(UserService::class, 'user_id', 'user_id');
    }

    public function qualifications()
    {
        return $this->hasMany(PractitionerQualification::class, 'practitioner_id');
    }

    public function reviews()
    {
        return $this->hasMany(PractitionerReview::class);
    }

    public function getProfileBioAttribute($value)
    {
        return $value ?: ($this->short_bio ?? '');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', true)->avg('rating') ?? 0;
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
        $subtitle = $specialization[0] ?? ($this->other_modalities[0] ?? ($this->consultations[0] ?? ($this->user->role ?? 'Professional')));
        return str_replace('_', ' ', ucfirst($subtitle));
    }

    public function getExpertisesListAttribute()
    {
        $list = array_merge(
            (array) ($this->health_conditions_treated ?? []),
            (array) ($this->specialization ?? []),
            (array) ($this->body_therapies ?? []),
            (array) ($this->consultations ?? []),
            (array) ($this->other_modalities ?? [])
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

