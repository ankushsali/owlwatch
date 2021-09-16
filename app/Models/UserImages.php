<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class UserImages extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'student_images';

    protected $fillable = [
        'student_id', 'school_id', 'semester_id', 'image'
    ];
}