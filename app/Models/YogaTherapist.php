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
        'slug',
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
        'upi_id',
        'cancelled_cheque_path',
        'status',
    ];

    protected $casts = [
        'dob' => 'date',
        'website_social_links' => 'array',
        'certificates_path' => 'array',
        'areas_of_expertise' => 'array',
        'consultation_modes' => 'array',
        'languages_spoken' => 'array',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }
}
