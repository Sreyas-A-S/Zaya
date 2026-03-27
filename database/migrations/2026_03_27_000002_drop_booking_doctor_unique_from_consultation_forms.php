<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        $indexName = 'consultation_forms_booking_id_doctor_id_unique';
        $results = DB::select("SHOW INDEX FROM consultation_forms WHERE Key_name = ?", [$indexName]);
        
        if (count($results) > 0) {
            Schema::table('consultation_forms', function (Blueprint $table) use ($indexName) {
                $table->dropUnique($indexName);
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
