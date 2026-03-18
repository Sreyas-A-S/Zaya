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
        Schema::create('countries', function (Blueprint $table) {
            $table->id();
            $table->string('code', 5);
            $table->string('name');
            $table->string('flag')->nullable();
            $table->string('status')->default('active');
            $table->timestamps();
        });

        // Seed data immediately
        $countries = config('countries');
        if (!empty($countries)) {
            $insertData = [];
            foreach ($countries as $code => $name) {
                $insertData[] = [
                    'code' => $code,
                    'name' => $name,
                    'status' => 'active',
                    'created_at' => now(),
                    'updated_at' => now(),
                ];
            }
            foreach (array_chunk($insertData, 50) as $chunk) {
                DB::table('countries')->insert($chunk);
            }
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('countries');
    }
};
