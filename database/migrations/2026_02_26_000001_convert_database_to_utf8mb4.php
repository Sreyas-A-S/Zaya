<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;

return new class extends Migration
{
    public function up(): void
    {
        if (DB::getDriverName() !== 'mysql') {
            return;
        }

        $database = DB::getDatabaseName();

        DB::statement(
            "ALTER DATABASE `{$database}` CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
        );

        $tables = DB::select('SHOW TABLES');
        if (empty($tables)) {
            return;
        }

        $firstRow = (array) $tables[0];
        $key = array_key_first($firstRow);

        foreach ($tables as $row) {
            $row = (array) $row;
            if (!isset($row[$key])) {
                continue;
            }

            $table = $row[$key];
            DB::statement(
                "ALTER TABLE `{$table}` CONVERT TO CHARACTER SET utf8mb4 COLLATE utf8mb4_unicode_ci"
            );
        }
    }

    public function down(): void
    {
        // Intentionally left as a no-op.
    }
};
