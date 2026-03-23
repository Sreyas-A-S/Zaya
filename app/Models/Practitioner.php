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
        'status',
        'booking_window_days',
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
    ];

    protected $casts = [
        'consultations' => 'array',
        'body_therapies' => 'array',
        'other_modalities' => 'array',
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

    public function qualifications()
    {
        return $this->hasMany(PractitionerQualification::class, 'practitioner_id');
    }
    public function reviews()
    {
        return $this->hasMany(PractitionerReview::class);
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
        return $this->city ?: ($this->state ?: 'Location not set');
    }
}
