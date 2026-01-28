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

        // 1. Users Table
        Schema::table('users', function (Blueprint $table) {
            if (!Schema::hasColumn('users', 'first_name')) {
                $table->string('first_name')->nullable()->after('name');
            }
            if (!Schema::hasColumn('users', 'last_name')) {
                $table->string('last_name')->nullable()->after('first_name');
            }
        });

        // 2. Profile Tables
        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) use ($tableName) {
                    if (!Schema::hasColumn($tableName, 'first_name')) {
                        $table->string('first_name')->nullable()->after('user_id');
                    }
                    if (!Schema::hasColumn($tableName, 'last_name')) {
                        $table->string('last_name')->nullable()->after('first_name');
                    }
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

        Schema::table('users', function (Blueprint $table) {
            $table->dropColumn(['first_name', 'last_name']);
        });

        foreach ($tables as $tableName) {
            if (Schema::hasTable($tableName)) {
                Schema::table($tableName, function (Blueprint $table) {
                    $table->dropColumn([
                        'first_name',
                        'last_name',
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
