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
              $table->foreignId('national_id')
              ->nullable()
              ->after('email')
              ->constrained('countries')
              ->onDelete('cascade');

        // Language Foreign Key
            $table->json('languages')
                ->nullable()
                ->after('national_id');

        // Status Column
            $table->tinyInteger('status')
              ->default(0)
              ->after('languages')
              ->comment('0 = Pending, 1 = Approved');
    });
    }

    /**
     * Reverse the migrations.
     */
   public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {

            $table->dropForeign(['national_id']);
            $table->dropColumn(['national_id', 'languages', 'status']);
        });
    }
};
