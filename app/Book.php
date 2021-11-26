<?php

namespace App;

use Illuminate\Database\Eloquent\Model;

class Book extends Model
{
     protected $guarded = ['id'];

     public function authors()
    {
    	return $this->hasOne('App\Author','id','author');
    }

    public function publishers()
    {
    	return  $this->hasOne('App\Publisher','id','publisher');
    }


    public function categories()
    {
    	return  $this->hasOne('App\BookCategory','id','category');
    }
     
}
