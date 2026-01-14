<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::table('practitioner_profiles', function (Blueprint $table) {
            // Drop first_name and last_name, add full_name
            $table->dropColumn(['first_name', 'last_name']);
            $table->string('full_name')->after('user_id');

            // Rename sex to gender
            $table->renameColumn('sex', 'gender');

            // Personal Details
            $table->string('city_state')->nullable()->after('residential_address');
            $table->string('profile_photo_path')->nullable()->after('zip_code');

            // Medical Registration
            $table->string('ayush_registration_number')->nullable()->after('profile_photo_path');
            $table->string('state_ayurveda_council_name')->nullable()->after('ayush_registration_number');
            $table->string('reg_certificate_path')->nullable()->after('state_ayurveda_council_name');
            $table->string('digital_signature_path')->nullable()->after('reg_certificate_path');

            // Qualifications & Experience
            $table->string('primary_qualification')->nullable()->after('digital_signature_path');
            $table->string('primary_qualification_other')->nullable()->after('primary_qualification');
            $table->string('post_graduation')->nullable()->after('primary_qualification_other');
            $table->string('post_graduation_other')->nullable()->after('post_graduation');
            $table->json('specializations')->nullable()->after('post_graduation_other');
            $table->string('degree_certificates_path')->nullable()->after('specializations');
            $table->integer('years_of_experience')->nullable()->after('degree_certificates_path');
            $table->string('current_workplace_clinic_name')->nullable()->after('years_of_experience');
            $table->text('clinic_address')->nullable()->after('current_workplace_clinic_name');

            // Ayurveda Consultation Expertise
            $table->json('consultation_skills')->nullable()->after('clinic_address');

            // Health Conditions Treated
            $table->json('health_conditions_treated')->nullable()->after('consultation_skills');

            // Therapy Skills
            $table->boolean('panchakarma_consultation')->default(false)->after('health_conditions_treated');
            $table->json('panchakarma_procedures')->nullable()->after('panchakarma_consultation');
            $table->json('external_therapies')->nullable()->after('panchakarma_procedures');

            // Consultation Setup
            $table->json('consultation_modes')->nullable()->after('external_therapies');
            // Change languages_spoken to JSON
            $table->json('languages_spoken')->nullable()->change();

            // KYC & Payment Details
            $table->string('pan_number')->nullable()->after('languages_spoken');
            $table->string('pan_path')->nullable()->after('pan_number');
            $table->string('aadhaar_path')->nullable()->after('pan_path');
            $table->string('bank_account_holder_name')->nullable()->after('aadhaar_path');
            $table->string('bank_name')->nullable()->after('bank_account_holder_name');
            $table->string('account_number')->nullable()->after('bank_name');
            $table->string('ifsc_code')->nullable()->after('account_number');
            $table->string('cancelled_cheque_path')->nullable()->after('ifsc_code');
            $table->string('upi_id')->nullable()->after('cancelled_cheque_path');

            // Platform Profile
            $table->renameColumn('profile_bio', 'short_doctor_bio');
            $table->text('key_expertise')->nullable()->after('short_doctor_bio');
            $table->text('services_offered')->nullable()->after('key_expertise');
            $table->text('awards_recognitions')->nullable()->after('services_offered');
            $table->json('social_links')->nullable()->after('awards_recognitions');

            // Declaration & Consent
            $table->boolean('ayush_registration_confirmed')->default(false)->after('signature');
            $table->boolean('ayush_guidelines_agreed')->default(false)->after('ayush_registration_confirmed');
            $table->boolean('document_verification_consented')->default(false)->after('ayush_guidelines_agreed');
            $table->boolean('policies_agreed')->default(false)->after('document_verification_consented');
            $table->boolean('prescription_understanding_agreed')->default(false)->after('policies_agreed');
            $table->boolean('confidentiality_consented')->default(false)->after('prescription_understanding_agreed');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('practitioner_profiles', function (Blueprint $table) {
            // Reverse of Personal Details
            $table->string('first_name')->nullable();
            $table->string('last_name')->nullable();
            $table->dropColumn('full_name');
            $table->renameColumn('gender', 'sex');
            $table->dropColumn(['city_state', 'profile_photo_path']);

            // Reverse of Medical Registration
            $table->dropColumn([
                'ayush_registration_number',
                'state_ayurveda_council_name',
                'reg_certificate_path',
                'digital_signature_path',
            ]);

            // Reverse of Qualifications & Experience
            $table->dropColumn([
                'primary_qualification',
                'primary_qualification_other',
                'post_graduation',
                'post_graduation_other',
                'specializations',
                'degree_certificates_path',
                'years_of_experience',
                'current_workplace_clinic_name',
                'clinic_address',
            ]);

            // Reverse of Ayurveda Consultation Expertise
            $table->dropColumn('consultation_skills');

            // Reverse of Health Conditions Treated
            $table->dropColumn('health_conditions_treated');

            // Reverse of Therapy Skills
            $table->dropColumn([
                'panchakarma_consultation',
                'panchakarma_procedures',
                'external_therapies',
            ]);

            // Reverse of Consultation Setup
            $table->dropColumn('consultation_modes');
            // Change languages_spoken back to string
            $table->string('languages_spoken')->nullable()->change();

            // Reverse of KYC & Payment Details
            $table->dropColumn([
                'pan_number',
                'pan_path',
                'aadhaar_path',
                'bank_account_holder_name',
                'bank_name',
                'account_number',
                'ifsc_code',
                'cancelled_cheque_path',
                'upi_id',
            ]);

            // Reverse of Platform Profile
            $table->renameColumn('short_doctor_bio', 'profile_bio');
            $table->dropColumn([
                'key_expertise',
                'services_offered',
                'awards_recognitions',
                'social_links',
            ]);

            // Reverse of Declaration & Consent
            $table->dropColumn([
                'ayush_registration_confirmed',
                'ayush_guidelines_agreed',
                'document_verification_consented',
                'policies_agreed',
                'prescription_understanding_agreed',
                'confidentiality_consented',
            ]);
        });
    }
};
