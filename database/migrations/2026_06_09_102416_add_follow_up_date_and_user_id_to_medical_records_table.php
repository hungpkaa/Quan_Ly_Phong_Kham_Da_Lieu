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
        Schema::table('medical_records', function (Blueprint $table) {
            if (!Schema::hasColumn('medical_records', 'follow_up_date')) {
                $table->date('follow_up_date')->nullable()->after('exam_date');
            }
            if (!Schema::hasColumn('medical_records', 'user_id')) {
                $table->unsignedBigInteger('user_id')->nullable()->after('doctor_id');
                // Optional: $table->foreign('user_id')->references('id')->on('users')->onDelete('set null');
            }
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            if (Schema::hasColumn('medical_records', 'follow_up_date')) {
                $table->dropColumn('follow_up_date');
            }
            if (Schema::hasColumn('medical_records', 'user_id')) {
                $table->dropColumn('user_id');
            }
        });
    }
};
