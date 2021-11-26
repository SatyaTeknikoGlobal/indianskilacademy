<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';
     protected $guarded = [];


    public function subjects()
    {
         return $this->hasOne('App\Subject','id','subject');
    }

    public function topic()
    {
        return $this->hasOne('App\Topic','id','topic_id');
    }
}
