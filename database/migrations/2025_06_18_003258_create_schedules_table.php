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
        Schema::create('schedules', function (Blueprint $table) {
            $table->id();
            $table->foreignId('student_id')->constrained()->onDelete('cascade');
            $table->foreignId('teacher_id')->constrained('users')->onDelete('cascade');

            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('student_room_id')->nullable();

            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->date('schedule_date');
            $table->string('time_slot'); // Normalized column, e.g., "08:00-08:50"

            $table->enum('status', ['present MTM', 'present GRP', 'absent MTM', 'absent GRP', 'N/A'])->default('N/A');
            $table->timestamps();
            $table->softDeletes();

            $table->foreign('room_id')->references('id')->on('rooms')->onDelete('set null');
            $table->foreign('student_room_id')->references('id')->on('rooms')->onDelete('set null');
        });

    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('schedules');
    }
};
