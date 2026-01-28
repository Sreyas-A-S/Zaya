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
        // Rename table
        if (Schema::hasTable('mindfulness_counsellors') && !Schema::hasTable('mindfulness_practitioners')) {
            Schema::rename('mindfulness_counsellors', 'mindfulness_practitioners');
        }

        // Update roles
        DB::table('users')
            ->where('role', 'mindfulness_counsellor')
            ->update(['role' => 'mindfulness_practitioner']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // Update roles back
        DB::table('users')
            ->where('role', 'mindfulness_practitioner')
            ->update(['role' => 'mindfulness_counsellor']);

        // Rename table back
        if (Schema::hasTable('mindfulness_practitioners') && !Schema::hasTable('mindfulness_counsellors')) {
            Schema::rename('mindfulness_practitioners', 'mindfulness_counsellors');
        }
    }
};
