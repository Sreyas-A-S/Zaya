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

        if ($hasUnique) {
            Schema::table('consultation_forms', function (Blueprint $table) use ($uniqueIndex) {
                // Drop foreign keys first because they might be using the unique index
                try {
                    $table->dropForeign(['booking_id']);
                } catch (\Exception $e) {}
                
                try {
                    $table->dropForeign(['doctor_id']);
                } catch (\Exception $e) {}

                // Now drop the unique index
                $table->dropUnique($uniqueIndex);

                // Re-add foreign keys (this will automatically create non-unique indexes)
                $table->foreign('booking_id')->references('id')->on('bookings')->cascadeOnDelete();
                $table->foreign('doctor_id')->references('id')->on('users')->cascadeOnDelete();
            });
        }
    }

    public function down(): void
    {
        Schema::table('consultation_forms', function (Blueprint $table) {
            $table->unique(['booking_id', 'doctor_id']);
        });
    }
};
