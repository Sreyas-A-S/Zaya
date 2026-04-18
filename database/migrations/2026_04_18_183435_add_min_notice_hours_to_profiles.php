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
        $tables = ['practitioners', 'doctors', 'mindfulness_practitioners', 'yoga_therapists'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if (!Schema::hasColumn($table, 'min_notice_hours')) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->integer('min_notice_hours')->default(1);
                    });
                }
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = ['practitioners', 'doctors', 'mindfulness_practitioners', 'yoga_therapists'];
        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                if (Schema::hasColumn($table, 'min_notice_hours')) {
                    Schema::table($table, function (Blueprint $table) {
                        $table->dropColumn('min_notice_hours');
                    });
                }
            }
        }
    }
};
