<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class JoinLiveClasses extends Model
{
    protected $table = 'join_live_classes';
      protected $fillable = [
        'user_id','live_class_id','faculties_id',
    ];
}
