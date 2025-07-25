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
        'sub_teacher_id',
        'room_id',
        'student_room_id',
        'subject_id',
        'schedule_date',
        'time_slot', // ⬅️ This replaces all individual time columns
        'status',
        'schedule_state',
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

    public function subTeacher()
    {
        return $this->belongsTo(Teacher::class, 'sub_teacher_id', 'user_id');
    }
}
