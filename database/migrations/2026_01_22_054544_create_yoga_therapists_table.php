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
        Schema::create('yoga_therapists', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');

            // Personal
            $table->string('phone')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();

            // Professional
            $table->string('yoga_therapist_type')->nullable(); // e.g., "Certified Yoga Therapist", "Yoga Instructor with Therapy Training"
            $table->integer('years_of_experience')->nullable();
            $table->string('current_organization')->nullable(); // "Current Clinic / Studio / Organization"
            $table->text('workplace_address')->nullable();
            $table->json('website_social_links')->nullable(); // JSON

            // Qualifications
            $table->text('certification_details')->nullable();
            $table->json('certificates_path')->nullable(); // JSON (array of paths)
            $table->text('additional_certifications')->nullable();

            // Registration
            $table->string('registration_number')->nullable();
            $table->string('affiliated_body')->nullable();
            $table->string('registration_proof_path')->nullable();

            // Expertise & Setup
            $table->json('areas_of_expertise')->nullable(); // JSON
            $table->json('consultation_modes')->nullable(); // JSON
            $table->json('languages_spoken')->nullable(); // JSON

            // Profile
            $table->text('short_bio')->nullable();
            $table->text('therapy_approach')->nullable();

            // Identity & Payment
            $table->string('gov_id_type')->nullable();
            $table->string('gov_id_upload_path')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('bank_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('cancelled_cheque_path')->nullable();

            $table->string('status')->default('active'); // active, inactive, pending
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('yoga_therapists');
    }
};
