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
        $tables = ['doctors', 'practitioners', 'mindfulness_practitioners', 'translators', 'yoga_therapists'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'swift_code')) {
                        if (Schema::hasColumn($tableName, 'ifsc_code')) {
                            $table->string('swift_code', 20)->nullable()->after('ifsc_code');
                        } elseif (Schema::hasColumn($tableName, 'account_number')) {
                            $table->string('swift_code', 20)->nullable()->after('account_number');
                        } else {
                            $table->string('swift_code', 20)->nullable();
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
        $tables = ['doctors', 'practitioners', 'mindfulness_practitioners', 'translators', 'yoga_therapists'];

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (Schema::hasColumn($tableName, 'swift_code')) {
                        $table->dropColumn('swift_code');
                    }
                });
            }
        }
    }
};
