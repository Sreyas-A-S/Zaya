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
        Schema::create('patient_profiles', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            
            // A. Personal Information
            $table->date('dob')->nullable();
            $table->integer('age')->nullable();
            $table->string('gender')->nullable();
            $table->string('occupation')->nullable();
            $table->text('address')->nullable();
            $table->string('mobile_country_code')->nullable();
            $table->string('mobile_number')->nullable();
            
            // B. Consultation Preferences (JSON)
            $table->json('consultation_preferences')->nullable();
            
            // C. Language & Referral Details
            $table->json('languages_spoken')->nullable();
            $table->string('referral_type')->nullable();
            $table->string('referrer_name')->nullable();
            
            // D. System-Generated Information
            $table->string('client_id')->unique()->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_profiles');
    }
};