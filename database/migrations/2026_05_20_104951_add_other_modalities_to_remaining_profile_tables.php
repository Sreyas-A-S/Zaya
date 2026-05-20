<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->json('other_modalities')->nullable()->after('specialization');
        });

        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->json('other_modalities')->nullable()->after('practitioner_type');
        });

        Schema::table('yoga_therapists', function (Blueprint $table) {
            $table->json('other_modalities')->nullable()->after('areas_of_expertise');
        });

        Schema::table('translators', function (Blueprint $table) {
            $table->json('other_modalities')->nullable()->after('fields_of_specialization');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('doctors', function (Blueprint $table) {
            $table->dropColumn('other_modalities');
        });

        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->dropColumn('other_modalities');
        });

        Schema::table('yoga_therapists', function (Blueprint $table) {
            $table->dropColumn('other_modalities');
        });

        Schema::table('translators', function (Blueprint $table) {
            $table->dropColumn('other_modalities');
        });
    }
};
