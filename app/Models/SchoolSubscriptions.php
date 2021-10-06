<?php

namespace App\Models;

use Illuminate\Database\Eloquent\Model;

class SchoolSubscriptions extends Model
{
    /*
     * The table associated with the model.
     */
    protected $table = 'school_subscriptions';

    protected $fillable = [
        'school_id', 'subscription', 'start_date', 'end_date'
    ];

    public function Subscription()
    {
        return $this->hasOne('App\Models\Subscriptions','subscription_id','subscription');
    }
}