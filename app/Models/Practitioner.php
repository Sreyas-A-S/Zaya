<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Practitioner extends Model
{
    use HasFactory;

    protected $table = 'practitioners';
    protected $guarded = [];

    protected $casts = [
        'consultations' => 'array',
        'body_therapies' => 'array',
        'other_modalities' => 'array',
        'languages_spoken' => 'array',
        'dob' => 'date',
        'can_translate_english' => 'boolean',
        'social_links' => 'array',
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
}
