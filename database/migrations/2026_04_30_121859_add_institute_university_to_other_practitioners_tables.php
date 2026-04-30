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
        Schema::table('yoga_therapists', function (Blueprint $table) {
            $table->string('highest_education')->nullable()->after('website_social_links');
            $table->string('institute_university')->nullable()->after('highest_education');
            $table->string('year_of_passing')->nullable()->after('institute_university');
        });

        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->string('institute_university')->nullable()->after('highest_education');
            $table->string('year_of_passing')->nullable()->after('institute_university');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('yoga_therapists', function (Blueprint $table) {
            $table->dropColumn(['highest_education', 'institute_university', 'year_of_passing']);
        });

        Schema::table('mindfulness_practitioners', function (Blueprint $table) {
            $table->dropColumn(['institute_university', 'year_of_passing']);
        });
    }
};
