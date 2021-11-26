<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Question extends Model
{
    protected $table = 'questions';

    protected $guarded = [];


    public function subject()
    {
    	return $this->hasOne(Subject::class,'id','subject');
    }
}
