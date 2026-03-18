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
        $tables = ['practitioners', 'doctors', 'yoga_therapists', 'mindfulness_practitioners', 'translators', 'users'];

        foreach ($tables as $table) {
            if (Schema::hasTable($table) && Schema::hasColumn($table, 'status')) {
                // Normalize 'approved' to 'active'
                DB::table($table)->whereRaw('LOWER(status) = ?', ['approved'])->update(['status' => 'active']);
                
                // Normalize 'pending', 'rejected', empty strings, or anything else to 'inactive' (except 'active')
                DB::table($table)->whereRaw('LOWER(status) NOT IN (?, ?)', ['active', 'inactive'])->update(['status' => 'inactive']);
                
                // Ensure empty status is 'inactive'
                DB::table($table)->whereNull('status')->update(['status' => 'inactive']);
                DB::table($table)->where('status', '')->update(['status' => 'inactive']);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        // No easy way to reverse normalization without original data
    }
};
