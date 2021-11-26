<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    //
    protected $guarded = ['id'];

    public function package()
    {
        return $this->hasOne(SubscriptionType::class,'id','package_id');
    }
}
