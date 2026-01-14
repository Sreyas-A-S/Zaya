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
        Schema::create('doctor_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');
            
            // A. Personal Details
            $table->string('full_name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->string('city_state')->nullable();
            $table->string('profile_photo_path')->nullable();

            // B. Medical Registration
            $table->string('ayush_registration_number')->nullable();
            $table->string('state_ayurveda_council_name')->nullable();
            $table->string('reg_certificate_path')->nullable();
            $table->string('digital_signature_path')->nullable();

            // C. Qualifications & Experience
            $table->string('primary_qualification')->nullable();
            $table->string('primary_qualification_other')->nullable();
            $table->string('post_graduation')->nullable();
            $table->string('post_graduation_other')->nullable();
            $table->json('specializations')->nullable();
            $table->json('degree_certificates_path')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('current_workplace_clinic_name')->nullable();
            $table->text('clinic_address')->nullable();

            // D. Ayurveda Consultation Expertise
            $table->json('consultation_skills')->nullable();

            // E. Health Conditions Treated
            $table->json('health_conditions_treated')->nullable();

            // F. Therapy Skills
            $table->boolean('panchakarma_consultation')->default(false);
            $table->json('panchakarma_procedures')->nullable();
            $table->json('external_therapies')->nullable();

            // G. Consultation Setup
            $table->json('consultation_modes')->nullable();
            $table->json('languages_spoken')->nullable();

            // H. KYC & Payment Details
            $table->string('pan_number')->nullable();
            $table->string('pan_path')->nullable();
            $table->string('aadhaar_path')->nullable();
            $table->string('bank_account_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('cancelled_cheque_path')->nullable();
            $table->string('upi_id')->nullable();

            // I. Platform Profile
            $table->text('short_doctor_bio')->nullable();
            $table->text('key_expertise')->nullable();
            $table->text('services_offered')->nullable();
            $table->text('awards_recognitions')->nullable();
            $table->json('social_links')->nullable();

            // J. Declaration & Consent
            $table->boolean('ayush_registration_confirmed')->default(false);
            $table->boolean('ayush_guidelines_agreed')->default(false);
            $table->boolean('document_verification_consented')->default(false);
            $table->boolean('policies_agreed')->default(false);
            $table->boolean('prescription_understanding_agreed')->default(false);
            $table->boolean('confidentiality_consented')->default(false);
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('doctor_profiles');
    }
};