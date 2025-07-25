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

            // 👇 Moved room_id and student_room_id beside teacher_id
            $table->unsignedBigInteger('room_id')->nullable();
            $table->unsignedBigInteger('student_room_id')->nullable();

            $table->foreignId('subject_id')->constrained()->onDelete('cascade');
            $table->date('schedule_date');
            $table->time('schedule_time');
            $table->enum('status', ['present MTM', 'present GRP', 'absent MTM', 'absent GRP', 'N/A'])->default('N/A');

            // Time slot columns
            $table->boolean('time_8_00_8_50')->default(false);
            $table->boolean('time_9_00_9_50')->default(false);
            $table->boolean('time_10_00_10_50')->default(false);
            $table->boolean('time_11_00_11_50')->default(false);
            $table->boolean('time_12_00_12_50')->default(false);
            $table->boolean('time_13_00_13_50')->default(false);
            $table->boolean('time_14_00_14_50')->default(false);
            $table->boolean('time_15_00_15_50')->default(false);
            $table->boolean('time_16_00_16_50')->default(false);
            $table->boolean('time_17_00_17_50')->default(false);

            // Timestamps and soft deletes
            $table->timestamps();
            $table->softDeletes();

            // Foreign key constraints for room and student_room
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
