<?php

namespace App\Console\Commands;
use Illuminate\Console\Command;
use DB;

class UserRanking extends Command
{
 public function __construct()
    {
        parent::__construct();
    }  

    protected $signature = 'quote:daily';


    protected $description = 'Respectively send an exclusive quote to everyone daily via email.';



	 public function handle()
    {
 		$tsuccess = DB::table('new')->insert(array('name'=>'bgjty'));
    }

}