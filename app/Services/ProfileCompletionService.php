<?php

namespace App\Services;

use App\Models\User;

class ProfileCompletionService
{
    /**
     * Define mandatory fields for each role based on database column names.
     */
    protected function getMandatoryFields(string $role): array
    {
        switch ($role) {
            case 'doctor':
                return [
                    'profile' => [
                        'first_name', 'last_name', 'gender', 'dob', 'phone', 'city', 'country', 
                        'primary_qualification', 'years_of_experience', 'current_workplace', 
                        'address_line_1', 'bank_holder_name', 'bank_name', 
                        'account_number', 'swift_code', 'cancelled_cheque_path', 'short_bio'
                    ],
                    'consents' => [
                        'guidelines_agreement', 'document_verification_consented', 
                        'policies_agreed', 'prescription_understanding_agreed', 'confidentiality_consented'
                    ]
                ];
            case 'practitioner':
                return [
                    'user' => ['name', 'email'],
                    'profile' => [
                        'gender', 'dob', 'nationality', 'phone', 'address_line_1', 'city', 'state', 'zip_code', 'country', 
                        'passing_year', 'institute_name', 'training_title', 'languages_spoken', 'doc_cover_letter', 
                        'doc_certificates', 'doc_experience', 'doc_registration', 'doc_ethics', 'doc_contract', 'doc_id_proof'
                    ]
                ];
            case 'mindfulness_practitioner':
            case 'mindfulness-practitioner':
                return [
                    'user' => ['name', 'email'],
                    'profile' => [
                        'profile_photo_path', 'phone', 'gender', 'dob', 'address_line_1', 'city', 'country', 
                        'practitioner_type', 'highest_education', 'mindfulness_training_details', 'certificates_path', 
                        'services_offered', 'client_concerns', 'consultation_modes', 'languages_spoken', 'short_bio',
                        'bank_holder_name', 'bank_name', 'account_number', 'swift_code', 'gov_id_upload_path'
                    ]
                ];
            case 'translator':
                return [
                    'user' => ['name', 'email'],
                    'profile' => [
                        'profile_photo_path', 'phone', 'gender', 'dob', 'address_line_1', 'city', 'country', 
                        'native_language', 'source_languages', 'target_languages', 'years_of_experience', 'fields_of_specialization', 
                        'services_offered', 'gov_id_upload_path', 'bank_holder_name', 'bank_name', 
                        'account_number', 'swift_code', 'cancelled_cheque_path'
                    ]
                ];
            case 'yoga_therapist':
            case 'yoga-therapist':
                return [
                    'user' => ['name', 'email'],
                    'profile' => [
                        'profile_photo_path', 'phone', 'gender', 'dob', 'address_line_1', 'city', 'country', 
                        'yoga_therapist_type', 'years_of_experience', 'current_organization', 'registration_number', 
                        'registration_proof_path', 'certification_details', 'certificates_path', 'areas_of_expertise', 
                        'consultation_modes', 'languages_spoken', 'short_bio', 'gov_id_upload_path', 'bank_holder_name', 
                        'bank_name', 'account_number', 'swift_code'
                    ]
                ];
            case 'client':
            case 'patient':
                return [
                    'user' => ['name', 'email'],
                    'profile' => [
                        'dob', 'gender', 'mobile_number', 'address_line_1', 'city', 'state', 'zip_code', 'country', 
                        'consultation_preferences', 'languages_spoken'
                    ]
                ];
            default:
                return [];
        }
    }

    /**
     * Get the completion status for a user.
     */
    public function getStatus(User $user): array
    {
        $role = $user->role;
        $mandatory = $this->getMandatoryFields($role);
        
        if (empty($mandatory)) {
            return ['is_complete' => true, 'percentage' => 100, 'missing' => []];
        }

        $missing = [];
        $totalFields = 0;
        $completedFields = 0;

        // Check User table fields
        if (isset($mandatory['user'])) {
            foreach ($mandatory['user'] as $field) {
                $totalFields++;
                if (!empty($user->$field)) {
                    $completedFields++;
                } else {
                    $missing[] = str_replace('_', ' ', ucfirst($field));
                }
            }
        }

        // Check Profile relationship fields
        $profile = $this->getProfileModel($user);
        if ($profile) {
            $fieldsToCheck = $mandatory['profile'] ?? $mandatory;
            foreach ($fieldsToCheck as $field) {
                if ($field === 'user' || $field === 'consents') continue;

                $totalFields++;
                try {
                    $value = $profile->$field;
                } catch (\Illuminate\Contracts\Encryption\DecryptException $e) {
                    $value = null;
                }
                
                if (is_array($value)) {
                    if (count($value) > 0) {
                        $completedFields++;
                    } else {
                        $missing[] = $this->formatFieldName($field);
                    }
                } elseif (!empty($value) || $value === 0 || $value === '0' || $value === true) {
                    $completedFields++;
                } else {
                    $missing[] = $this->formatFieldName($field);
                }
            }

            if (isset($mandatory['consents'])) {
                foreach ($mandatory['consents'] as $field) {
                    $totalFields++;
                    if ($profile->$field) {
                        $completedFields++;
                    } else {
                        $missing[] = $this->formatFieldName($field);
                    }
                }
            }
        } else {
            $fieldsToCheck = $mandatory['profile'] ?? $mandatory;
            foreach ($fieldsToCheck as $field) {
                if ($field === 'user' || $field === 'consents') continue;
                $totalFields++;
                $missing[] = $this->formatFieldName($field);
            }
            if (isset($mandatory['consents'])) {
                foreach ($mandatory['consents'] as $field) {
                    $totalFields++;
                    $missing[] = $this->formatFieldName($field);
                }
            }
        }

        $percentage = $totalFields > 0 ? round(($completedFields / $totalFields) * 100) : 100;

        return [
            'is_complete' => $percentage >= 100,
            'percentage' => (int)$percentage,
            'missing' => $missing,
            'total_missing' => count($missing)
        ];
    }

    protected function getProfileModel(User $user)
    {
        switch ($user->role) {
            case 'doctor':
                return $user->doctor;
            case 'practitioner':
                return $user->practitioner;
            case 'mindfulness_practitioner':
            case 'mindfulness-practitioner':
                return $user->mindfulnessPractitioner;
            case 'translator':
                return $user->translator;
            case 'yoga_therapist':
            case 'yoga-therapist':
                return $user->yogaTherapist;
            case 'client':
            case 'patient':
                return $user->patient;
            default:
                return null;
        }
    }

    protected function formatFieldName(string $field): string
    {
        $labels = [
            'dob' => 'Date of Birth',
            'pan_upload_path' => 'PAN Card Copy',
            'reg_certificate_path' => 'Registration Certificate',
            'digital_signature_path' => 'Digital Signature',
            'ayush_registration_confirmed' => 'AYUSH Confirmation',
            'ayush_guidelines_agreed' => 'Guidelines Agreement',
            'document_verification_consented' => 'Document Verification Consent',
            'ifsc_code' => 'IFSC Code',
            'swift_code' => 'SWIFT Code',
            'gov_id_upload_path' => 'ID Proof Upload',
            'doc_id_proof' => 'ID Proof',
            'doc_certificates' => 'Educational Certificates',
            'doc_experience' => 'Experience Certificate',
            'doc_cover_letter' => 'Cover Letter',
            'doc_registration' => 'Registration Form',
            'doc_ethics' => 'Code of Ethics',
            'doc_contract' => 'ZAYA Contract',
            'registration_proof_path' => 'Registration Proof',
            'certificates_path' => 'Professional Certificates',
        ];

        if (isset($labels[$field])) {
            return $labels[$field];
        }

        return str_replace(['_path', '_'], ['', ' '], ucfirst($field));
    }
}
