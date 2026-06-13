<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    public function up(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            if (!Schema::hasColumn('medical_records', 'appointment_id')) {
                $table->unsignedBigInteger('appointment_id')->nullable()->unique()->after('user_id');
                $table->foreign('appointment_id')->references('id')->on('appointments')->onDelete('set null');
            }
        });

        $appointments = DB::table('appointments')
            ->select('id', 'doctor_id', 'user_id', 'appointment_date')
            ->whereNotNull('user_id')
            ->get();

        foreach ($appointments as $appointment) {
            $sameDayAppointments = DB::table('appointments')
                ->where('doctor_id', $appointment->doctor_id)
                ->where('user_id', $appointment->user_id)
                ->whereDate('appointment_date', $appointment->appointment_date)
                ->count();

            if ($sameDayAppointments !== 1) {
                continue;
            }

            $matchingRecordIds = DB::table('medical_records')
                ->whereNull('appointment_id')
                ->where('doctor_id', $appointment->doctor_id)
                ->where('user_id', $appointment->user_id)
                ->whereDate('exam_date', $appointment->appointment_date)
                ->pluck('id');

            if ($matchingRecordIds->count() === 1) {
                DB::table('medical_records')
                    ->where('id', $matchingRecordIds->first())
                    ->update(['appointment_id' => $appointment->id]);
            }
        }
    }

    public function down(): void
    {
        Schema::table('medical_records', function (Blueprint $table) {
            if (Schema::hasColumn('medical_records', 'appointment_id')) {
                $table->dropForeign(['appointment_id']);
                $table->dropUnique('medical_records_appointment_id_unique');
                $table->dropColumn('appointment_id');
            }
        });
    }
};
