<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class DetentionReasons extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'detention_reasons';

    protected $fillable = [
        'uuid', 'school_id', 'semester_id', 'name'
    ];
}