<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('consultation_forms', function (Blueprint $table) {
            if (!Schema::hasColumn('consultation_forms', 'title')) {
                $table->string('title')->nullable()->after('doctor_id');
            }
            
            // Check if the unique index exists before dropping it
            $uniqueIndex = 'consultation_forms_booking_id_doctor_id_unique';
            $hasUnique = count(DB::select("SHOW INDEX FROM consultation_forms WHERE Key_name = ?", [$uniqueIndex])) > 0;
            
            if ($hasUnique) {
                $table->dropUnique($uniqueIndex);
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultation_forms', function (Blueprint $table) {
            if (Schema::hasColumn('consultation_forms', 'title')) {
                $table->dropColumn('title');
            }
            // We don't necessarily want to re-add the unique constraint if it was already dropped by another migration
        });
    }
};
