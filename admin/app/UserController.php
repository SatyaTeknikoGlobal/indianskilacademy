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
use Carbon\Carbon;
use Yajra\DataTables\DataTables;

class UserController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       return  view('admin/users/list');
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */


    public function getUsers(Request $request)
    {


        $search_data = $request->search['value'];

        if(!empty($search_data)) {
        $data= DB::table('users')
        ->select('users.*','boards.board_name','states.name as state_name','cities.name as city_name','user_wallets.wallet')
        ->leftjoin('boards','users.board_id','=','boards.id')
        ->leftjoin('states','users.state_id','=','states.id')
        ->leftjoin('cities','users.city_id','=','cities.id')
        ->leftjoin('user_wallets','users.id','=','user_wallets.user_id')

        ->where('users.name', 'LIKE', "%{$search_data}%")
        ->orwhere('users.phone', 'LIKE', "%{$search_data}%")
        ->orwhere('users.email', 'LIKE', "%{$search_data}%")
        ->orwhere('cities.name', 'LIKE', "%{$search_data}%") 

        ->orderBy('users.id','DESC')
        ->get();
        }
        else{
             $data= DB::table('users')
        ->select('users.*','boards.board_name','states.name as state_name','cities.name as city_name','user_wallets.wallet')
        ->leftjoin('boards','users.board_id','=','boards.id')
        ->leftjoin('states','users.state_id','=','states.id')
        ->leftjoin('cities','users.city_id','=','cities.id')
        ->leftjoin('user_wallets','users.id','=','user_wallets.user_id')

        ->orderBy('users.id','DESC')
        ->get();
        }
        
        return Datatables::of($data)
            ->filter(function ($instance) use ($request) {

            })
            ->make(true);
    }
    public function create()
    {
        
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

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $user_id=$request->user_id;
        $type_id=$request->type;
        $subscr_type = SubscriptionType::find($type_id);
        $board_id = 0;
        $amount = $subscr_type->new_amount;
        $new_amount =0;
        $coupon_discount = 0;
        $online_amount = 0;
        $package_id = $subscr_type->id;
        $subs_type = $subscr_type->title;
        $subs_sub_type = 'course';
        $subs_sub_type_id =  $subscr_type->type_id;
        $start_date = $subscr_type->start_date;
        $end_date = $subscr_type->end_date;
        $desc = array();
        $txn_id_insert = 0;
        $desc[] = array(
            "id"=>$txn_id_insert,
            "type"=>'course',
            "startDate"=>$type_id,
            "endDate"=>$user_id,
            "coursePrice"=>$subs_type,
            "couponDiscount"=>$coupon_discount,
            "walletAmount"=>0,
            "payableAmount"=>$amount,
            "amountToPay"=>0,
            "couponCode"=>""
        );
        $insert_subscription = SubscriptionHistory::create([
            "user_id"=>$user_id,
            "txn_id"=>$txn_id_insert,
            "package_id"=>$package_id,
            "subs_type"=>$subs_type,
            "subs_sub_type"=>$subs_sub_type,
            "subs_sub_type_id"=>$subs_sub_type_id,
            "amount"=>$amount,
            "new_amount"=>$new_amount,
            "coupon_code"=>'AGRI COACHING',
            "discount"=>$coupon_discount,
            "paid_amount"=>($amount - $coupon_discount),
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
       return  view('admin/users/show',array('data'=>$data,'id'=>$id,'subcription'=>$subcription));
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
        'board_id'=>'required',
        'dob'=>'required',
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
}
