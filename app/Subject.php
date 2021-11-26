<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Subject extends Model
{
    //
    protected $guarded = ['id'];

      public function faculties()
    {
        return $this->hasOne(Faculties::class,'id','faculties_id');
    }
}
