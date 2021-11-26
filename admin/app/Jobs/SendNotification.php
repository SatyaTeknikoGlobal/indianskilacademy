<?php

namespace App\Jobs;

use Illuminate\Bus\Queueable;
use Illuminate\Contracts\Queue\ShouldQueue;
use Illuminate\Foundation\Bus\Dispatchable;
use Illuminate\Queue\InteractsWithQueue;
use Illuminate\Queue\SerializesModels;
use Illuminate\Http\Request;
use DB;
use App\AppUser;
use App\UserLogin;
use App\SubscriptionHistory;

class SendNotification implements ShouldQueue
{
    use Dispatchable, InteractsWithQueue, Queueable, SerializesModels;

    /**
     * Create a new job instance.
     *
     * @return void
     */
    public function __construct()
    {
        //
    }

    /**
     * Execute the job.
     *
     * @return void
     */
    public function handle()
    {
       
   $all_notifications = DB::table('notifications_scheduling')->where('status',0)->get();
   if(!empty($all_notifications)){
    foreach($all_notifications as $notif){
        if($notif->type == 'all'){
                /////ALL USERS///////
            $users = AppUser::select('id')->get();
            if(!empty($users)){
                foreach($users as $user){
                    $user_logins = UserLogin::where('user_id',$user->id)->get();


                    
                    if(!empty($user_logins)){
                        foreach($user_logins as $login){
                         $deviceToken = $login->deviceToken;
                         $sendData = array(
                            'body' => $notif->text,
                            'title' => $notif->title,
                            'image'=>$notif->image,
                            'sound' => 'Default',
                        );
                         $result = $this->fcmNotification($deviceToken,$sendData);
                            $tsuccess = DB::table('new')->insert(array('name'=>'dfasfsfsdfsdffsdfsdfds'));
                         
                         if($result){
                            $dbArr = [];
                            $dbArr['userID'] = $login->user_id;
                            $dbArr['text'] = $notif->text;
                            $dbArr['title'] = $notif->title;
                            $dbArr['image'] = $notif->image;
                            $dbArr['is_send'] = 1;

                            
                            //DB::table('notifications')->insert($dbArr);
                        }
                    }
                }
            }
        }
        DB::table('notifications_scheduling')->where('id',$notif->id)->update(['status'=>1]);


    }else if($notif->type == 'course'){
        if(!empty($notif->course_id)){
            $userIDs = [];
            $sub_history = SubscriptionHistory::select('id','user_id')->where('subject_id',$notif->course_id)->get();
            if(!empty($sub_history)){
                foreach($sub_history as $his){
                    $userIDs[] = $his->user_id;
                }
            }

            $users = AppUser::select('id')->whereIn('id',$userIDs)->get();




            if(!empty($users)){
                foreach($users as $user){
                    $user_logins = UserLogin::where('user_id',$user->id)->get();
                    if(!empty($user_logins)){
                        foreach($user_logins as $login){
                         $deviceToken = $login->deviceToken;
                         $sendData = array(
                            'body' => $notif->text,
                            'title' => $notif->title,
                            'image'=>$notif->image,
                            'sound' => 'Default',
                        );
                         $result = $this->fcmNotification($deviceToken,$sendData);

                         if($result){
                            $dbArr = [];
                            $dbArr['userID'] = $login->user_id;
                            $dbArr['text'] = $notif->text;
                            $dbArr['title'] = $notif->title;
                            $dbArr['image'] = $notif->image;
                            $dbArr['is_send'] = 1;
                            DB::table('notifications')->insert($dbArr);
                        }
                    }
                }
            }
        }
        DB::table('notifications_scheduling')->where('id',$notif->id)->update(['status'=>1]);


    }


}else if($notif->type == 'batch'){
    if(!empty($notif->batch_id)){
        $userIDs = [];
        $sub_history = SubscriptionHistory::select('id','user_id')->where('topic_id',$notif->batch_id)->get();
        if(!empty($sub_history)){
            foreach($sub_history as $his){
                $userIDs[] = $his->user_id;
            }
        }

        $users = AppUser::select('id')->whereIn('id',$userIDs)->get();
        if(!empty($users)){
            foreach($users as $user){
                $user_logins = UserLogin::where('user_id',$user->id)->get();
                if(!empty($user_logins)){
                    foreach($user_logins as $login){
                     $deviceToken = $login->deviceToken;
                     $sendData = array(
                        'body' => $notif->text,
                        'title' => $notif->title,
                        'image'=>$notif->image,
                        'sound' => 'Default',
                    );
                     $result = $this->fcmNotification($deviceToken,$sendData);

                     if($result){
                        $dbArr = [];
                        $dbArr['userID'] = $login->user_id;
                        $dbArr['text'] = $notif->text;
                        $dbArr['title'] = $notif->title;
                        $dbArr['image'] = $notif->image;
                        $dbArr['is_send'] = 1;
                    DB::table('notifications')->insert($dbArr);
                    }
                }
            }
        }
    }
    DB::table('notifications_scheduling')->where('id',$notif->id)->update(['status'=>1]);
}



}




}
}


    }
}
