<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class StudentContacts extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'student_contacts';

    protected $fillable = [
        'school_id', 'semester_id', 'student_id', 'name', 'phone', 'phone_type', 'email'
    ];
}