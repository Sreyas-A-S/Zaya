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
             $table->foreignId('nationality_id')
              ->nullable()
              ->after('email')
              ->constrained('countries')
              ->onDelete('cascade');

        // Language Foreign Key
        $table->foreignId('language_id')
              ->nullable()
              ->after('nationality_id')
              ->constrained('languages')
              ->onDelete('cascade');

        // Status Column
        $table->tinyInteger('status')
              ->default(0)
              ->after('language_id')
              ->comment('0 = Pending, 1 = Approved');
    });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            //
        });
    }
};
