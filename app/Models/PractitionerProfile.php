<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class PractitionerProfile extends Model
{
    use HasFactory;

    protected $guarded = [];

    protected $casts = [
        'consultations' => 'array',
        'body_therapies' => 'array',
        'other_modalities' => 'array',
        'can_translate_english' => 'boolean',
        'declaration_agreed' => 'boolean',
        'consent_agreed' => 'boolean',
        'signed_date' => 'date',
        'dob' => 'date',
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qualifications()
    {
        return $this->hasMany(PractitionerQualification::class);
    }
}