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
        if (Schema::hasTable('homepage_settings')) {
            if (!Schema::hasColumn('homepage_settings', 'max_length')) {
                Schema::table('homepage_settings', function (Blueprint $table) {
                    $table->integer('max_length')->nullable()->after('section');
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('homepage_settings')) {
            if (Schema::hasColumn('homepage_settings', 'max_length')) {
                Schema::table('homepage_settings', function (Blueprint $table) {
                    $table->dropColumn('max_length');
                });
            }
        }
    }
};
