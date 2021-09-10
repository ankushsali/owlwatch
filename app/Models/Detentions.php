<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Detentions extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'detentions';

    protected $fillable = [
        'uuid', 'school_id', 'semester_id', 'student_id', 'reason_id', 'create_date', 'serverd'
    ];

    public function School()
    {
        return $this->hasOne('App\Models\Schools','uuid','school_id');
    }

    public function Semester()
    {
        return $this->hasOne('App\Models\Semesters','uuid','semester_id');
    }

    public function StudentData()
    {
        return $this->hasOne('App\Models\StudentData','student_id','student_id');
    }

    public function Reason()
    {
        return $this->hasOne('App\Models\DetentionReasons','uuid','reason_id');
    }
}