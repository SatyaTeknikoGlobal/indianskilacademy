<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppUser;
use App\Boards;
use App\Faculties;
use DB;
use App\User;
use App\Exams;
use App\News;
use Redirect;
use Illuminate\Support\Facades\Mail;



class AdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */

    public function __construct()
    {

        date_default_timezone_set("Asia/Calcutta");  
    }

    public function index()
    {
        $data = [];
        // $total_user = AppUser::get();
        $total_user = [];
        $total_user = AppUser::select('id')->get();
        $faculties = Faculties::count();
        $boards = Boards::get();

        $exams = Exams::get();

        $sub_user= DB::table('users')
        ->join('subscription_histories','users.id','=','subscription_histories.user_id')
        ->groupBy('users.id')
        ->get();
        $free_user = 0;
         $free_user = count($total_user)-count($sub_user);
        $results = DB::table('results')->get();

        $subscription_types = DB::table('subscription_types')
        ->rightjoin('boards','boards.id','=','subscription_types.type_id')
        ->select('subscription_types.*','boards.id as board_id','boards.board_name','boards.image','boards.status','boards.board_name_hindi','boards.created_at as board_created','boards.status as board_status','boards.created_by')
        ->get();

        $news = News::get();



        $data['subscription_types'] = $subscription_types;
        $data['total_user'] = $total_user;
        $data['faculties'] = $faculties;
        $data['boards'] = $boards;
        $data['results'] = $results;
        $data['exams'] = $exams;
        $data['free_user'] = $free_user;
        $data['news'] = $news;
        return view('admin/dashboard',$data);
    }
    public function summary(Request $request){
      $data = [];
      $total_user = AppUser::get();
      $faculties = Faculties::count();
      $boards = Boards::get();

      $exams = Exams::get();

      $sub_user= DB::table('users')
      ->join('subscription_histories','users.id','=','subscription_histories.user_id')
      ->groupBy('users.id')
      ->get();
      $free_user = count($total_user)-count($sub_user);
      $results = DB::table('results')->get();

      $subscription_types = DB::table('subscription_types')
      ->rightjoin('boards','boards.id','=','subscription_types.type_id')
      ->select('subscription_types.*','boards.id as board_id','boards.board_name','boards.image','boards.status','boards.board_name_hindi','boards.created_at as board_created','boards.status as board_status','boards.created_by')
      ->get();
      $data['subscription_types'] = $subscription_types;
      $data['total_user'] = $total_user;
      $data['faculties'] = $faculties;
      $data['boards'] = $boards;
      $data['results'] = $results;
      $data['exams'] = $exams;
      $data['free_user'] = $free_user;
      return view('admin/summery',$data);  
  }

  private function sendEmail($name = "Agri",$email = "kshitij.singh559@hotmail.com")
  {

    $content = array('name'=>$name, 'email'=>$email);

    Mail::send('email.birthday', $content, function($message) use ($name, $email) {

        $message->to($email, $name)
        ->subject('Happy Birthday');
        $message->from(env('MAIL_FROM_ADDRESS'),env('MAIL_FROM_NAME'));
    });

}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //
    }

    public function getBirthdays(Request $request)
    {
        $data = AppUser::whereMonth("date_of_birth", '=', date('m'))->whereDay('date_of_birth','=', date('d'))->get();
        foreach ($data as  $row) {
           $message = "Dear ".$row->name.",\nToday ".$row->date_of_birth." is your birthday. We are glad that you have chosen ACC as your partner to fulfill your educational goals. We Wish you a many many happy returns of the day. As long as you work hard and never stop believing in yourself, good luck and success shall always accompany you.\nHappy birthday.";
           $this->send_message($row->phone,$message);
           if($this->valid_email($row->email)){
         	// $this->sendEmail($row->name,$row->email);
           }

       }
   }

   function valid_email($str) {
      return (!preg_match("/^([a-z0-9\+_\-]+)(\.[a-z0-9\+_\-]+)*@([a-z0-9\-]+\.)+[a-z]{2,6}$/ix", $str)) ? FALSE : TRUE;
  }


  private function send_message($mobile,$message)

  {

    $sender = "AGRICO";

    $message = urlencode($message);

    $msg = "sender=".$sender."&route=4&country=91&message=".$message."&mobiles=".$mobile."&authkey=284738AIuEZXRVCDfj5d26feae";

    $ch = curl_init('http://api.msg91.com/api/sendhttp.php?');

    curl_setopt($ch, CURLOPT_POST, true);

    curl_setopt($ch, CURLOPT_POSTFIELDS, $msg);

        //curl_setopt($ch, CURLOPT_CUSTOMREQUEST, "GET");

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

    $res = curl_exec($ch);

    $result = curl_close($ch);

    return $res;

}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        //
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {
     $request->validate([
        'name' =>'required',
        'email'=>'required',
        'phone'=>'required',
    ]);

     $data = array(
        'name'=>request('name'),
        'email'=>request('email'),
        'phone'=>request('phone'),
    );
     User::where(['id'=>$id])->update($data);

     $user = User::where(['id'=>$id])->first();

    Faculties::where(['id'=>$user->faculties_id])->update($data);



     return redirect()->route('myprofile.index')
     ->with('success','Profile Updated successfully.');
 }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }

    public function update_status(Request $request){

        $response = [];
        $status = isset($request->status) ? $request->status :0;
        $board_id = isset($request->board_id) ? $request->board_id :0;

        $data = array(
            'status'=>$status,
        );
        $res = Boards::where(['id'=>$board_id])->update($data);
        if($res){
            $response = array('status'=>'success','msg'=>'Updated successfully');
            echo json_encode($response);
        }else{
            $response = array('status'=>'error','msg1'=>'Not Updated ');
            echo json_encode($response);
        }
    }

//Update Subject STatus

    public function update_sub_status(Request $request){
       $response = [];
       $status = isset($request->sub_status) ? $request->sub_status :0;
       $sub_id = isset($request->sub_id) ? $request->sub_id :0;

       $data = array(
        'status'=>$status,
    );
       $res = DB::table('subjects')->where(['id'=>$sub_id])->update($data);
       if($res){
        $response = array('status'=>'success','msg'=>'Updated successfully');
        echo json_encode($response);
    }else{
        $response = array('status'=>'error','msg1'=>'Not Updated ');
        echo json_encode($response);
    }
}



//Update Chapter STatus

public function update_chap_status(Request $request){
   $response = [];
   $status = isset($request->chap_status) ? $request->chap_status :0;
   $chap_id = isset($request->chap_id) ? $request->chap_id :0;

   $data = array(
    'status'=>$status,
);
   $res = DB::table('chapters')->where(['id'=>$chap_id])->update($data);
   if($res){
    $response = array('status'=>'success','msg'=>'Updated successfully');
    echo json_encode($response);
}else{
    $response = array('status'=>'error','msg1'=>'Not Updated ');
    echo json_encode($response);
}
}





public function app_sidebar(Request $request){
    $data =[];
    $app_sidebars = DB::table('app_sidebar')->get();
    $data['app_sidebars'] = $app_sidebars;
    return view('admin/appsidebar/list',$data);  

}


public function app_sidebar_add(Request $request){
    $data =[];
    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $dbArray = [];
        $dbArray['title'] = isset($request->title) ? $request->title :'';
        $dbArray['bar_id'] = isset($request->bar_id) ? $request->bar_id :'';
        $dbArray['link'] = isset($request->link) ? $request->link :'';
        $dbArray['type'] = isset($request->type) ? $request->type :'';
        $dbArray['status'] = isset($request->status) ? $request->status :1;
        $dbArray['description'] = isset($request->description) ? $request->description :'';
        
        $imageName = isset($request->file) ? $request->file :'';


        if($request->hasFile('file')){

            $imageName = time().'.'.$request->file->extension();  
            
            $request->file->move(public_path('images/app_sidebar'), $imageName);
        }

        $dbArray['file'] = $imageName;

        
        DB::table('app_sidebar')->insert($dbArray);
        return Redirect::back()->with('success', 'Added Successfully');
    }




    return view('admin.appsidebar.add',$data);
}



public function app_sidebar_edit(Request $request){
    $id = isset($request->id) ? $request->id :'';
    $data =[];
    $method = $request->method();

    if($method == 'post' || $method == 'POST'){
        $dbArray = [];
        $dbArray['title'] = isset($request->title) ? $request->title :'';
        $dbArray['bar_id'] = isset($request->bar_id) ? $request->bar_id :'';
        $dbArray['link'] = isset($request->link) ? $request->link :'';
        $dbArray['type'] = isset($request->type) ? $request->type :'';
        $dbArray['description'] = isset($request->description) ? $request->description :'';

        $dbArray['status'] = isset($request->status) ? $request->status :1;
        $imageName = isset($request->file) ? $request->file :'';


        if($request->hasFile('file')){

            $imageName = time().'.'.$request->file->extension();  
            
            $request->file->move(public_path('images/app_sidebar'), $imageName);
        }
        $dbArray['file'] = $imageName;

        
        DB::table('app_sidebar')->where('id',$id)->update($dbArray);
        return Redirect::back()->with('success', 'Updated Successfully');
    }

    $app_sidebar = DB::table('app_sidebar')->where('id',$id)->first();
    $data['sidebar'] = $app_sidebar;

    return view('admin.appsidebar.edit',$data);


}



public function app_sidebar_delete(Request $request){
    $id = isset($request->id) ? $request->id :'';

    DB::table('app_sidebar')->where('id',$id)->delete();
    return Redirect::back()->with('success', 'Deleted Successfully');

}






}
