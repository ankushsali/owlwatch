<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Semesters extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'semesters';

    protected $fillable = [
        'uuid', 'school_id', 'name'
    ];
}