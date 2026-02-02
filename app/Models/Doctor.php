<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class Doctor extends Model
{
    use HasFactory;

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
}
