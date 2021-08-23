<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Locations extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'locations';

    protected $fillable = [
        'uuid', 'school_id', 'name'
    ];
}