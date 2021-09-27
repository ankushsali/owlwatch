<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class Subscriptions extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'subscriptions';

    protected $fillable = [
        'school_id', 'subscription', 'start_date', 'end_date'
    ];
}