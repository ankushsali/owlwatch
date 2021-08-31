<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class HallPass extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'hall_pass';

    protected $fillable = [
        'uuid', 'user_id', 'school_id', 'student_name', 'location', 'duration', 'comments','status'
    ];

    public function Location()
    {
        return $this->hasOne('App\Models\Locations','uuid','location');
    }

    public function Duration()
    {
        return $this->hasOne('App\Models\Durations','uuid','duration');
    }

    public function StudentData()
    {
        return $this->hasOne('App\Models\StudentData','student_id','student_name');
    }

    public function User()
    {
        return $this->hasOne('App\Models\Users','uuid','user_id');
    }
}