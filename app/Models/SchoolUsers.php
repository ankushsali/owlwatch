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
        'user_id', 'school_id'
    ];

    public function School()
    {
        return $this->hasOne('App\Models\Schools','uuid','school_id');
    }
}