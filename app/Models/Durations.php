<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Durations extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'durations';

    protected $fillable = [
        'uuid', 'school_id', 'duration'
    ];
}