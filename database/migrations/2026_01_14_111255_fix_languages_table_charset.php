<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        // Forcing the table to use utf8mb4 to support emojis
        DB::statement('ALTER TABLE languages CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci');
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Optional: revert back if needed, though utf8mb4 is generally preferred
        DB::statement('ALTER TABLE languages CONVERT TO CHARACTER SET utf8 COLLATE utf8_unicode_ci');
    }
};