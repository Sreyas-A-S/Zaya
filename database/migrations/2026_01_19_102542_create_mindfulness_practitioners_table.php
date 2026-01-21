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
        Schema::create('mindfulness_practitioners', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending');

            // Personal Details
            $table->string('full_name')->nullable();
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();

            // Professional Identity
            $table->string('practitioner_type')->nullable();
            $table->integer('years_of_experience')->nullable();
            $table->string('current_workplace')->nullable();
            $table->json('website_social_links')->nullable();

            // Qualifications And Certifications
            $table->string('highest_education')->nullable();
            $table->text('mindfulness_training_details')->nullable();
            $table->json('certificates_path')->nullable(); // Can be multiple
            $table->text('additional_certifications')->nullable();

            // Areas Of Expertise
            $table->json('services_offered')->nullable();
            $table->json('client_concerns')->nullable();

            // Consultation Setup
            $table->json('consultation_modes')->nullable();
            $table->json('languages_spoken')->nullable();

            // Identity And Payment
            $table->string('gov_id_type')->nullable();
            $table->string('gov_id_upload_path')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('bank_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('cancelled_cheque_path')->nullable();

            // Platform Profile
            $table->text('short_bio')->nullable();
            $table->text('coaching_style')->nullable();
            $table->text('target_audience')->nullable(); // Who you work best with

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('mindfulness_practitioners');
    }
};
