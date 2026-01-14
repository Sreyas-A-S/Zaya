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
        // 1. Fix Doctor
        Schema::dropIfExists('doctors');
        Schema::dropIfExists('doctor_profiles');
        Schema::create('doctors', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');

            // Personal Details
            $table->string('full_name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('city_state')->nullable();
            $table->string('profile_photo_path')->nullable();

            // Medical Registration
            $table->string('ayush_registration_number')->nullable();
            $table->string('state_ayurveda_council_name')->nullable();
            $table->string('reg_certificate_path')->nullable();
            $table->string('digital_signature_path')->nullable();

            // Qualifications & Experience
            $table->string('primary_qualification')->nullable();
            $table->string('post_graduation')->nullable();
            $table->json('specialization')->nullable();
            $table->json('degree_certificates_path')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('current_workplace_clinic_name')->nullable();
            $table->text('clinic_address')->nullable();

            // Consultation Expertise
            $table->json('consultation_expertise')->nullable();

            // Health Conditions Treated
            $table->json('health_conditions_treated')->nullable();

            // Therapy Skills
            $table->boolean('panchakarma_consultation')->default(false);
            $table->json('panchakarma_procedures')->nullable();
            $table->json('external_therapies')->nullable();

            // Consultation Setup
            $table->json('consultation_modes')->nullable();
            $table->json('languages_spoken')->nullable();

            // KYC & Payment Details
            $table->string('pan_number')->nullable();
            $table->string('pan_upload_path')->nullable();
            $table->string('aadhaar_upload_path')->nullable();
            $table->string('bank_account_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('cancelled_cheque_path')->nullable();
            $table->string('upi_id')->nullable();

            // Platform Profile
            $table->text('short_doctor_bio')->nullable();
            $table->text('key_expertise')->nullable();
            $table->text('services_offered')->nullable();
            $table->text('awards_recognitions')->nullable();
            $table->json('social_links')->nullable();

            // Declaration & Consent
            $table->boolean('ayush_registration_confirmed')->default(false);
            $table->boolean('ayush_guidelines_agreed')->default(false);
            $table->boolean('document_verification_consented')->default(false);
            $table->boolean('policies_agreed')->default(false);
            $table->boolean('prescription_understanding_agreed')->default(false);
            $table->boolean('confidentiality_consented')->default(false);

            $table->timestamps();
        });

        // 2. Fix Practitioner
        Schema::dropIfExists('practitioners');
        Schema::dropIfExists('practitioner_profiles');
        Schema::create('practitioners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');

            // Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->text('residential_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('website_url')->nullable();

            // Professional Practice Details
            $table->json('consultations')->nullable();
            $table->json('body_therapies')->nullable();
            $table->json('other_modalities')->nullable();

            // Additional Information
            $table->text('additional_courses')->nullable();
            $table->json('languages_spoken')->nullable();
            $table->boolean('can_translate_english')->default(false);

            // Website Profile
            $table->text('profile_bio')->nullable();

            // Required Documents
            $table->string('doc_cover_letter')->nullable();
            $table->string('doc_certificates')->nullable();
            $table->string('doc_experience')->nullable();
            $table->string('doc_registration')->nullable();
            $table->string('doc_ethics')->nullable();
            $table->string('doc_contract')->nullable();
            $table->string('doc_id_proof')->nullable();

            $table->timestamps();
        });

        // 3. Ensure Qualifications table exists for Practitioner
        Schema::dropIfExists('practitioner_qualifications');
        Schema::create('practitioner_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practitioner_id')->constrained('practitioners')->onDelete('cascade');
            $table->string('year_of_passing')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('training_diploma_title')->nullable();
            $table->string('training_duration_online_hours')->nullable();
            $table->string('training_duration_contact_hours')->nullable();
            $table->string('institute_postal_address')->nullable();
            $table->string('institute_contact_details')->nullable();
            $table->timestamps();
        });

        // 4. Fix Patients
        Schema::dropIfExists('patients');
        Schema::dropIfExists('patient_profiles');
        Schema::create('patients', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('city_state')->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patients');
        Schema::dropIfExists('practitioner_qualifications');
        Schema::dropIfExists('practitioners');
        Schema::dropIfExists('doctors');
    }
};