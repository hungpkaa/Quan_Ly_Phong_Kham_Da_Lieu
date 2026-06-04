<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        if (!Schema::hasTable('medical_records') || !Schema::hasColumn('medical_records', 'cost')) {
            return;
        }

        // Current schema uses DECIMAL(8,2) which overflows for values > 999999.99.
        // Use raw SQL to avoid requiring doctrine/dbal for column changes.
        DB::statement('ALTER TABLE `medical_records` MODIFY `cost` DECIMAL(15,2) NULL');
    }

    public function down(): void
    {
        if (!Schema::hasTable('medical_records') || !Schema::hasColumn('medical_records', 'cost')) {
            return;
        }

        DB::statement('ALTER TABLE `medical_records` MODIFY `cost` DECIMAL(8,2) NULL');
    }
};
