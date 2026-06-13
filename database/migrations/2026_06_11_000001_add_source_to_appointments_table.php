<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSourceToAppointmentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasColumn('appointments', 'source')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->string('source')->default('manual')->after('status');
            });
        }
    }

    public function down()
    {
        if (Schema::hasColumn('appointments', 'source')) {
            Schema::table('appointments', function (Blueprint $table) {
                $table->dropColumn('source');
            });
        }
    }
}
