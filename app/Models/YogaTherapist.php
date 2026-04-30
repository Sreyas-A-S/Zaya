<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YogaTherapist extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->slug) {
                $name = trim(($model->first_name ?? '') . ' ' . ($model->last_name ?? ''));
                if (empty($name)) {
                    $name = ($model->user ? $model->user->name : null) ?? 'yoga-' . time();
                }
                $baseSlug = \Illuminate\Support\Str::slug($name);
                $slug = $baseSlug ?: 'yoga-' . time();
                $count = 1;
                while (\App\Models\YogaTherapist::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $model->slug = $slug;
            }
        });
    }

    protected $fillable = [
        'user_id',
        'payout_currency',
        'min_notice_hours',
        'first_name',
        'last_name',
        'phone',
        'gender',
        'dob',
        'address_line_1',
        'address_line_2',
        'city',
        'state',
        'zip_code',
        'country',
        'profile_photo_path',
        'yoga_therapist_type',
        'years_of_experience',
        'current_organization',
        'workplace_address',
        'website_social_links',
        'highest_education',
        'institute_university',
        'year_of_passing',
        'certification_details',
        'certificates_path',
        'additional_certifications',
        'registration_number',
        'affiliated_body',
        'registration_proof_path',
        'areas_of_expertise',
        'consultation_modes',
        'languages_spoken',
        'short_bio',
        'therapy_approach',
        'gov_id_type',
        'gov_id_upload_path',
        'pan_number',
        'bank_holder_name',
        'bank_name',
        'account_number',
        'ifsc_code',
        'swift_code',
        'upi_id',
        'cancelled_cheque_path',
        'booking_window_days',
        'default_slot_duration',
        'reminder_lead_time',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'website_social_links' => 'array',
        'certificates_path' => 'array',
        'areas_of_expertise' => 'array',
        'consultation_modes' => 'array',
        'languages_spoken' => 'array',
        'booking_window_days' => 'integer',
        'default_slot_duration' => 'integer',
        'reminder_lead_time' => 'integer',
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
        return $this->hasMany(PractitionerReview::class, 'practitioner_id', 'id');
    }

    public function getAverageRatingAttribute()
    {
        return $this->reviews()->where('status', true)->avg('rating') ?? 0;
    }

    public function getProfileBioAttribute()
    {
        return $this->short_bio ?? '';
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
        $subtitle = $this->yoga_therapist_type ?? 'Yoga Therapist';
        return str_replace('_', ' ', ucfirst($subtitle));
    }

    public function getExpertisesListAttribute()
    {
        $list = array_merge(
            (array) ($this->areas_of_expertise ?? [])
        );
        return array_values(array_unique(array_filter($list, fn ($v) => trim((string) $v) !== '')));
    }

    public function getConditionsListAttribute()
    {
        $list = array_merge(
            (array) ($this->areas_of_expertise ?? [])
        );
        return array_values(array_unique(array_filter($list, fn ($v) => trim((string) $v) !== '')));
    }
}
