<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentData extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'student_data';

    protected $fillable = [
        'school_id', 'first_name', 'last_name', 'student_id', 'grade', 'dob', 'counselor', 'locker_number', 'locker_combination', 'parking_space', 'license_plate'
    ];
}