<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Options extends Model
{
    protected $table = 'options';
      protected $fillable = [
        'q_id', 'option_h','option_e','correct','del'
    ];
}
