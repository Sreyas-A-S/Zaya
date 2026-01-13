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
        Schema::create('practitioner_qualifications', function (Blueprint $table) {
            $table->id();
            $table->foreignId('practitioner_profile_id')->constrained()->onDelete('cascade');
            
            // C. Training & Qualifications
            $table->string('year_passing')->nullable();
            $table->string('institute_name')->nullable();
            $table->string('course_title')->nullable();
            $table->string('duration')->nullable();
            $table->integer('online_hours')->nullable();
            $table->integer('contact_hours')->nullable();
            $table->text('institute_address')->nullable();
            $table->string('institute_contact')->nullable();
            
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('practitioner_qualifications');
    }
};