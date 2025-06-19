<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Room extends Model
{
    // define the table name  
    protected $fillable = ['roomname'];

    public function schedules()
    {
        return $this->hasMany(Schedule::class);
    }
}

