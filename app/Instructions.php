<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Instructions extends Model
{
    protected $table = 'instructions';
      protected $fillable = [
        'title','slug','e_content','h_content'
    ];
     public $timestamps = false;
}
