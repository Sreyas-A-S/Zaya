<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
     public function up()
    {
        Schema::table('homepage_settings', function (Blueprint $table) {

            // Drop foreign key first (important)
            $table->dropForeign(['language_id']);

            // Rename column
            $table->renameColumn('language_id', 'language');

            // Change column type to string
            $table->string('language', 10)->nullable()->change();
             
        });
    }

    public function down()
    {
        Schema::table('homepage_settings', function (Blueprint $table) {

            $table->renameColumn('language', 'language_id');
            

            $table->unsignedBigInteger('language_id')->nullable()->change();
        });
    }
};
