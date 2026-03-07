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
        if (!Schema::hasColumn('users', 'national_id')) {
            Schema::table('users', function (Blueprint $table) {
                $table->foreignId('national_id')
                    ->nullable()
                    ->after('email')
                    ->constrained('countries')
                    ->onDelete('cascade');
            });
        } else {
            Schema::table('users', function (Blueprint $table) {
                // Ensure it's the right type and add constraint
                $table->unsignedBigInteger('national_id')->nullable()->change();
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
