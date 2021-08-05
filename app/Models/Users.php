<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Users extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'users';

    protected $fillable = [
        'uuid', 'login_id', 'first_name', 'last_name', 'email', 'school_name', 'school_color', 'detention_color', 'image', 'type', 'is_admin'
    ];

    public function getImageNameAttribute()
    {
        return basename($this->image);
    }
    public function getImageAttribute($value)
    {
        return env('APP_URL').'public/user-images/'.$value;
    }
}