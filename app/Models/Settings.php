<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Settings extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'settings';

    protected $fillable = [
        'school_id', 'name', 'value', 'status'
    ];
}