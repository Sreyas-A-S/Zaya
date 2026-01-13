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
        Schema::create('practitioner_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // A. Personal Information
            $table->string('first_name');
            $table->string('last_name');
            $table->string('sex')->nullable();
            $table->date('dob')->nullable();
            $table->string('nationality')->nullable();
            $table->text('residential_address')->nullable();
            $table->string('zip_code')->nullable();
            $table->string('phone')->nullable();
            $table->string('website_url')->nullable();
            
            // B. Professional Practice Details (stored as JSON)
            $table->json('consultations')->nullable(); // B1
            $table->json('body_therapies')->nullable(); // B2
            $table->json('other_modalities')->nullable(); // B3
            
            // D. Additional Education
            $table->text('additional_education')->nullable();
            
            // E. Language Proficiency
            $table->string('languages_spoken')->nullable(); // Or JSON
            $table->boolean('can_translate_english')->default(false);
            
            // F. Website Profile Details
            $table->text('profile_bio')->nullable();
            
            // G. Required Document Uploads (File paths)
            $table->string('doc_cover_letter')->nullable();
            $table->string('doc_certificates')->nullable();
            $table->string('doc_experience')->nullable();
            $table->string('doc_registration')->nullable();
            $table->string('doc_ethics')->nullable();
            $table->string('doc_contract')->nullable();
            $table->string('doc_id_proof')->nullable();
            
            // H. Declaration & Consent
            $table->boolean('declaration_agreed')->default(false);
            $table->boolean('consent_agreed')->default(false);
            $table->string('signature')->nullable();
            $table->date('signed_date')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_profiles');
    }
};