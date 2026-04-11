<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\Factories\HasFactory;

class MindfulnessPractitioner extends Model
{
    use HasFactory;

    protected static function boot()
    {
        parent::boot();
        static::creating(function ($model) {
            if (!$model->slug) {
                $name = trim(($model->first_name ?? '') . ' ' . ($model->last_name ?? ''));
                if (empty($name)) {
                    $name = ($model->user ? $model->user->name : null) ?? 'mindfulness-' . time();
                }
                $baseSlug = \Illuminate\Support\Str::slug($name);
                $slug = $baseSlug ?: 'mindfulness-' . time();
                $count = 1;
                while (\App\Models\MindfulnessPractitioner::where('slug', $slug)->exists()) {
                    $slug = $baseSlug . '-' . $count++;
                }
                $model->slug = $slug;
            }
        });
    }

    protected $table = 'mindfulness_practitioners';

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
        'practitioner_type',
        'years_of_experience',
        'current_organization',
        'workplace_address',
        'website_social_links',
        'mindfulness_training_details',
        'certificates_path',
        'additional_certifications',
        'registration_number',
        'affiliated_body',
        'registration_proof_path',
        'services_offered',
        'client_concerns',
        'consultation_modes',
        'languages_spoken',
        'short_bio',
        'approach',
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
        'services_offered' => 'array',
        'client_concerns' => 'array',
        'consultation_modes' => 'array',
        'languages_spoken' => 'array',
        'practitioner_type' => 'array',
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
        $types = $this->practitioner_type ?? [];
        if (!is_array($types)) $types = [$types];

        $subtitle = $types[0] ?? 'Mindfulness Counsellor';
        return str_replace('_', ' ', ucfirst($subtitle));
    }

    public function getExpertisesListAttribute()
    {
        $list = array_merge(
            (array) ($this->services_offered ?? []),
            (array) ($this->client_concerns ?? []),
            (array) ($this->practitioner_type ?? [])
        );
        return array_values(array_unique(array_filter($list, fn ($v) => trim((string) $v) !== '')));
    }
}
