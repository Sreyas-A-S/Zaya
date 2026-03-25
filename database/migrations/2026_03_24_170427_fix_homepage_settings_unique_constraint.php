<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Check if the unique index exists before trying to drop it
        $indexExists = collect(DB::select("SHOW INDEX FROM homepage_settings"))
            ->contains('Key_name', 'homepage_settings_key_unique');

        if ($indexExists) {
            Schema::table('homepage_settings', function (Blueprint $table) {
                $table->dropUnique('homepage_settings_key_unique');
            });
        }

        Schema::table('homepage_settings', function (Blueprint $table) {
            // Check if the new composite index already exists to avoid duplicate index errors
            $compositeExists = collect(DB::select("SHOW INDEX FROM homepage_settings"))
                ->contains('Key_name', 'homepage_settings_key_language_unique');

            if (!$compositeExists) {
                $table->unique(['key', 'language']);
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_settings', function (Blueprint $table) {
            $table->dropUnique(['key', 'language']);
            $table->unique('key');
        });
    }
};
