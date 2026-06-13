<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddSpecialtyToAppointmentsTable extends Migration
{
    public function up()
    {
        if (!Schema::hasTable('appointments') || Schema::hasColumn('appointments', 'specialty')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            $table->string('specialty')->nullable()->after('doctor_id');
        });
    }

    public function down()
    {
        if (!Schema::hasTable('appointments') || !Schema::hasColumn('appointments', 'specialty')) {
            return;
        }

        Schema::table('appointments', function (Blueprint $table) {
            $table->dropColumn('specialty');
        });
    }
}
