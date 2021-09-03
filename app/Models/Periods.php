<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Periods extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'periods';

    protected $fillable = [
        'uuid', 'school_id', 'semester_id', 'period'
    ];
}