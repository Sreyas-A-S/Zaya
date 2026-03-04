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
            // Drop foreign key and its associated index
            $table->dropForeign(['national_id']);
            $table->dropIndex('users_national_id_foreign');
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
            $table->foreign('national_id')->references('id')->on('countries')->onDelete('cascade');
        });
    }
};
