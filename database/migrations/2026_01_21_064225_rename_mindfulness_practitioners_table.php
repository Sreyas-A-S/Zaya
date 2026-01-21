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
        Schema::rename('mindfulness_practitioners', 'mindfulness_counsellors');
        \Illuminate\Support\Facades\DB::table('users')
            ->where('role', 'mindfulness_practitioner')
            ->update(['role' => 'mindfulness_counsellor']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::rename('mindfulness_counsellors', 'mindfulness_practitioners');
        \Illuminate\Support\Facades\DB::table('users')
            ->where('role', 'mindfulness_counsellor')
            ->update(['role' => 'mindfulness_practitioner']);
    }
};
