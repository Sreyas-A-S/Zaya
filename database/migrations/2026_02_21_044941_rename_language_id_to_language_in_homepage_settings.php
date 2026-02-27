<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up()
    {
        // Get columns using raw query to be absolutely sure
        $columns = DB::select('DESCRIBE homepage_settings');
        $columnNames = array_map(function($col) { return $columnName = $col->Field ?? $col->field; }, $columns);
        
        if (in_array('language_id', $columnNames)) {
            if (!in_array('language', $columnNames)) {
                // Rename using raw SQL to avoid Laravel's automatic foreign key handling
                DB::statement('ALTER TABLE homepage_settings CHANGE language_id language VARCHAR(10) NULL');
            } else {
                // Both exist? Drop the old one using raw SQL
                DB::statement('ALTER TABLE homepage_settings DROP COLUMN language_id');
            }
        } elseif (!in_array('language', $columnNames)) {
            // Neither exists? Add it
            DB::statement('ALTER TABLE homepage_settings ADD language VARCHAR(10) NULL AFTER section');
        }
        
        // Ensure it's the right type (safe to call change() if we know it exists)
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
