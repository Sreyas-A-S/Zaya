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
        // Check for and drop foreign key safely
        $foreignKeys = Schema::getForeignKeys('users');
        $fkExists = collect($foreignKeys)->contains(function ($fk) {
            return $fk['name'] === 'users_national_id_foreign';
        });

        if ($fkExists) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropForeign(['national_id']);
            });
        }

        // Check for and drop associated index safely
        $indexes = Schema::getIndexes('users');
        $idxExists = collect($indexes)->contains(function ($idx) {
            return $idx['name'] === 'users_national_id_foreign';
        });

        if ($idxExists) {
            Schema::table('users', function (Blueprint $table) {
                $table->dropIndex('users_national_id_foreign');
            });
        }

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
