<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class DiscussionAnswer extends Model
{
    //
    protected $guarded = ['id'];

    public function user()
    {
        return $this->hasOne(User::class,'id','user_id')->select([
            "id",
            "name"
        ]);
    }
}
