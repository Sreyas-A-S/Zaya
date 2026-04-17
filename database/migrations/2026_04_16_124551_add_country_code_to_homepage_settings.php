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
        Schema::table('homepage_settings', function (Blueprint $table) {
            if (!Schema::hasColumn('homepage_settings', 'country_code')) {
                $table->string('country_code', 10)->default('all')->after('language');
            }
        });

        // Drop the old unique index if it exists
        $indexExists = collect(DB::select("SHOW INDEX FROM homepage_settings"))
            ->contains('Key_name', 'homepage_settings_key_language_unique');

        if ($indexExists) {
            Schema::table('homepage_settings', function (Blueprint $table) {
                $table->dropUnique('homepage_settings_key_language_unique');
            });
        }

        // Add new unique index including country_code
        Schema::table('homepage_settings', function (Blueprint $table) {
            $table->unique(['key', 'language', 'country_code'], 'hp_settings_key_lang_country_unique');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('homepage_settings', function (Blueprint $table) {
            $table->dropUnique('hp_settings_key_lang_country_unique');
            $table->unique(['key', 'language'], 'homepage_settings_key_language_unique');
            $table->dropColumn('country_code');
        });
    }
};
