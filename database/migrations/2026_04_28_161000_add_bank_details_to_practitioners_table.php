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
        if (Schema::hasTable('practitioners')) {
            Schema::table('practitioners', function (Blueprint $table) {
                if (!Schema::hasColumn('practitioners', 'bank_account_holder_name')) {
                    $table->string('bank_account_holder_name')->nullable()->after('doc_id_proof');
                }
                if (!Schema::hasColumn('practitioners', 'bank_name')) {
                    $table->string('bank_name')->nullable()->after('bank_account_holder_name');
                }
                if (!Schema::hasColumn('practitioners', 'account_number')) {
                    $table->string('account_number')->nullable()->after('bank_name');
                }
                if (!Schema::hasColumn('practitioners', 'ifsc_code')) {
                    $table->string('ifsc_code')->nullable()->after('account_number');
                }
                if (!Schema::hasColumn('practitioners', 'cancelled_cheque_path')) {
                    $table->string('cancelled_cheque_path')->nullable()->after('ifsc_code');
                }
            });
        }
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        if (Schema::hasTable('practitioners')) {
            Schema::table('practitioners', function (Blueprint $table) {
                $columns = ['bank_account_holder_name', 'bank_name', 'account_number', 'ifsc_code', 'cancelled_cheque_path'];
                foreach ($columns as $column) {
                    if (Schema::hasColumn('practitioners', $column)) {
                        $table->dropColumn($column);
                    }
                }
            });
        }
    }
};
