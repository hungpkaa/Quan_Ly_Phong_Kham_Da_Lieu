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
        Schema::create('patient_progresses', function (Blueprint $table) {
            $table->id();
            $table->unsignedBigInteger('user_id'); // Patient
            $table->unsignedBigInteger('doctor_id')->nullable(); // Assigned doctor if any
            $table->string('image_path'); // Photo of the skin
            $table->text('notes')->nullable(); // Patient description
            $table->timestamps();

            $table->foreign('user_id')->references('id')->on('users')->onDelete('cascade');
            $table->foreign('doctor_id')->references('id')->on('doctors')->onDelete('set null');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('patient_progresses');
    }
};
