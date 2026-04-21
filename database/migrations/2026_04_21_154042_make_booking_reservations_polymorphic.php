<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        $tableName = 'booking_reservations';
        
        // 1. Drop old index if exists
        $indexes = DB::select("SHOW INDEX FROM {$tableName}");
        $hasOldIndex = false;
        foreach($indexes as $idx) {
            if ($idx->Key_name === 'br_p_date_time_status_idx') $hasOldIndex = true;
        }
        
        if ($hasOldIndex) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->dropIndex('br_p_date_time_status_idx');
            });
        }

        // 2. Fix columns and add new index
        Schema::table($tableName, function (Blueprint $table) {
            if (Schema::hasColumn('booking_reservations', 'practitioner_id')) {
                $table->renameColumn('practitioner_id', 'profile_id');
            }

            if (!Schema::hasColumn('booking_reservations', 'practitioner_type')) {
                $table->string('practitioner_type', 100)->after('user_id')->nullable();
            }
        });
        
        // 3. Add new index if not exists
        $indexes = DB::select("SHOW INDEX FROM {$tableName}");
        $hasNewIndex = false;
        foreach($indexes as $idx) {
            if ($idx->Key_name === 'br_poly_idx') $hasNewIndex = true;
        }
        
        if (!$hasNewIndex) {
            Schema::table($tableName, function (Blueprint $table) {
                $table->index(['profile_id', 'practitioner_type', 'booking_date'], 'br_poly_idx');
            });
        }
        
        DB::table($tableName)->whereNull('practitioner_type')->update(['practitioner_type' => 'App\Models\Practitioner']);
    }

    public function down(): void
    {
        // Not strictly needed for fixing fast, but good practice
    }
};
