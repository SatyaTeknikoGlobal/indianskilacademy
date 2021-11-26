<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boards;
use App\User;
use App\AppUser;
use App\Topic;
use App\Subject;
use DB;
use Auth;
use Validator;

class ChatController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        return view('admin/chats/index');
    }

    public function get_chats(Request $request){

        $html = '';
        $user_id = isset($request->user_id) ? $request->user_id :0;

        $doubts = DB::table('doubts')->where('sender_id','!=',0)->groupBy('sender_id')->latest()->get();

        if(!empty($doubts)){
            $i=1;
            foreach($doubts as $db){
                $user = AppUser::where('id',$db->sender_id)->first();
                $date = date('d F',strtotime($db->created_at));

                $active = 'active_chat';


                if($i==1){
                    $html.='<div class="chat_list active_chat" onclick="get_chat_by_user('.$user->id.')">
                    <div class="chat_people">
                    <div class="chat_img"><img src="https://ptetutorials.com/images/user-profile.png" alt="User"> </div>
                    <div class="chat_ib">
                    <h5>'.$user->name.'<span class="chat_date">'.$date.'</span></h5>
                    </div>
                    </div>
                    </div>
                    <input type="hidden" id="get_chat" name="user_id" value='.$user->id.'>
                    ';
                }else{
                    $html.='<div class="chat_list " onclick="get_chat_by_user('.$user->id.')">
                    <div class="chat_people">
                    <div class="chat_img"><img src="https://ptetutorials.com/images/user-profile.png" alt="User"> </div>
                    <div class="chat_ib">
                    <h5>'.$user->name.'<span class="chat_date">'.$date.'</span></h5>
                    </div>
                    </div>
                    </div>';
                }






                $i++;


            }
        }            




        echo $html;



    }

    public function get_user_name(Request $request){
        $user_id = isset($request->user_id) ? $request->user_id :'';
        $user = AppUser::where('id',$user_id)->first();
        echo json_encode($user);
    }

    public function send_message(Request $request){

        $dbArray = [];
        $dbArray['sender_id'] = $request->sender_id;
        $dbArray['receiver_id'] = $request->receiver_id;
        $dbArray['sender_type'] = $request->sender_type;
        $dbArray['receiver_type'] = $request->receiver_type;
        $dbArray['status'] = $request->status;
        $dbArray['message'] = $request->message;


        $success = DB::table('doubts')->insert($dbArray);
        if($success)
        {
            echo json_encode(['status'=>true,'user_id'=>$request->receiver_id]);
        }else{
            echo json_encode(['status'=>false,'user_id'=>0]);

        }


    }




    public function get_chat_by_user(Request $request){

       $user_id = isset($request->user_id) ? $request->user_id :0;
       $html = '<input type="hidden" id="new_user_id" name="new_user_id" value='.$user_id.'>';
       if($user_id !=0 || $user_id !=''){
           $doubts = DB::table('doubts')->where('sender_id',$user_id)->orWhere('receiver_id',$user_id)->get();
           if(!empty($doubts)){
            $i=1;
            foreach($doubts as $db){
                $user = AppUser::where('id',$db->sender_id)->first();
                $date = date('F d',strtotime($db->created_at));
                $time = date('h:i A',strtotime($db->created_at));

                if($db->sender_type == 'admin' && $db->sender_id == 0){
                    $html.='<div class="outgoing_msg">
                    <div class="sent_msg">
                    <p>'.$db->message.'</p>
                    <span class="time_date"> '.$time.'    |    '.$date.'</span> </div>
                    </div>';
                }
                if($db->sender_type == 'user' && $db->sender_id != 0){
                    $html.='<div class="incoming_msg">
                    <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="User"> </div>
                    <div class="received_msg">
                    <div class="received_withd_msg">
                    <p>'.$db->message.'</p>
                    <span class="time_date"> '.$time.'     |    '.$date.'</span></div>
                    </div>
                    </div>';

                }


            }
        }            
    }


    echo $html;


}



public function group_chat(Request $request){
   return view('admin/chats/group_chats');
}

public function get_groups(Request $request){
  $html = '';
  $user_id = isset($request->user_id) ? $request->user_id :0;

  $chat_group = DB::table('chat_group')->get();

  if(!empty($chat_group)){
    $i=1;
    foreach($chat_group as $db){
        $topic = Topic::where('id',$db->program_id)->first();
        $course_name = '';
        $course = [];
        if(!empty($topic->subject_id)){
            $course = Subject::where('id',$topic->subject_id)->first();
        }
        if(!empty($course)){
            $course_name = $course->title;
        }


        if(!empty($topic)){
            $date = date('d F',strtotime($db->created_at));
            if($i==1){
                $html.='<div class="chat_list active_chat" onclick="get_chat_by_program('.$topic->id.')">
                <div class="chat_people">
                <div class="chat_img"><img src="https://ptetutorials.com/images/user-profile.png" alt="User"> </div>
                <div class="chat_ib">
                <h5>'.$topic->name." - ". $course_name.'<span class="chat_date">'.$date.'</span></h5>
                </div>
                </div>
                </div>
                <input type="hidden" id="program_id" name="program_id" value='.$db->program_id.'>
                ';
            }else{
                $html.='<div class="chat_list " onclick="get_chat_by_program('.$topic->id.')">
                <div class="chat_people">
                <div class="chat_img"><img src="https://ptetutorials.com/images/user-profile.png" alt="User"> </div>
                <div class="chat_ib">
                <h5>'.$topic->name." - ". $course_name.'<span class="chat_date">'.$date.'</span></h5>
                </div>
                </div>
                </div>



                ';
            }

            $i++;


        }

    }
}            




echo $html;


}

public function get_program_name(Request $request){
  $program_id = isset($request->program_id) ? $request->program_id :'';
  $topic = Topic::where('id',$program_id)->first();
  echo json_encode($topic);
}



public function get_programchat_by_user(Request $request){

   $program_id = isset($request->program_id) ? $request->program_id :0;
   $page = isset($request->page) ? $request->page :1;
   $perpage = 10;
   $count = $perpage * $page;
   $group = DB::table('chat_group')->where('program_id',$program_id)->first();


   $html = '';
   if($program_id !=0 || $program_id !=''){
       $doubts = DB::table('group_chat')->where('g_id',$group->id)->skip(0)->take($count)->get();
       if(!empty($doubts)){
        $i=1;
        foreach($doubts as $db){
            $user = AppUser::where('id',$db->user_id)->first();
            $date = date('F d',strtotime($db->created_at));
            $time = date('h:i A',strtotime($db->created_at));

            if($db->user_id == 0){
                $html.='<div class="outgoing_msg">
                <div class="sent_msg">
                <p>'.$db->text.'</p>
                <span class="time_date"> Admin | '.$time.'    |    '.$date.'</span> </div>
                </div>';
            }
            else{
                $html.='<div class="incoming_msg">
                <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="User"> </div>
                <div class="received_msg">
                <div class="received_withd_msg">
                <p>'.$db->text.'</p>
                <span class="time_date">'.$user->name.' | '.$time.'     |    '.$date.'</span></div>
                </div>
                </div>';

            }


        }
    }            
}


echo $html;


}

public function send_message_group(Request $request){
    $dbArray = [];

    $program_id = $request->receiver_id;

    $group = DB::table('chat_group')->where('program_id',$program_id)->first();

    $dbArray['user_id'] = $request->sender_id;

    $dbArray['g_id'] = $group->id;
    $dbArray['text'] = $request->message;


    $success = DB::table('group_chat')->insert($dbArray);
    if($success)
    {
        echo json_encode(['status'=>true,'program_id'=>$request->receiver_id]);
    }else{
        echo json_encode(['status'=>false,'program_id'=>0]);

    }



}


public function get_user_list_from_program(Request $request){
    $program_id = isset($request->program_id) ? $request->program_id :0;
    $html = '';
    if($program_id !=0){
        $chat_group = DB::table('chat_group')->where('program_id',$program_id)->first();
        if(!empty($chat_group)){
            $chat_users = DB::table('chat_users')->where('group_id',$chat_group->id)->get();
            if(!empty($chat_users)){
                foreach($chat_users as $chat){

                    $user = AppUser::where('id',$chat->user_id)->first();
                    $selected = '';
                    if($chat->is_block == 1){
                        $selected = 'selected';
                    }
                    $html.='<option value='.$user->id.' '.$selected.'>'.$user->name.'</option>';


                }
            }
        }
    }


    echo $html;

}


public function block_user(Request $request){
    $program_id = isset($request->program_id_modal) ? $request->program_id_modal :0;
    $response = [];
    $user_ids = isset($request->user_ids) ? $request->user_ids :0;
    $chat_group = DB::table('chat_group')->where('program_id',$program_id)->first();



    $blocked_users = DB::table('chat_users')->where('group_id',$chat_group->id)->where('is_block',1)->get();

    DB::table('chat_users')->where('group_id',$chat_group->id)->update(array('is_block'=>0));


    if(!empty($user_ids)){
        foreach ($user_ids as $key => $value) {
         $success = DB::table('chat_users')->where(array('user_id'=>$value,'group_id'=>$chat_group->id))->update(array('is_block'=>1));

     }
     
 }


 echo json_encode(array('status'=>true));
}




}