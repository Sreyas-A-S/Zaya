<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $uniqueIndex = 'consultation_forms_booking_id_doctor_id_unique';
        $newIndex = 'consultation_forms_booking_id_index';
        
        $hasUnique = count(DB::select("SHOW INDEX FROM consultation_forms WHERE Key_name = ?", [$uniqueIndex])) > 0;
        $hasNew = count(DB::select("SHOW INDEX FROM consultation_forms WHERE Key_name = ?", [$newIndex])) > 0;

        Schema::table('consultation_forms', function (Blueprint $table) use ($hasUnique, $hasNew, $uniqueIndex, $newIndex) {
            if ($hasUnique) {
                if (!$hasNew) {
                    $table->index('booking_id', $newIndex);
                }
                $table->dropUnique($uniqueIndex);
            }
        });
    }

    public function down(): void
    {
        Schema::table('consultation_forms', function (Blueprint $table) {
            $table->unique(['booking_id', 'doctor_id']);
        });
    }
};
