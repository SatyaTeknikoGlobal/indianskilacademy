<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class UserChats extends Model
{
    protected $table = 'chats';
      protected $fillable = [
        'user_id','live_class_id','faculties_id','text',
    ];
}
