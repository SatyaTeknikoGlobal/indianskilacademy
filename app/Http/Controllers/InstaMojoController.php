<?php

namespace App\Http\Controllers;

use App\Instamojo;
use App\Order;
use App\Topic;
use App\Board;
use App\Subject;
use App\User;
use App\SubscriptionHistory;
use App\TransactionHistory;
use App\UserWallet;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Razorpay\Api\Api;
use DB;
use Mail;






class InstaMojoController extends Controller
{
    //
    public function createPayment(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'instamojoId' => 'required',
            'payload' => 'required',
            'data' => 'required',
            'type' => 'required',
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
                'message' => ''
            ],401);
        }
        $userId = $user->id;
        Instamojo::create([
            "instamojo_id"=>$request->instamojoId,
            "user_id"=>$userId,
            "type"=>$request->type,
            "data"=>$request->data,
            "payload"=>$request->payload,
            "payment_status"=>'CREATED'
        ]);
        return response()->json([
            'result' => 'success',
            'message' => 'Payment Created'
        ],200);
    }

    public function webHook(Request $request)
    {
        $payment_request_id = $request->payment_request_id;
        $status = $request->status;
        $instamojo = Instamojo::where(['instamojo_id'=>$payment_request_id])->first();
        if(!empty($instamojo)){
            $instamojo->payment_status = $status;
            $instamojo->webhook_response = json_encode($_POST);
            $instamojo->save();
            if($status == 'Credit'){
                if($instamojo->type == "Add Wallet"){
                    $txn_insert = TransactionHistory::create([
                        "purpose"=>'Add Wallet',
                        "txn_no"=>time(),
                        "method"=>'Instamojo',
                        "gateway"=>'Instamojo',
                        "paid_by"=>$instamojo->user_id,
                        "amount"=>$request->amount - $request->fees,
                    ]);

                    $user_wallet = UserWallet::where(['user_id'=>$instamojo->user_id])->first();
                    if (empty($user_wallet)){
                        UserWallet::create([
                            "user_id"=>$instamojo->user_id,
                            "wallet"=> $request->amount - $request->fees,
                            "points"=> 0,
                        ]);
                    }else{
                        $user_wallet->wallet += $request->amount - $request->fees;
                        $user_wallet->save();
                    }

                    return;
                }
                $data = json_decode($instamojo->data);
                if($data->usableWallet > 0){
                    TransactionHistory::create([
                        "purpose"=>'Subscription',
                        "txn_no"=>time(),
                        "method"=>'WALLET',
                        "gateway"=>'wallet',
                        "paid_by"=>$instamojo->user_id,
                        "amount"=>$data->usableWallet,
                    ]);
                }
                $txn_insert = TransactionHistory::create([
                    "purpose"=>'Subscription',
                    "txn_no"=>time(),
                    "method"=>'Instamojo',
                    "gateway"=>'Instamojo',
                    "paid_by"=>$instamojo->user_id,
                    "amount"=>$request->amount - $request->fees,
                ]);
                $user_wallet = UserWallet::where(['user_id'=>$instamojo->user_id])->first();
                if (empty($user_wallet)){
                    UserWallet::create([
                        "user_id"=>$instamojo->user_id,
                        "wallet"=> 0,
                        "points"=> 0,
                    ]);
                }else{
                    $user_wallet->wallet -= $data->usableWallet;
                    $user_wallet->save();
                }
                if($instamojo->type == "Subscription"){
                    $insert_subscription = SubscriptionHistory::create([
                        "user_id"=>$instamojo->user_id,
                        "txn_id"=>$txn_insert->id,
                        "package_id"=>$data->id,
                        "subs_type"=>$data->title,
                        "subs_sub_type"=>$data->type,
                        "subs_sub_type_id"=>$data->typeId,
                        "amount"=>$data->coursePrice,
                        "new_amount"=>$data->amountToPay,
                        "coupon_code"=>$data->couponCode,
                        "discount"=>$data->couponDiscount,
                        "paid_amount"=>$data->payableAmount,
                        "start_date"=>date("Y-m-d",strtotime($data->startDate)),
                        "end_date"=>date("Y-m-d",strtotime($data->endDate)),
                        "description"=>json_encode($data),
                    ]);
                }elseif ($instamojo->type == "Book Purchase"){
                    Order::create([
                        "user_id"=>$instamojo->user_id,
                        "person_name"=>$data->person_name,
                        "phone"=>$data->phone,
                        "address1"=>$data->address1,
                        "address2"=>$data->address2,
                        "pin"=>$data->pin,
                        "city"=>$data->city,
                        "state"=>$data->state,
                        "country"=>$data->country,
                        "item_price"=>$data->item_price,
                        "coupon_code"=>$data->coupon_code,
                        "coupon_disc"=>$data->coupon_disc,
                        "delivery_charges"=>$data->delivery_charges,
                        "txn_id"=>$txn_insert->id,
                        "grand_total"=>$data->grand_total,
                        "payment_method"=>"Instamojo",
                        "items"=>json_encode($data->items),
                        "status"=>"PLACED",
                    ]);
                }

            }
        }
    }

    public function redirectUrl(Request $request)
    {
        return response()->json([
            'result' => 'success',
            'message' => 'Success',
        ],200);
    }

    public function subscribeWallet(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required'
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
                'message' => ''
            ],401);
        }
        $userId = $user->id;
        $id = $request->id;
        $type = $request->type;
        $typeId = $request->typeId;
        $startDate = $request->startDate;
        $endDate = $request->endDate;
        $title = $request->title;
        $coursePrice = $request->coursePrice;
        $couponDiscount = $request->couponDiscount;
        $usableWallet = $request->usableWallet;
        $walletAmount = $request->walletAmount;
        $payableAmount = $request->payableAmount;
        $amountToPay = $request->amountToPay;
        $couponCode = $request->couponCode;
        $txn_insert = TransactionHistory::create([
            "purpose"=>'Subscription',
            "txn_no"=>time(),
            "method"=>'WALLET',
            "gateway"=>'wallet',
            "paid_by"=>$userId,
            "amount"=>$usableWallet,
        ]);
        $user_wallet = UserWallet::where(['user_id'=>$user->id])->first();
        if (empty($user_wallet)){
            UserWallet::create([
                "user_id"=>$user->id,
                "wallet"=> 0,
                "points"=> 0,
            ]);
        }else{
            $user_wallet->wallet -= $usableWallet;
            $user_wallet->save();
        }
        $insert_subscription = SubscriptionHistory::create([
            "user_id"=>$userId,
            "txn_id"=>$txn_insert->id,
            "package_id"=>$id,
            "subs_type"=>$title,
            "subs_sub_type"=>$type,
            "subs_sub_type_id"=>$typeId,
            "amount"=>$coursePrice,
            "new_amount"=>$amountToPay,
            "coupon_code"=>$couponCode,
            "discount"=>$couponDiscount,
            "paid_amount"=>$payableAmount,
            "start_date"=>date("Y-m-d",strtotime($startDate)),
            "end_date"=>date("Y-m-d",strtotime($endDate)),
            "description"=>json_encode([
                "id" => $request->id,
                "type" => $request->type,
                "typeId" => $request->typeId,
                "startDate" => $request->startDate,
                "endDate" => $request->endDate,
                "title" => $request->title,
                "coursePrice" => $request->coursePrice,
                "couponDiscount" => $request->couponDiscount,
                "usableWallet" => $request->usableWallet,
                "walletAmount" => $request->walletAmount,
                "payableAmount" => $request->payableAmount,
                "amountToPay" => $request->amountToPay,
                "couponCode" => $request->couponCode,
            ]),
        ]);
        return response()->json([
            'result' => 'success',
            'message' => 'Subscribed Successfully'
        ],200);
    }

    public function mySubscriptions(Request $request){
        $validator =  Validator::make($request->all(), [
            'token' => 'required'
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
        $content = SubscriptionHistory::where([
            "user_id"=>$user->id,
        ])->with('package')->latest()->get();
        if(!empty($content)){
            foreach($content as $con){
                $topic = Topic::select('id','name')->where('id',$con->topic_id)->first();
                $subject = Subject::select('id','title')->where('id',$con->subject_id)->first();
                $board = Board::select('id','board_name')->where('id',$con->board_id)->first();

                $con->board_name = $board->board_name;
                $con->subject_name = $subject->title;
                $con->topic_name = $topic->name;
            }
        }

        return response()->json([
            'result' => 'success',
            'message' => '',
            'content' => $content
        ],200);
    }


/////////////////////////////////////////Razor PAY///////////////////////////////////////


    public function get_curl_handle($payment_id, $amount)  {


        // $payment_id = '';
        // $amount = '';

        // $payment_id, $amount
        // $url = 'https://api.razorpay.com/v1/payments/'.$payment_id.'/capture';
        // $key_id = 'rzp_test_fILqMZF2Hxd2ZN';
        // $key_secret = 'oeoYgYLuqktspmrAEdN9eplG';
        // $fields_string = "amount=$amount";

        // $ch = curl_init();

        // //set the url, number of POST vars, POST data

        // curl_setopt($ch, CURLOPT_URL, $url);

        // curl_setopt($ch, CURLOPT_USERPWD, $key_id.':'.$key_secret);

        // curl_setopt($ch, CURLOPT_TIMEOUT, 60);

        // curl_setopt($ch, CURLOPT_POST, 1);

        // curl_setopt($ch, CURLOPT_POSTFIELDS, $fields_string);

        // curl_setopt($ch, CURLOPT_RETURNTRANSFER, true);

        // curl_setopt($ch, CURLOPT_SSL_VERIFYPEER, true);

        // curl_setopt($ch, CURLOPT_CAINFO, dirname(__FILE__).'/ca-bundle.crt');

        // return $ch;

      //   $curl = curl_init();

      //   curl_setopt_array($curl, array(
      //     CURLOPT_URL => 'https://api.razorpay.com/v1/payments/'.$payment_id.'/capture',
      //     CURLOPT_RETURNTRANSFER => true,
      //     CURLOPT_ENCODING => '',
      //     CURLOPT_MAXREDIRS => 10,
      //     CURLOPT_TIMEOUT => 0,
      //     CURLOPT_FOLLOWLOCATION => true,
      //     CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      //     CURLOPT_CUSTOMREQUEST => 'POST',
      //     CURLOPT_POSTFIELDS => array('amount' => $amount,'currency' => 'INR'),
      //     CURLOPT_HTTPHEADER => array(
      //       'Authorization: Basic cnpwX2xpdmVfSFEyRUlucGNwMEgxYks6QlJrdE9IU2ZuTnhsWFlnbm9zNnVuWkVY'
      //   ),
      // ));

      //   $response = curl_exec($curl);

      //   curl_close($curl);
      //   return $response;

        // 'Authorization: Basic cnpwX2xpdmVfVXRwOXJrNmNCcW9MbUw6SkU2d0tHdTZZQWpTT3FzeG1NWnNDdmt6',

        $curl = curl_init();

        curl_setopt_array($curl, array(
          CURLOPT_URL => 'https://api.razorpay.com/v1/payments/'.$payment_id.'/capture',
          CURLOPT_RETURNTRANSFER => true,
          CURLOPT_ENCODING => '',
          CURLOPT_MAXREDIRS => 10,
          CURLOPT_TIMEOUT => 0,
          CURLOPT_FOLLOWLOCATION => true,
          CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
          CURLOPT_CUSTOMREQUEST => 'POST',
          CURLOPT_POSTFIELDS =>array('amount' => $amount,'currency' => 'INR'),
          CURLOPT_HTTPHEADER => array(
           'Authorization: Basic cnpwX2xpdmVfaFZCajFSWDF1NThySVo6TnNBTkF6Zm1acm9vYTFHalZNZFRtSHlj',
       ),
      ));

        $response = curl_exec($curl);
        curl_close($curl);
        return $response;





    }



    public function check_payment(Request $request){
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'amount' => 'required',
            'transactionID' => 'required',
            'topicId' => 'required',
        ]);

        $user = null;
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

        $success = false;

        $error = '';

        $amount = $request->amount;
        $txn_no = $request->transactionID;
        $response = array();

        $ta = $amount * 100;

        $ch = $this->get_curl_handle($txn_no, $ta);


        $response_array = json_decode($ch, true);

        if($response_array['status'] == 'captured' && $response_array['amount'] == $ta ){
         $success = true;

     }else{
         $success = false;
         return response()->json([
            'result' => 'failure',
            'message' => 'Success',
            'error' =>isset($response_array['error']) ? $response_array['error'] :'',

        ],401);

     }

     if($success == true){
        /////Functionality

        $user_id=$user->id;
        $topic_id=$request->topicId;
        $topic = Topic::where('id',$topic_id)->first();
        $duration = $topic->duration;
        $course_id = $topic->course_id;
        $subject_id = $topic->subject_id;
        $subscription_amount = $topic->subscription_amount;
        $description = $topic->description;
        $start_date = date('Y-m-d');
        $end_date = date('Y-m-d', strtotime("+".$duration." days"));
        $package_id = $request->topicId;
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


        return response()->json([
            'result' => 'success',
            'message' => 'Success',
           // 'error' => $error,

        ],200);
    }else{

     return response()->json([
        'result' => 'failure',
        'message' => 'Something Went Wrong',
            //'error' => $error,
    ],200);
 }


}



public function donations(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'amount' => 'required',
        'transactionID' => 'required',
    ]);

    $user = null;
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

    $success = false;

    $error = '';

    $amount = $request->amount;
    $txn_no = $request->transactionID;
    $response = array();

    $ta = $amount * 100;

    $ch = $this->get_curl_handle($txn_no, $ta);

    $response_array = json_decode($ch, true);

    if($response_array['status'] == 'captured' && $response_array['amount'] == $ta ){
     $success = true;

 }else{
     $success = false;
     return response()->json([
        'result' => 'failure',
        'message' => 'Success',
        'error' =>isset($response_array['error']) ? $response_array['error'] :'',

    ],401);

 }

  // $success = true;


 if($success == true){
    $donations = DB::table('donations')->insert([
        "user_id"=>$user->id,
        "txn_id"=>$txn_no,
        "txn_id"=>$amount,
    ]);

    





    return response()->json([
        'result' => 'success',
        'message' => 'Success',

    ],200);
}else{

 return response()->json([
    'result' => 'failure',
    'message' => 'Something Went Wrong',
],200);
}


}

public function send_email_attachment(Request $request){
    $data["email"] = "satyatekniko@gmail.com";
    $data["title"] = "From IndianSkills Academy";
    $data["body"] = "This is Test";

    $files = [
        public_path('donations/satyaepf.pdf'),
    ];

    Mail::send('donation', $data, function($message)use($data, $files) {
        $message->to($data["email"], $data["email"])
        ->subject($data["title"]);

        foreach ($files as $file){
            $message->attach($file);
        }

    });


}



public function get_prime(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'amount' => 'required',
        'transactionID' => 'required',
    ]);

    $user = null;
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

    $success = false;

    $error = '';

    $amount = $request->amount;
    $txn_no = $request->transactionID;
    $response = array();

    $ta = $amount * 100;

    $ch = $this->get_curl_handle($txn_no, $ta);

    $response_array = json_decode($ch, true);

    if($response_array['status'] == 'captured' && $response_array['amount'] == $ta ){
     $success = true;

 }else{
     $success = false;
     return response()->json([
        'result' => 'failure',
        'message' => 'Success',
        'error' =>isset($response_array['error']) ? $response_array['error'] :'',

    ],401);

 }

 $success = true;
 if($success == true){
    $insert_subscription = DB::table('prime')->insert([
        "user_id"=>$user->id,
        "txn_id"=>$txn_no,
    ]);


    return response()->json([
        'result' => 'success',
        'message' => 'Success',

    ],200);
}else{

 return response()->json([
    'result' => 'failure',
    'message' => 'Something Went Wrong',
],200);
}


}

















public function razorpay_create_payment(Request $request){
    $api_key = env('RAZORPAY_APIKEY_TEST');
    $api_secret = env('RAZORPAY_SECRET_TEST');
    $api = new Api($api_key, $api_secret);

    $orderData = [
        'receipt'         => 'rcptid_11',
            'amount'          => 39900, // 39900 rupees in paise
            'currency'        => 'INR'
        ];

        $razorpayOrder = $api->order->create($orderData);

        return response()->json([
            'result' => 'success',
            'message' => '',
            'razorpay' => $razorpayOrder,

        ],200);

    }

    public function add_wallet(Request $request){
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'amount' => 'required',
            'transactionID' => 'required',
           // 'topicId' => 'required',
        ]);

        $user = null;
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

        $success = false;

        $error = '';

        $amount = $request->amount;
        $txn_no = $request->transactionID;
        $response = array();
        $ta = $amount * 100;
        $ch = $this->get_curl_handle($txn_no, $ta);

        $response_array = json_decode($ch, true);

        if(!empty($response_array['error'])){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'error' =>$response_array['error'],

            ],200);
        }

        if($response_array['status'] == 'captured' && $response_array['amount'] == $ta ){
         $success = true;

     }else{
         $success = false;
         return response()->json([
            'result' => 'failure',
            'message' => 'Success',
            'error' =>isset($response_array['error']) ? $response_array['error'] :'',

        ],401);

     }
     $dbArr = [];
     if($success == true){
        /////Functionality

        $dbArr['user_id']=$user->id;
        $dbArr['purpose'] = 'Wallet';
        $dbArr['txn_no'] = $txn_no;
        $dbArr['method'] = 'online';
        $dbArr['gateway'] = 'razorpay';
        $dbArr['paid_by'] = $user->name;
        $dbArr['amount'] =  $amount;


        $success = DB::table('transaction_histories')->insert($dbArr);
        if($success){

            $user = User::where('id',$user->id)->first();

            $new_wallet = $user->wallet + $amount;
            User::where('id',$user->id)->update(['wallet'=>$new_wallet]);
        }

        
        return response()->json([
            'result' => 'success',
            'message' => 'Success',
            

        ],200);
    }else{

     return response()->json([
        'result' => 'failure',
        'message' => 'Something Went Wrong',

    ],200);
 }


}



public function initiate_txn(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => 'required',
    'currency' => 'required',
    'amount' => 'required',
]);

   $user = null;
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



$type = $request->type;
$topic_id = $request->topic_id;
$data = json_encode($request->toArray());
$currency = $request->currency;
$amount = $request->amount;

$gateway = isset($request->gateway) ? $request->gateway :'razor';
$tarray = array(
    'type' => $type,
    'topic_id' => $topic_id,
    'data' => $data,
    'currency' => $currency,
    'amount' => $amount,
    'user_id' => $user->id,
    'gateway' => $gateway,
    'status'=>'initiated',
    'created_at'=>date("Y-m-d H:i:s"),
    'updated_at'=>date("Y-m-d H:i:s")
);
$insert_id = DB::table('initiate_txn')->insertGetId($tarray);

if($gateway == 'razor')
{
    $order_id = $this->create_order($insert_id,$amount,$currency);

    DB::table('initiate_txn')->where('id',$insert_id)->update($tarray);

    if(!empty($order_id))
    {
        $insert_id = $order_id;
    }
    $response = array('result'=>'success','txn_id'=>$insert_id);
} else {
    $response = array('result'=>'success','txn_id'=>$insert_id);
}

return response()->json($response,200);
}





private function create_order($id,$amount,$currency)
{

    $d = array(
        'amount' => $amount * 100,
        'currency' => $currency,
        'receipt' => (string)$id,
    );
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.razorpay.com/v1/orders',
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'POST',
      CURLOPT_POSTFIELDS => json_encode($d),
      CURLOPT_HTTPHEADER => array(
         'Authorization: Basic cnpwX3Rlc3RfQVUwUk5ka2pBM2VZcE46czk0d2V4dTVKT3F2dDF3aHBpWWw3Mndn',
         'Content-Type: application/json'
     ),
  ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!empty($response))
    {
        $res = json_decode($response,true);
        if(!empty($res))
        {
            return $res['id'];
        } else {
            return $id;
        }
    }

}

private function get_orderID($order_id)
{
    $curl = curl_init();

    curl_setopt_array($curl, array(
      CURLOPT_URL => 'https://api.razorpay.com/v1/orders/'.$order_id,
      CURLOPT_RETURNTRANSFER => true,
      CURLOPT_ENCODING => '',
      CURLOPT_MAXREDIRS => 10,
      CURLOPT_TIMEOUT => 0,
      CURLOPT_FOLLOWLOCATION => true,
      CURLOPT_HTTP_VERSION => CURL_HTTP_VERSION_1_1,
      CURLOPT_CUSTOMREQUEST => 'GET',
      CURLOPT_HTTPHEADER => array(
         'Authorization: Basic cnpwX3Rlc3RfQVUwUk5ka2pBM2VZcE46czk0d2V4dTVKT3F2dDF3aHBpWWw3Mndn',
     ),
  ));

    $response = curl_exec($curl);

    curl_close($curl);
    if(!empty($response))
    {
        $res = json_decode($response,true);
        if(!empty($res))
        {
            return $res['receipt'];
        } else {
            return $order_id;
        }
    }
    return $order_id;
}


public function subscribed_program_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);

    $user = null;
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

    $subsciption_list = SubscriptionHistory::where('user_id',$user_id)->get();
    if($empty($subsciption_list)){
        foreach($subsciption_list as $sub){

        }
    }


    return response()->json([
        'result' => 'success',
        'message' => '',
        'razorpay' => $subsciption_list,

    ],200);




}




public function razor_webhook(Request $request)
{

    // $payload = $data['payload'];
    // $entity = $data['payload']['payment']['entity'];
    // $order_id = $entity['description'];
    // $status = $entity['status'];

    // print_r($request->toArray());
    // exit();

    $payload = isset($request->payload) ? $request->payload :'';
    $payment = isset($payload['payment']) ? $payload['payment'] :'';
    $entity = isset($payment['entity']) ? $payment['entity'] :'';
    $order_id = isset($entity['description']) ? $entity['description'] :'';
    $status = isset($entity['status']) ? $entity['status'] : '';




    $id = $this->get_orderID($order_id);
    $txn_no = $order_id;
    $int_txn_id = $id;

    $int_txn_detail = DB::table('initiate_txn')->where('id',$int_txn_id)->where('status','!=','completed')->first();
    if(!empty($int_txn_id) && $int_txn_id != 0 ){
        if($status == 'authorized')
        {
            if(!empty($int_txn_detail)){
                $type = $int_txn_detail->type;
                $insert_status = false;
                if($type=='subscription'){
                    DB::table('initiate_txn')->where('id',$int_txn_id)->update(array('status'=>'completed'));
                }
                
            }
        } else {
            DB::table('initiate_txn')->where('id',$int_txn_id)->update(array('status'=>'failed'));

        }
    } else {
      $response = array('result'=>'failure','message'=>'Your transaction failed');

  }
  $response = array('result'=>'success','message'=>'Your transaction added');
  return json_encode($response);
}





































}
