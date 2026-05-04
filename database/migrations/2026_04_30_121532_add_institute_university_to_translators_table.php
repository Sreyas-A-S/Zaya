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
        Schema::table('translators', function (Blueprint $table) {
            $table->string('institute_university')->nullable()->after('highest_education');
            $table->string('year_of_passing')->nullable()->after('institute_university');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('translators', function (Blueprint $table) {
            $table->dropColumn(['institute_university', 'year_of_passing']);
        });
    }
};
