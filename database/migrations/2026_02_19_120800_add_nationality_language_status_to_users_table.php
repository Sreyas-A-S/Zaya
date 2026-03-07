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
        // Ensure users table is InnoDB
        \Illuminate\Support\Facades\DB::statement('ALTER TABLE users ENGINE=InnoDB');

        // Check if countries table exists before trying to modify it or use it as a foreign key
        $countriesExist = Schema::hasTable('countries');

        if ($countriesExist) {
            try {
                \Illuminate\Support\Facades\DB::statement('ALTER TABLE countries ENGINE=InnoDB');
            } catch (\Exception $e) {
                // Might fail due to permissions or other issues
            }
        }

        if (!Schema::hasColumn('users', 'national_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('national_id')->nullable()->after('email');
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                $table->unsignedBigInteger('national_id')->nullable()->change();
            });
        }

        // Add foreign key constraint separately to be more robust
        if ($countriesExist) {
            Schema::table('users', function (Blueprint $table) {
                try {
                    $table->foreign('national_id')->references('id')->on('countries')->onDelete('cascade');
                } catch (\Exception $e) {
                    // Constraint might already exist
                }
            });
        }

        if (!Schema::hasColumn('users', 'languages')) {
            Schema::table('users', function (Blueprint $table) {
                $table->json('languages')
                    ->nullable()
                    ->after('national_id');
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Drop foreign key if it exists
        try {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['national_id']);
            });
        } catch (\Exception $e) {
            // Ignore if it doesn't exist
        }

        Schema::table('users', function (Blueprint $table) {
            if (Schema::hasColumn('users', 'national_id')) {
                $table->dropColumn('national_id');
            }
            if (Schema::hasColumn('users', 'languages')) {
                $table->dropColumn('languages');
            }
        });
    }
};
