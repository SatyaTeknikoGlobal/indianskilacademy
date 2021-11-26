<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Solutions extends Model
{
     protected $table = 'solutions';
      protected $fillable = [
        'q_id', 'e_solutions','h_solutions','image','admitted_by','del'
    ];
     public $timestamps = false;
}
