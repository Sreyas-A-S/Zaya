<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->string('registration_number')->nullable()->after('additional_certifications');
            $table->string('affiliated_body')->nullable()->after('registration_number');
            $table->string('registration_proof_path')->nullable()->after('affiliated_body');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->dropColumn(['registration_number', 'affiliated_body', 'registration_proof_path']);
        });
    }
};
