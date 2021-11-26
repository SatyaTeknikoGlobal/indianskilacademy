<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class LiveClass extends Model
{
   protected $table = 'live_classes';
      protected $fillable = [
        'title','description','start_date','start_time','end_time','faculties_id','image'
    ];


     public function faculties()
    {
        return $this->hasOne(Faculties::class,'id','faculties_id');
    }
}
