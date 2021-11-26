<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Content extends Model
{
    //
    protected $guarded = ['id'];
    public function topic()
    {
        return $this->hasOne(Topic::class,'id','topic_id');
    }
    public function subject()
    {
        return $this->hasOne(Subject::class,'id','subject_id');
    }
}
