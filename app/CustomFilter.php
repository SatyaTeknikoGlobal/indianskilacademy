<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class CustomFilter extends Model
{
     protected $table = 'custom_filters';
      protected $fillable = [
        'filter_name','type'
    ];


}
