<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class DropRedundantColumnsFromInvoicesTable extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->dropColumn(['patient_name', 'exam_date', 'phone', 'cost']);
        });
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('invoices', function (Blueprint $table) {
            $table->string('patient_name')->nullable();
            $table->date('exam_date')->nullable();
            $table->string('phone')->nullable();
            $table->bigInteger('cost')->nullable();
        });
    }
}
