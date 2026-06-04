<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddServicesMedicinesToInvoices extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasTable('invoices') || Schema::hasColumn('invoices', 'services_medicines')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->text('services_medicines')
                ->charset('utf8mb4')
                ->collation('utf8mb4_unicode_ci')
                ->nullable()
                ->after('total_amount');
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (!Schema::hasTable('invoices') || !Schema::hasColumn('invoices', 'services_medicines')) {
            return;
        }

        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn('services_medicines');
        });
    }
}
