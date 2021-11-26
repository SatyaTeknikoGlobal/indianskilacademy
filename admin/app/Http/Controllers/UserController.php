<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\AppUser;
use App\Boards;
use App\Classes;
use App\City;
use App\State;
use App\SubscriptionHistory;
use App\SubscriptionPackage;
use App\SubscriptionType;
use App\UserLogin;
use App\Subject;
use App\Topic;
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

use Maatwebsite\Excel\Facades\Excel;
use App\Exports\UserExport;

use Rap2hpoutre\FastExcel\FastExcel;






class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data =[];
        // echo "string";
        $search = isset($_GET['search']) ? $_GET['search'] :'';

        $user = AppUser::orderBy('id','desc');



        if(!empty($search)){
            $user->where('name', 'like', '%' . $search . '%');
            $user->orWhere('email', 'like', '%' . $search . '%');
            $user->orWhere('phone', 'like', '%' . $search . '%');

        }





        $user = $user->paginate(10);

        // echo count($user);


        $data['users'] = $user; 
        return  view('admin/users/list',$data);
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function getUsers(Request $request)
    {


     $datas = AppUser::orderBy('id','desc');

     $datas = $datas->get();

     return Datatables::of($datas)


     ->editColumn('id', function(AppUser $data) {

         return  $data->id;
     })
     ->editColumn('name', function(AppUser $data) {
         return  $data->name.'<br>'.$data->email;
     })
     ->editColumn('phone', function(AppUser $data) {
         return  $data->phone;
     })

     ->editColumn('email', function(AppUser $data) {
         return  $data->email;
     })



     ->addColumn('action', function(AppUser $data) {

        $html = '<a href="{{ url ("app_users") }}/'.$data->id.'/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a><a href="{{ url("reset_device")}}/'.$data->id.'" class="btn btn-info btn-sm"><i class="fa fa-lock" aria-hidden="true"></i> Reset Device</a><form action="{{ url("app_users") }}/'.$data->id.'" method="POST">@csrf @method("DELETE")<button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm("You Want to Delete this?")"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></form>';



        return $html;
    })

     ->rawColumns(['name', 'status','is_approve', 'action','role_id'])
     ->toJson();





 }
 public function addUser(Request $request){
    $board = Boards::latest()->select('id','board_name')->get();
    $method = $request->method();

    if($method == 'post' || $method == 'POST'){
        $request->validate([
            'name' =>'required',
            'email'=>'required',
            'phone'=>'required',
            'status'=>'required',
        ]);
        $data = array(
            'name'=>request('name'),
            'email'=>request('email'),
            'phone'=>request('phone'),
            'board_id'=>request('board_id'),
            'date_of_birth'=>request('dob'),
            'login_enabled'=>request('status'),   
            'password'=>bcrypt(123456),   
        );

        AppUser::create($data);
        return redirect()->route('app_users.index')
        ->with('success','Record Added successfully.');
    }


    return view('admin/users/add',array('board'=>$board));




}




public function reset_device($id)
{

   $created_at= Carbon::now()->toDateTimeString();
   UserLogin::where('user_id',$id)->delete();

   $value=array('user_id'=>$id,'created_at'=>$created_at);
   $check_reset= DB::table('reset_device')->insert($value);

   return redirect()->route('app_users.index')
   ->with('success','Reset Device successfully.');
}


public function change_password($id)
{
   AppUser::where('id',$id)->update(['password'=>bcrypt(123456)]);
   return redirect()->route('app_users.index')
   ->with('success','Password Changed successfully.');
}

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
   //  public function store(Request $request)
   //  {
   //      $user_id=$request->user_id;
   //      $type_id=$request->type;
   //      $subscr_type = SubscriptionType::find($type_id);
   //      $board_id = 0;
   //      $amount = $subscr_type->new_amount;
   //      $new_amount =0;
   //      $coupon_discount = 0;
   //      $online_amount = 0;
   //      $package_id = $subscr_type->id;
   //      $subs_type = $subscr_type->title;
   //      $subs_sub_type = 'course';
   //      $subs_sub_type_id =  $subscr_type->type_id;
   //      $start_date = $subscr_type->start_date;
   //      $end_date = $subscr_type->end_date;
   //      $desc = array();
   //      $txn_id_insert = 0;
   //      $desc[] = array(
   //          "id"=>$txn_id_insert,
   //          "type"=>'course',
   //          "startDate"=>$type_id,
   //          "endDate"=>$user_id,
   //          "coursePrice"=>$subs_type,
   //          "couponDiscount"=>$coupon_discount,
   //          "walletAmount"=>0,
   //          "payableAmount"=>$amount,
   //          "amountToPay"=>0,
   //          "couponCode"=>""
   //      );
   //      $insert_subscription = SubscriptionHistory::create([
   //          "user_id"=>$user_id,
   //          "txn_id"=>$txn_id_insert,
   //          "package_id"=>$package_id,
   //          "subs_type"=>$subs_type,
   //          "subs_sub_type"=>$subs_sub_type,
   //          "subs_sub_type_id"=>$subs_sub_type_id,
   //          "amount"=>$amount,
   //          "new_amount"=>$new_amount,
   //          "coupon_code"=>'IAS GYAN',
   //          "discount"=>$coupon_discount,
   //          "paid_amount"=>($amount - $coupon_discount),
   //          "start_date"=>$start_date,
   //          "end_date"=>$end_date,
   //          "description"=>json_encode($desc),
   //      ]);
   //     return redirect()->back()->with('success','Subscription Added Successfully');

   // }





   //   public function store(Request $request)
   //  {
   //      $user_id=$request->user_id;
   //      $type_id=$request->type;
   //      $subscr_type = SubscriptionType::find($type_id);
   //      $board_id = 0;
   //      $amount = $subscr_type->new_amount;
   //      $new_amount =0;
   //      $coupon_discount = 0;
   //      $online_amount = 0;
   //      $package_id = $subscr_type->id;
   //      $subs_type = $subscr_type->title;
   //      $subs_sub_type = 'course';
   //      $subs_sub_type_id =  $subscr_type->type_id;
   //      $start_date = $subscr_type->start_date;
   //      $end_date = $subscr_type->end_date;
   //      $desc = array();
   //      $txn_id_insert = 0;
   //      $desc[] = array(
   //          "id"=>$txn_id_insert,
   //          "type"=>'course',
   //          "startDate"=>$type_id,
   //          "endDate"=>$user_id,
   //          "coursePrice"=>$subs_type,
   //          "couponDiscount"=>$coupon_discount,
   //          "walletAmount"=>0,
   //          "payableAmount"=>$amount,
   //          "amountToPay"=>0,
   //          "couponCode"=>""
   //      );
   //      $insert_subscription = SubscriptionHistory::create([
   //          "user_id"=>$user_id,
   //          "txn_id"=>$txn_id_insert,
   //          "package_id"=>$package_id,
   //          "subs_type"=>$subs_type,
   //          "subs_sub_type"=>$subs_sub_type,
   //          "subs_sub_type_id"=>$subs_sub_type_id,
   //          "amount"=>$amount,
   //          "new_amount"=>$new_amount,
   //          "coupon_code"=>'IAS GYAN',
   //          "discount"=>$coupon_discount,
   //          "paid_amount"=>($amount - $coupon_discount),
   //          "start_date"=>$start_date,
   //          "end_date"=>$end_date,
   //          "description"=>json_encode($desc),
   //      ]);
   //     return redirect()->back()->with('success','Subscription Added Successfully');

   // }


    public function store(Request $request)
    {
        $user_id=$request->user_id;
        $topic_id=$request->topic_id;

        $topic = Topic::where('id',$topic_id)->first();
        $duration = $topic->duration;
        $course_id = $topic->course_id;
        $subject_id = $topic->subject_id;
        $subscription_amount = $topic->subscription_amount;
        $description = $topic->description;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+".$duration." days"));
        $package_id = $request->topic_id;


        // $subscr_type = SubscriptionType::find($type_id);
        // $board_id = 0;
        // $amount = $subscr_type->new_amount;
        // $new_amount =0;
        // $coupon_discount = 0;
        // $online_amount = 0;
        // $package_id = $request->topic_id;
        // $subs_type = $subscr_type->title;
        // $subs_sub_type = 'course';
        // $subs_sub_type_id =  $subscr_type->type_id;
        // $start_date = $subscr_type->start_date;
        // $end_date = $subscr_type->end_date;
        // $desc = array();





        $txn_id_insert = 0;



        $desc[] = array(
            "id"=>$txn_id_insert,
            "type"=>'program',
            "startDate"=>$start_date,
            "endDate"=>$end_date,
            "coursePrice"=>$subscription_amount,
            "couponDiscount"=>0,
            "walletAmount"=>0,
            "payableAmount"=>$subscription_amount,
            "amountToPay"=>$subscription_amount,
            "couponCode"=>"IASGYAN"
        );
        $insert_subscription = SubscriptionHistory::create([
            "user_id"=>$user_id,
            "txn_id"=>$txn_id_insert,
            "package_id"=>$package_id,
            "board_id"=>$course_id,
            "subject_id"=>$subject_id,
            "topic_id"=>$topic_id,
            "subs_type"=>'Course',
            "subs_sub_type"=>"program",
            "subs_sub_type_id"=>$topic_id,
            "amount"=>$subscription_amount,
            "new_amount"=>"0.00",
            "coupon_code"=>'IAS GYAN',
            "discount"=>0,
            "paid_amount"=>$subscription_amount,
            "start_date"=>$start_date,
            "end_date"=>$end_date,
            "description"=>json_encode($desc),
        ]);


        return redirect()->back()->with('success','Subscription Added Successfully');

    }


    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data= DB::table('subscription_histories')
        ->select('subscription_histories.*','subscription_types.title as type_title','users.name','users.phone')
        ->leftjoin('subscription_types','subscription_histories.package_id','=','subscription_types.id')
        ->leftjoin('users','subscription_histories.user_id','=','users.id')
        ->where(['user_id'=>$id])
        ->get();


        $subcription = SubscriptionType::where(['status'=>'Y'])->get();


        $user = AppUser::where(['id'=>$id])->first();
        $topic_ids = [];
        $today = date('Y-m-d');
        $subscription_history = SubscriptionHistory::where('user_id',$id)->where('end_date','<',$today)->get();
        if(!empty($subscription_history)){
            foreach($subscription_history as $sub){
                $topic_ids[] = $sub->topic_id;
            }
        }

        $topics = Topic::where('is_delete',0)->orderby('id','desc');

        if(!empty($topic_ids)){
            $topics->whereNotIn('id',$topic_ids);
        }

        $topics  = $topics->get();

        return  view('admin/users/show',array('data'=>$data,'id'=>$id,'subcription'=>$subcription,'topics'=>$topics));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = AppUser::where(['id'=>$id])->first();
        $board = Boards::latest()->get();
        $city = City::latest()->get();
        $state = State::latest()->get();
        return  view('admin/users/edit',array('data'=>$data,'board'=>$board,'city'=>$city,'state'=>$state));
    }


    public function user_subcription(Request $request)
    {
        $request->validate([
            'id'=>'required',
            'end_date'=>'required'
        ]);
        $id = $request->input('id');
        $user_id = $request->input('user_id');
        $end_date = $request->input('end_date');
        SubscriptionHistory::where(['id'=>$id])->update(['end_date'=>$end_date]);
        return redirect()->route('app_users.show', $user_id)
        ->with('success','Subscription Updated successfully.');
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request)
    {
     $request->validate([
        'name' =>'required',
        'email'=>'required',
        'phone'=>'required',
       // 'board_id'=>'required',
       // 'dob'=>'required',
        'id'=>'required',
        'status'=>'required',
    ]);
     $id = $request->input('id');
     $data = array(
        'name'=>request('name'),
        'email'=>request('email'),
        'phone'=>request('phone'),
        'board_id'=>request('board_id'),
        'date_of_birth'=>request('dob'),
        'login_enabled'=>request('status'),   
    );

     AppUser::where(['id'=>$id])->update($data);
     return redirect()->route('app_users.index')
     ->with('success','Record Updated successfully.');
 }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        AppUser::where('id',$id)->delete();
        return redirect()->route('app_users.index')
        ->with('success','Record Delete successfully.');
    }


    public function deleteSubcription($id)
    {
        SubscriptionHistory::where('id',$id)->delete();
        return redirect()->back()->with('success','Subscription Delete Successfully');

    }

    public function usersGenerator()
    {
      foreach (AppUser::cursor() as $user) {
          yield $user;
      }
  }


  public function export(Request $request){
    ini_set ('memory_limit','-1');
    set_time_limit (0);
    $users = AppUser::select('id','name','email','phone')->get();
    if(!empty($users) && $users->count() > 0){

        foreach($users as $user){
           //  if(!empty($user->state_id)){
           //      $state = State::where('id',$user->state_id)->first();

           //  }
           //  if(!empty($user->city_id)){
           //      $city = City::where('id',$user->city_id)->first();


           //  }


           //  $exist = DB::table('prime')->where('user_id',$user->id)->first();
           //  $prime = 0;
           //  if(!empty($exist)) { 
           //     $prime = 1;
           // }

           $userArr = [];
           $userArr['ID'] = $user->id;
           $userArr['Name'] = $user->name ?? '';
           $userArr['Email'] = $user->email ?? '';
           $userArr['Phone'] = $user->phone ?? '';
           //   $userArr['State'] = $state->name ?? '';
           //   $userArr['City'] = $city->name ?? '';
           // $userArr['Prime'] = $prime;
           $exportArr[] = $userArr;
       }

       $filedNames = array_keys($exportArr[0]);

       $fileName = 'users_'.date('Y-m-d-H-i-s').'.xlsx';
       return Excel::download(new UserExport($exportArr, $filedNames), $fileName);
   }

//return Excel::download(new UserExport, 'users.xlsx');


}






public function get_prime(Request $request){
    $user_id = isset($request->user_id) ? $request->user_id : 0;
    $status = isset($request->status) ? $request->status : 0;

    if($status == 1){
        $exist = DB::table('prime')->where('user_id',$user_id)->first();
        if(empty($exist)){
            $dbArr = [];
            $dbArr['user_id'] = $user_id;
            $dbArr['txn_id'] = '';
            DB::table('prime')->insert($dbArr);

            echo json_encode(array('status'=>true,'message'=>'Added to Prime User Successfully'));
        }

    }else{
     $exist = DB::table('prime')->where('user_id',$user_id)->first();
     if(!empty($exist)){
        DB::table('prime')->where('user_id',$user_id)->delete();
        echo json_encode(array('status'=>true,'message'=>'Remove From Prime User Successfully'));

    }

}



                //echo json_encode(array('status'=>true,'message'=>'Something Went Wrong'));



}




}
