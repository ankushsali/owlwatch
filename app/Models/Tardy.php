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
}