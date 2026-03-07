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
        Schema::table('users', function (Blueprint $table) {
            // Drop foreign key and its associated index safely
            try {
                $table->dropForeign(['national_id']);
            } catch (\Exception $e) {
                // Ignore if it doesn't exist
            }

            try {
                $table->dropIndex('users_national_id_foreign');
            } catch (\Exception $e) {
                // Ignore if it doesn't exist
            }
        });

        Schema::table('users', function (Blueprint $table) {
            // Change column to JSON
            $table->json('national_id')->nullable()->change();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->unsignedBigInteger('national_id')->nullable()->change();
            
            // Check if countries table exists before adding foreign key
            if (Schema::hasTable('countries')) {
                try {
                    $table->foreign('national_id')->references('id')->on('countries')->onDelete('cascade');
                } catch (\Exception $e) {
                    // Ignore if it already exists or can't be added
                }
            }
        });
    }
};
