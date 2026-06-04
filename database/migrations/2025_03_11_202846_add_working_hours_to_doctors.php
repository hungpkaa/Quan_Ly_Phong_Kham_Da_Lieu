<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

class AddWorkingHoursToDoctors extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if (!Schema::hasColumn('doctors', 'working_hours')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->json('working_hours')->nullable()->after('image');
            });
        }
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        if (Schema::hasColumn('doctors', 'working_hours')) {
            Schema::table('doctors', function (Blueprint $table) {
                $table->dropColumn('working_hours');
            });
        }
    }
}
