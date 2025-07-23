<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Student extends Model
{
    protected $fillable = ['name', 'english_name', 'course', 'level'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }

    public function teacher()
    {
        return $this->belongsTo(teacher::class);
    }
}
