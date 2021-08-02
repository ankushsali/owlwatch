<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolUsers extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'school_users';

    protected $fillable = [
        'user_id', 'school_id', 'role', 'color'
    ];
}