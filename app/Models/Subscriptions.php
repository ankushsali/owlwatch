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
        'subscription_id', 'subscription_name', 'limit'
    ];
}