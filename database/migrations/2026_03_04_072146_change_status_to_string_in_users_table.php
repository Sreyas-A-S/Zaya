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
        // First convert current data to strings if possible, 
        // but tinyInteger doesn't hold strings. 
        // We'll change the column type and then update values.
        Schema::table('users', function (Blueprint $table) {
            $table->string('status')->default('pending')->change();
        });

        // Update existing numeric statuses to strings
        \Illuminate\Support\Facades\DB::table('users')->where('status', '0')->update(['status' => 'pending']);
        \Illuminate\Support\Facades\DB::table('users')->where('status', '1')->update(['status' => 'active']);
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('users', function (Blueprint $table) {
            $table->tinyInteger('status')->default(0)->comment('0 = Pending, 1 = Approved')->change();
        });
    }
};
