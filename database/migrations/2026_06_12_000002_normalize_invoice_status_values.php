<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

class NormalizeInvoiceStatusValues extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        $this->makeStatusColumnString('unpaid');

        DB::table('invoices')
            ->whereIn('status', ['Đã thanh toán', 'ÄĂ£ thanh toĂ¡n'])
            ->update(['status' => 'paid']);

        DB::table('invoices')
            ->whereIn('status', ['Chưa thanh toán', 'ChÆ°a thanh toĂ¡n'])
            ->update(['status' => 'unpaid']);
    }

    public function down()
    {
        if (!Schema::hasTable('invoices')) {
            return;
        }

        DB::table('invoices')->where('status', 'paid')->update(['status' => 'Đã thanh toán']);
        DB::table('invoices')->where('status', 'unpaid')->update(['status' => 'Chưa thanh toán']);

        if (DB::getDriverName() === 'mysql') {
            DB::statement("ALTER TABLE invoices MODIFY status ENUM('Đã thanh toán', 'Chưa thanh toán') NOT NULL DEFAULT 'Chưa thanh toán'");
        }
    }

    private function makeStatusColumnString(string $default): void
    {
        $driver = DB::getDriverName();

        if ($driver === 'mysql') {
            DB::statement("ALTER TABLE invoices MODIFY status VARCHAR(20) NOT NULL DEFAULT '{$default}'");
            return;
        }

        if ($driver === 'pgsql') {
            DB::statement('ALTER TABLE invoices ALTER COLUMN status TYPE VARCHAR(20)');
            DB::statement("ALTER TABLE invoices ALTER COLUMN status SET DEFAULT '{$default}'");
            return;
        }

        if ($driver === 'sqlsrv') {
            DB::statement('ALTER TABLE invoices ALTER COLUMN status NVARCHAR(20) NOT NULL');
        }
    }
}
