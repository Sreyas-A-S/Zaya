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
    ];

    public function user()
    {
        return $this->belongsTo(User::class);
    }

    public function qualifications()
    {
        return $this->hasMany(PractitionerQualification::class, 'practitioner_id');
    }
}