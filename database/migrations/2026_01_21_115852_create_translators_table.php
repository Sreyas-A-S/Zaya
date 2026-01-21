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
        Schema::create('translators', function (Blueprint $table) {
            $table->id();
            $table->foreignId('user_id')->constrained()->onDelete('cascade');
            $table->string('status')->default('pending'); // active, pending, suspended

            // Personal
            $table->string('full_name');
            $table->string('gender')->nullable();
            $table->date('dob')->nullable();
            $table->string('phone')->nullable();
            $table->text('address')->nullable();
            $table->string('profile_photo_path')->nullable();

            // Language Details
            $table->string('native_language')->nullable();
            $table->json('source_languages')->nullable();
            $table->json('target_languages')->nullable();
            $table->json('additional_languages')->nullable();

            // Professional Details
            $table->string('translator_type')->nullable(); // Freelance, Agency, etc.
            $table->integer('years_of_experience')->nullable();
            $table->json('fields_of_specialization')->nullable(); // Array from Master Data
            $table->text('previous_clients_projects')->nullable();
            $table->string('portfolio_link')->nullable();

            // Qualifications
            $table->string('highest_education')->nullable();
            $table->text('certification_details')->nullable();
            $table->json('certificates_path')->nullable(); // Array of paths
            $table->json('sample_work_path')->nullable(); // Array of paths

            // Services
            $table->json('services_offered')->nullable(); // Array from Master Data

            // Identity & Payment
            $table->string('gov_id_type')->nullable();
            $table->string('gov_id_upload_path')->nullable();
            $table->string('pan_number')->nullable();
            $table->string('bank_holder_name')->nullable();
            $table->string('bank_name')->nullable();
            $table->string('account_number')->nullable();
            $table->string('ifsc_code')->nullable();
            $table->string('swift_code')->nullable();
            $table->string('upi_id')->nullable();
            $table->string('cancelled_cheque_path')->nullable();

            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('translators');
    }
};
