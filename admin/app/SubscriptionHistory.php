<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class SubscriptionHistory extends Model
{
    //
    protected $guarded = ['id'];


     public function users()
    {
         return $this->hasOne('App\AppUser','id','user_id');
    }

}
