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
            'doctors' => 'clinic_address',
            'practitioners' => 'residential_address',
            'patients' => 'address',
            'mindfulness_practitioners' => 'address',
            'yoga_therapists' => 'address',
            'translators' => 'address',
        ];

        foreach ($tables as $table => $oldField) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableGroup) use ($table, $oldField) {
                    $newColumns = ['address_line_1', 'address_line_2', 'city', 'state', 'zip_code', 'country'];

                    foreach ($newColumns as $index => $column) {
                        if (!Schema::hasColumn($table, $column)) {
                            $after = $index === 0 ? $oldField : $newColumns[$index - 1];
                            if (Schema::hasColumn($table, $after)) {
                                $tableGroup->string($column)->nullable()->after($after);
                            } else {
                                $tableGroup->string($column)->nullable();
                            }
                        }
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
            'patients',
            'mindfulness_practitioners',
            'yoga_therapists',
            'translators',
        ];

        foreach ($tables as $table) {
            if (Schema::hasTable($table)) {
                Schema::table($table, function (Blueprint $tableGroup) {
                    $tableGroup->dropColumn(['address_line_1', 'address_line_2', 'city', 'state', 'zip_code', 'country']);
                });
            }
        }
    }
};
