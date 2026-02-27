<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up()
    {
        // 1. If language_id exists, rename it to language
        if (Schema::hasColumn('homepage_settings', 'language_id')) {
            Schema::table('homepage_settings', function (Blueprint $table) {
                // We wrap this in a try-catch because dropForeign can be very finicky with index names
                try {
                    $table->dropForeign(['language_id']);
                } catch (\Exception $e) {
                    // Ignore if foreign key doesn't exist
                }
                
                // Rename column
                $table->renameColumn('language_id', 'language');
            });
        } 
        // 2. If neither language nor language_id exists, add language
        elseif (!Schema::hasColumn('homepage_settings', 'language')) {
            Schema::table('homepage_settings', function (Blueprint $table) {
                $table->string('language', 10)->nullable()->after('section');
            });
        }

        // 3. Now that we are sure 'language' exists (or we just added it), ensure it's the right type
        if (Schema::hasColumn('homepage_settings', 'language')) {
            Schema::table('homepage_settings', function (Blueprint $table) {
                $table->string('language', 10)->nullable()->change();
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('homepage_settings', 'language')) {
            Schema::table('homepage_settings', function (Blueprint $table) {
                $table->renameColumn('language', 'language_id');
            });
        }
    }
};
