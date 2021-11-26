<?php

namespace App\Http\Controllers;

use App\Course;
use App\Http\Controllers\Discussion\TopicController;
use App\SubscriptionHistory;
use App\SubscriptionPackage;
use App\SubscriptionType;
use App\Topic;
use App\TransactionHistory;
use App\UserWalletPointHistory;
use JWTAuth;
use App\User;
use Illuminate\Http\Request;
use Tymon\JWTAuth\Exceptions\JWTException;
use Validator;
use Illuminate\support\str;
use App\UserWallet;
use App\Boards;
use App\Subject;
use App\Content;
use App\Coupon;
use Razorpay\Api\Api;

class SubscriptionController extends Controller
{
    //
    public function subscription_type(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
        ]);
        $content = array();
        if ($validator->fails()) {
            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content' =>$content,
                'subStatus'=>""
            ],400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'content' =>$content,
                'subStatus'=>""
            ],401);
        }
        $boardId = $user->board_id;
        if($request->has('board_id') && $request->board_id != '' && $request->board_id != 0){
            $boardId = $request->board_id;
        }
        $content = SubscriptionType::where(['status'=>'Y','type_id'=>$boardId])->get();


        foreach ($content as $c) {
            $c->remaining_time = strtotime($c->end_date) - time();
        }
        return response()->json([
            'result' => 'success',
            'message' => '',
            'content' =>$content,
            'subStatus'=>$this->_checkCourseSubscription($boardId,$user->id),
        ],200);
    }

    private function _checkCourseSubscription($courseId,$studentId){
        $expired_on = "2020-05-31";
        //check in course subscription
        $subscription = SubscriptionHistory::where(['user_id'=>$studentId,'subs_sub_type_id'=>$courseId])
        ->whereDate('start_date', '<=', date('Y-m-d'))
        ->whereDate('end_date', '>=', date('Y-m-d'))
        ->orderBy('end_date','desc')->first();
//        var_dump(['user_id'=>$student_id,'subs_sub_type_id'=>$courses]);
        if (!empty($subscription)){
            return $subscription->end_date;
        }
        return "";
    }

    public function couponList(Request $request)
    {
       $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
       $content = array();
       if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'content' =>$content,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'content' =>$content,
        ],401);
    }
    $content = Coupon::where(['status'=>1])->get();
    return response()->json([
        'result' => 'success',
        'message' => '',
        'content' =>$content,
    ],200);
}


public function apply_coupon(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'coupon_code'=>'required',
    ]);
    $content = array();
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'content' =>$content,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'content' =>$content,
        ],401);
    }
    $coupon_code = $request->coupon_code;
    $content = Coupon::where(['coupon_code'=>$coupon_code])->first();
    if (empty($content)){
        return response()->json([
            'result' => 'failure',
            'message' => 'Invalid Coupon Code',
            'content' =>$content,
        ],200);
    }
      //  $content = 5;
    return response()->json([
        'result' => 'success',
        'message' => '',
        'content' =>$content,
    ],200);
}
public function subscription_packages(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'type_id'=>'required',
    ]);
    $content = array();
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'content' =>$content,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'content' =>$content,
        ],401);
    }
    $type_id = $request->type_id;
    $content = SubscriptionPackage::where(['type_id'=>$type_id,'status'=>'Y'])->get();
    return response()->json([
        'result' => 'success',
        'message' => '',
        'content' =>$content,
    ],200);
}

public function check_subscription(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type_id'=>'required',
    'type'=>'required',
]);
   $content = array();
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'content' =>$content,
    ],400);
}
$user = JWTAuth::parseToken()->authenticate();
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
        'content' =>$content,
    ],401);
}
$type_id = $request->type_id;
$type = $request->type;

if($type == 'video' || $type == 'notes'){

    $contents = Content::where('id',$type_id)->first();
    $topic_id = $contents->topic_id;
    $topics = Topic::where('id',$topic_id)->first();
    $boards = Boards::where('id',$contents->board_id)->first();
    $subject = Subject::where('id',$contents->subject_id)->first();

    $topics->board_name = isset($boards->board_name) ? $boards->board_name :'';
    $topics->subject_name = isset($subject->name) ? $subject->name :'';

    $content = $topics;

 }
 //if($type == 'notes'){

// }



return response()->json([
    'result' => 'success',
    'message' => '',
    'content' =>$content,
],200);
}

public function apply_coupon_code(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'coupon_code'=>'required',
    ]);
    $content = 0;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'content' =>$content,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'content' =>$content,
        ],401);
    }
    $coupon_code = $request->coupon_code;
    return response()->json([
        'result' => 'failure',
        'message' => 'Invalid Coupon Code',
        'content' =>$content,
    ],200);
    $referrer = User::where(['slug'=>$coupon_code])->where('id','!=',$user->id)->first();
    if (empty($referrer)){
        return response()->json([
            'result' => 'failure',
            'message' => 'Invalid Coupon Code',
            'content' =>$content,
        ],200);
    }
    $content = 5;
    return response()->json([
        'result' => 'success',
        'message' => '',
        'content' =>$content,
    ],200);
}

public function user_wallet(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $wallet = 0.0;
    $points = 0.0;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'wallet' =>$wallet,
            'points' =>$points,
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'wallet' =>$wallet,
            'points' =>$points,
        ],401);
    }

    $user_wallet = User::where(['id'=>$user->id])->first();
    if(!empty($user_wallet)){
        $wallet = $user_wallet->wallet;
        //$points = $user_wallet->points;
    }
    return response()->json([
        'result' => 'success',
        'message' => '',
        'wallet' =>$wallet,
        'points' =>$points,
    ],200);

}

public function purchase_subscription(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'amount' => 'required',
        'new_amount' => 'required',
        'package_id' => 'required',
        'subs_type' => 'required',
        'subs_sub_type' => 'required',
        'subs_sub_type_id' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);
    }
    $amount = $request->amount;
    $new_amount = $request->new_amount;
    $used_points = $request->used_points;
    $used_wallet = $request->used_wallet;
    $coupon_code = $request->coupon_code;
    $coupon_discount = $request->coupon_discount;
    $online_amount = $request->online_amount;
    $txn_id = $request->txn_id;
    $package_id = $request->package_id;
    $subs_type = $request->subs_type;
    $subs_sub_type = $request->subs_sub_type;
    $subs_sub_type_id = $request->subs_sub_type_id;
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $desc = array();
    $txn_id_insert = 0;

    if ($online_amount > 0)
    {
        $api = new Api(env("RAZORPAY_APIKEY"),env("RAZORPAY_SECRET"));
        $payment = $api->payment->fetch($txn_id)->capture(array('amount'=>$online_amount * 100, 'currency'=>'INR'));
        if (!isset($payment['status']) || $payment['status'] != 'captured'){
            return response()->json([
                'result' => 'failure',
                'message' => 'Payment Failed ,the amount will be refunded automatically.',
            ],200);
        }

        $txn_insert = TransactionHistory::create([
            "purpose"=>'Subscription',
            "txn_no"=>$txn_id,
            "method"=>'ONLINE',
            "gateway"=>'Razor Pay',
            "paid_by"=>$user->id,
            "amount"=>$online_amount,
        ]);
        $txn_id_insert = $txn_insert->id;
        $desc[] = array(
            "txn_no"=>$txn_insert->id,
            "method"=>'ONLINE',
            "gateway"=>'Razor Pay',
            "paid_by"=>$user->id,
            "amount"=>$online_amount,
        );
    }

    if ($used_points > 0)
    {
        $txn_insert = UserWalletPointHistory::create([
            "user_id"=>$user->id,
            "points"=>$used_points,
            "type"=>'DEBIT',
            "remarsks"=>"$used_points points used for Subscription"
        ]);
        $desc[] = array(
            "txn_no"=>$txn_insert->id,
            "method"=>'POINTS',
            "gateway"=>'Point',
            "paid_by"=>$user->id,
            "amount"=>$used_points,
        );
    }

    if ($used_wallet > 0){
        $txn_insert = TransactionHistory::create([
            "purpose"=>'Subscription',
            "txn_no"=>$txn_id,
            "method"=>'WALLET',
            "gateway"=>'wallet',
            "paid_by"=>$user->id,
            "amount"=>$used_wallet,
        ]);
        if ($txn_id_insert == 0){
            $txn_id_insert = $txn_insert->id;
        }
        $desc[] = array(
            "txn_no"=>$txn_insert->id,
            "method"=>'WALLET',
            "gateway"=>'wallet',
            "paid_by"=>$user->id,
            "amount"=>$used_wallet,
        );
    }

    $user_wallet = UserWallet::where(['user_id'=>$user->id])->first();
    if (empty($user_wallet)){
        UserWallet::create([
            "user_id"=>$user->id,
            "wallet"=> 0,
            "points"=> 0,
        ]);
    }else{
        $user_wallet->wallet -= $used_wallet;
        $user_wallet->points -= $used_points;
        $user_wallet->save();
    }

    $insert_subscription = SubscriptionHistory::create([
        "user_id"=>$user->id,
        "txn_id"=>$txn_id_insert,
        "package_id"=>$package_id,
        "subs_type"=>$subs_type,
        "subs_sub_type"=>$subs_sub_type,
        "subs_sub_type_id"=>$subs_sub_type_id,
        "amount"=>$amount,
        "new_amount"=>$new_amount,
        "coupon_code"=>$coupon_code,
        "discount"=>$coupon_discount,
        "paid_amount"=>($new_amount - $coupon_discount),
        "start_date"=>date("Y-m-d",strtotime($start_date)),
        "end_date"=>date("Y-m-d",strtotime($end_date)),
        "description"=>json_encode($desc),
    ]);
        // if ($coupon_code != ""){
        //     $referrer = User::where(['slug'=>$coupon_code])->where('id','!=',$user->id)->first();
        //     if (!empty($referrer)){
        //         $cash_back = $new_amount / 10;
        //         TransactionHistory::create([
        //             "purpose"=>'Subscription CashBack',
        //             "txn_no"=>$user->id,
        //             "method"=>'Cashback',
        //             "gateway"=>'Cashback',
        //             "paid_by"=>$referrer->id,
        //             "amount"=>$cash_back,
        //         ]);
        //         $referrer_wallet = UserWallet::where(['user_id'=>$referrer->id])->first();
        //         if (empty($referrer_wallet)){
        //             UserWallet::create([
        //                 "user_id"=>$referrer->id,
        //                 "wallet"=> $cash_back,
        //                 "points"=> 0,
        //             ]);
        //         }else{
        //             $referrer_wallet->wallet += $cash_back;
        //             $referrer_wallet->save();
        //         }
        //     }
        // }
    return response()->json([
        'result' => 'success',
        'message' => 'Subscribed Successfully',
    ],200);

}

public function purchase_subscription_new(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'amount' => 'required',
        'new_amount' => 'required',
        'package_id' => 'required',
        'subs_type' => 'required',
        'subs_sub_type' => 'required',
        'subs_sub_type_id' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);
    }
    $amount = $request->amount;
    $new_amount = $request->new_amount;
    $used_points = $request->used_points;
    $used_wallet = $request->used_wallet;
    $coupon_code = $request->coupon_code;
    $coupon_discount = $request->coupon_discount;
    $online_amount = $request->online_amount;
    $txn_id = $request->txn_id;
    $package_id = $request->package_id;
    $subs_type = $request->subs_type;
    $subs_sub_type = $request->subs_sub_type;
    $subs_sub_type_id = $request->subs_sub_type_id;
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $desc = array();
    $txn_id_insert = 0;

    if ($online_amount > 0)
    {
        $api = new Api(env("RAZORPAY_APIKEY_NEW"),env("RAZORPAY_SECRET_NEW"));
        $payment = $api->payment->fetch($txn_id)->capture(array('amount'=>$online_amount * 100, 'currency'=>'INR'));
        if (!isset($payment['status']) || $payment['status'] != 'captured'){
            return response()->json([
                'result' => 'failure',
                'message' => 'Payment Failed ,the amount will be refunded automatically.',
            ],200);
        }

        $txn_insert = TransactionHistory::create([
            "purpose"=>'Subscription',
            "txn_no"=>$txn_id,
            "method"=>'ONLINE',
            "gateway"=>'Razor Pay',
            "paid_by"=>$user->id,
            "amount"=>$online_amount,
        ]);
        $txn_id_insert = $txn_insert->id;
        $desc[] = array(
            "txn_no"=>$txn_insert->id,
            "method"=>'ONLINE',
            "gateway"=>'Razor Pay',
            "paid_by"=>$user->id,
            "amount"=>$online_amount,
        );
    }

    if ($used_points > 0)
    {
        $txn_insert = UserWalletPointHistory::create([
            "user_id"=>$user->id,
            "points"=>$used_points,
            "type"=>'DEBIT',
            "remarsks"=>"$used_points points used for Subscription"
        ]);
        $desc[] = array(
            "txn_no"=>$txn_insert->id,
            "method"=>'POINTS',
            "gateway"=>'Point',
            "paid_by"=>$user->id,
            "amount"=>$used_points,
        );
    }

    if ($used_wallet > 0){
        $txn_insert = TransactionHistory::create([
            "purpose"=>'Subscription',
            "txn_no"=>$txn_id,
            "method"=>'WALLET',
            "gateway"=>'wallet',
            "paid_by"=>$user->id,
            "amount"=>$used_wallet,
        ]);
        if ($txn_id_insert == 0){
            $txn_id_insert = $txn_insert->id;
        }
        $desc[] = array(
            "txn_no"=>$txn_insert->id,
            "method"=>'WALLET',
            "gateway"=>'wallet',
            "paid_by"=>$user->id,
            "amount"=>$used_wallet,
        );
    }

    $user_wallet = UserWallet::where(['user_id'=>$user->id])->first();
    if (empty($user_wallet)){
        UserWallet::create([
            "user_id"=>$user->id,
            "wallet"=> 0,
            "points"=> 0,
        ]);
    }else{
        $user_wallet->wallet -= $used_wallet;
        $user_wallet->points -= $used_points;
        $user_wallet->save();
    }

    $insert_subscription = SubscriptionHistory::create([
        "user_id"=>$user->id,
        "txn_id"=>$txn_id_insert,
        "package_id"=>$package_id,
        "subs_type"=>$subs_type,
        "subs_sub_type"=>$subs_sub_type,
        "subs_sub_type_id"=>$subs_sub_type_id,
        "amount"=>$amount,
        "new_amount"=>$new_amount,
        "coupon_code"=>$coupon_code,
        "discount"=>$coupon_discount,
        "paid_amount"=>($new_amount - $coupon_discount),
        "start_date"=>date("Y-m-d",strtotime($start_date)),
        "end_date"=>date("Y-m-d",strtotime($end_date)),
        "description"=>json_encode($desc),
    ]);
        // if ($coupon_code != ""){
        //     $referrer = User::where(['slug'=>$coupon_code])->where('id','!=',$user->id)->first();
        //     if (!empty($referrer)){
        //         $cash_back = $new_amount / 10;
        //         TransactionHistory::create([
        //             "purpose"=>'Subscription CashBack',
        //             "txn_no"=>$user->id,
        //             "method"=>'Cashback',
        //             "gateway"=>'Cashback',
        //             "paid_by"=>$referrer->id,
        //             "amount"=>$cash_back,
        //         ]);
        //         $referrer_wallet = UserWallet::where(['user_id'=>$referrer->id])->first();
        //         if (empty($referrer_wallet)){
        //             UserWallet::create([
        //                 "user_id"=>$referrer->id,
        //                 "wallet"=> $cash_back,
        //                 "points"=> 0,
        //             ]);
        //         }else{
        //             $referrer_wallet->wallet += $cash_back;
        //             $referrer_wallet->save();
        //         }
        //     }
        // }
    return response()->json([
        'result' => 'success',
        'message' => 'Subscribed Successfully',
    ],200);

}

public function purchase_subscription1(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'userID' => 'required',
        'amount' => 'required',
        'new_amount' => 'required',
        'package_id' => 'required',
        'subs_type' => 'required',
        'subs_sub_type' => 'required',
        'subs_sub_type_id' => 'required',
        'start_date' => 'required',
        'end_date' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
        ],400);
    }
    $user = User::find($request->userID);
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);
    }
    $amount = $request->amount;
    $new_amount = $request->new_amount;
    $coupon_discount = $request->coupon_discount;
    $online_amount = $request->online_amount;
    $package_id = $request->package_id;
    $subs_type = $request->subs_type;
    $subs_sub_type = $request->subs_sub_type;
    $subs_sub_type_id = $request->subs_sub_type_id;
    $start_date = $request->start_date;
    $end_date = $request->end_date;

    $desc = array();
    $txn_id_insert = 0;

    $txn_insert = TransactionHistory::create([
        "purpose"=>'Subscription',
        "txn_no"=>'easy_'.time(),
        "method"=>'EASY PHYSICS',
        "gateway"=>'EASY PHYSICS',
        "paid_by"=>$user->id,
        "amount"=>$online_amount,
    ]);
    $txn_id_insert = $txn_insert->id;
    $desc[] = array(
        "txn_no"=>$txn_insert->id,
        "method"=>'EASY PHYSICS',
        "gateway"=>'EASY PHYSICS',
        "paid_by"=>$user->id,
        "amount"=>$online_amount,
    );

    $insert_subscription = SubscriptionHistory::create([
        "user_id"=>$user->id,
        "txn_id"=>$txn_id_insert,
        "package_id"=>$package_id,
        "subs_type"=>$subs_type,
        "subs_sub_type"=>$subs_sub_type,
        "subs_sub_type_id"=>$subs_sub_type_id,
        "amount"=>$amount,
        "new_amount"=>$new_amount,
        "coupon_code"=>'EASY PHYSICS',
        "discount"=>$coupon_discount,
        "paid_amount"=>($new_amount - $coupon_discount),
        "start_date"=>date("Y-m-d",strtotime($start_date)),
        "end_date"=>date("Y-m-d",strtotime($end_date)),
        "description"=>json_encode($desc),
    ]);
    return response()->json([
        'result' => 'success',
        'message' => 'Subscribed Successfully',
    ],200);

}

public function subscripton_history(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $content = array();
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'content' => $content
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'content' => $content
        ],401);
    }
    $content = SubscriptionHistory::where(['user_id'=>$user->id])->latest()->get();
    foreach ($content as $c){
        $c->subscription_sub_title = $this->get_subscription_sub_title($c->subs_sub_type,$c->subs_sub_type_id);
    }
    return response()->json([
        'result' => 'success',
        'message' => '',
        'content' => $content
    ],200);

}

private function get_subscription_sub_title($type,$id){
    switch ($type) {
        case 'course':
        $course = Course::find($id);
        $title = $course->title;
        break;
        case 'crash_course':
        $course = Course::find($id);
        $title = $course->title;
        break;
        case 'topic':
        $course = Topic::find($id);
        $title = $course->title;
        break;
        default:
        $title = '';
    }
    return $title;
}

public function webhook(Request $request){

}






}
