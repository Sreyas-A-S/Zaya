<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Factories\HasFactory;
use Illuminate\Database\Eloquent\Model;

class YogaTherapist extends Model
{
    use HasFactory;

    protected $fillable = [
        'user_id',
        'first_name',
        'last_name',
        'phone',
        'gender',
        'dob',
        'address',
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
