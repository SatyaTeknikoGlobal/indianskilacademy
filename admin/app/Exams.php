<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{
    protected $table = 'exams';
     
    protected $guarded = [];

     public function topic()
    {
         return $this->hasOne('App\Topic','id','topic_id');
    }
}
