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
        $tables = [
            'doctors',
            'practitioners',
            'mindfulness_practitioners',
            'yoga_therapists',
            'patients',
            'translators'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'address_line_1')) {
                        $table->string('address_line_1')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'address_line_2')) {
                        $table->string('address_line_2')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'city')) {
                        $table->string('city')->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'state')) {
                        $table->string('state', 100)->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'zip_code')) {
                        $table->string('zip_code', 20)->nullable();
                    }
                    if (!Schema::hasColumn($tableName, 'country')) {
                        $table->string('country', 100)->nullable()->default('India');
                    }
                });
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        $tables = [
            'doctors',
            'practitioners',
            'mindfulness_practitioners',
            'yoga_therapists',
            'patients',
            'translators'
        ];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn([
                        'address_line_1',
                        'address_line_2',
                        'city',
                        'state',
                        'zip_code',
                        'country'
                    ]);
                });
            }
        }
    }
};
