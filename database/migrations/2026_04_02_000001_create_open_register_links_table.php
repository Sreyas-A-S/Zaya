<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::create('open_register_links', function (Blueprint $table) {
            $table->id();
            $table->string('role', 100);
            $table->string('token', 128)->unique();
            $table->foreignId('created_by')->nullable()->constrained('users')->nullOnDelete();
            $table->timestamp('expires_at')->nullable();
            $table->timestamp('used_at')->nullable();
            $table->timestamps();

            $table->index(['role', 'expires_at']);
        });
    }

    public function down(): void
    {
        Schema::dropIfExists('open_register_links');
    }
};

