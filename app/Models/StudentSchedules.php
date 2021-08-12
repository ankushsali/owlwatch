<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentSchedules extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'student_schedules';

    protected $fillable = [
        'school_id', 'student_id', 'period', 'teacher', 'room_number', 'class_name', 'semester'
    ];
}