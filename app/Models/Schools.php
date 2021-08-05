<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Schools extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'schools';

    protected $fillable = [
        'uuid', 'name', 'address', 'color'
    ];
}