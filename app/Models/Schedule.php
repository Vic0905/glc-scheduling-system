<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;
use Illuminate\Database\Eloquent\SoftDeletes;

class Schedule extends Model
{
    use SoftDeletes;

    protected $fillable = [
    'student_id',
    'teacher_id',
    'room_id',
    'student_room_id',
    'subject_id',
    'schedule_date',
    'schedule_time',
    'status',
    'schedule_state', // ✅ Add this line
    'time_8_00_8_50',
    'time_9_00_9_50',
    'time_10_00_10_50',
    'time_11_00_11_50',
    'time_12_00_12_50',
    'time_13_00_13_50',
    'time_14_00_14_50',
    'time_15_00_15_50',
    'time_16_00_16_50',
    'time_17_00_17_50',
];

    
    public function student()
    {
        return $this->belongsTo(Student::class, 'student_id');
    }

    public function teacher()
    {
        return $this->belongsTo(Teacher::class, 'teacher_id', 'user_id');
    }

    public function subject()
    {
        return $this->belongsTo(Subject::class, 'subject_id');
    }
    public function room()
    {
        return $this->belongsTo(Room::class, 'room_id');
    }
    public function studentRoom()
    {
        return $this->belongsTo(Room::class, 'student_room_id');
    }
}

