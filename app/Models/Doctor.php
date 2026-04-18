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
}
