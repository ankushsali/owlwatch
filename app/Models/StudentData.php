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
        'school_id', 'semester_id', 'first_name', 'last_name', 'student_id', 'grade', 'dob', 'counselor', 'locker_number', 'locker_combination', 'parking_space', 'license_plate'
    ];

    public function StudentSchedules()
    {
        return $this->hasMany('App\Models\StudentSchedules','student_id','student_id');
    }

    public function StudentContacts()
    {
        return $this->hasMany('App\Models\StudentContacts','student_id','student_id');
    }
}