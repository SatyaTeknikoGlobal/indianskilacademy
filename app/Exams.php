<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Exams extends Model
{
    protected $table = 'exams';
    protected $guarded = [];



    public function subject()
    {
        return $this->hasOne('App\CoursesSubject','id','sub_id');
    }
}
