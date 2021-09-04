<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Tardy extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'tardy';

    protected $fillable = [
        'uuid', 'school_id', 'semester_id', 'period_id', 'student_id'
    ];

    public function School()
    {
        return $this->hasOne('App\Models\Schools','uuid','school_id');
    }

    public function Semester()
    {
        return $this->hasOne('App\Models\Semesters','uuid','semester_id');
    }

    public function Period()
    {
        return $this->hasOne('App\Models\Periods','uuid','period_id');
    }
}