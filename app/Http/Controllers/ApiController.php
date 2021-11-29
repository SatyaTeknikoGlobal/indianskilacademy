<?php



namespace App\Http\Controllers;



use App\Board;

use App\City;

use App\ContactUs;
use App\ContentHistory;

use App\Exams;
use App\Resume;
use App\State;

use App\UserNote;
use App\UserOtp;

use Illuminate\Support\Facades\DB;

use App\AppVersion;

use App\Content;
use App\ExamQuestion;
use App\BookCategory;
use App\PrimeContent;
use App\FeedbackCourse;
use App\FeedbackContent;
use App\PrimeCourse;
use App\Book;
use App\Faculties;

use App\Course;
use App\Result;

use App\Storage;

use App\Subject;
use App\Testimonial;

use App\Chapter;
use App\Bookmark;
use Carbon\Carbon;
use App\SubscriptionHistory;

use App\Topic;
use App\News;

use App\Corner;

use App\UserLogin;
use App\Boards;

use Illuminate\Support\Facades\Session;
use JWTAuth;

use App\Banner;

use App\User;

use App\LiveClass;

use App\Faq;

use App\UserChats;

use App\Enquiry;

use App\JoinLiveClasses;

use Illuminate\Http\Request;

use Tymon\JWTAuth\Exceptions\JWTException;

use Validator;

use Illuminate\support\str;

use Aws\S3\S3Client;

use App\Jobs\MoveFiles;
use Hash;

use Mail;


class ApiController extends Controller

{

    /**

     * @var bool

     */

    public $loginAfterSignUp = true;

    public function __construct()
    {
        $this->url = url('/');
    }



    private function getTopicImageUrl($image){
        return "https://www.indianskillacademy.com/admin/public/images/topic/".$image;
    }

    private function getDocUrl($doc){

        return "https://www.indianskillacademy.com/admin/".$doc;

    }



    private function getVideoUrl($video){

        return "https://www.indianskillacademy.com/admin/public/content/video/".$video;

    }

    private function getFolderImage($video,$folder){

        return "https://www.indianskillacademy.com/admin/public/images/$folder/".$video;

    }


    private function storageUrl($video)

    {

        return "https://www.indianskillacademy.com/admin/public/content/video/".$video.".mp4";

    }

    private function getExamImageUrl($image){

        return "http://isa.appmantra.live/admin/public/images/exam/".$image;
    }

    private function getLiveClasses($image){

        return "https://www.indianskillacademy.com/admin/public/images/liveclasses/".$image;

    }

    private function getCourseImage($image){

        return "https://www.indianskillacademy.com/admin/public/images/course/".$image;

    }

    private function getThumbnailUrl($image){

        // return "http://isa.appmantra.live/admin/public/images/video/".$image;

        //https://www.indianskillacademy.com/admin/public/content/video/images/thumbnail1629275177.png
        return "https://www.indianskillacademy.com/admin/public/content/video/images/".$image;

    }



    private function getBanners($image){

        return "https://www.indianskillacademy.com/admin/public/images/liveclasses/".$image;

    }



    private function getImageUrl($image){

        return asset('public/uploads/images/'.$image);

    }



    private function getGalleryimage($image)

    {

    	 //return "https://agri.org/storage/gallery/".$image;

      return "https://www.indianskillacademy.com/admin/public/images/gallery/".$image;

  }

  public function run_crone(){



  }





  public function change_password(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'password' => 'required',
        'confirm_password' => 'required_with:password|same:password',
    ]);

    $user = null;

    if ($validator->fails()) {

        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();
    $exam_list = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }

    User::where('id',$user->id)->update(['password'=>bcrypt($request->password), 'is_change'=>1]);


    return response()->json([
        'result' => 'success',
        'message' => ' Successfully',

    ],200);



}


public static function sendEmail($viewPath, $viewData, $to, $from, $replyTo, $subject, $params=array()){

    try{

        Mail::send(
            $viewPath,
            $viewData,
            function($message) use ($to, $from, $replyTo, $subject, $params) {
                $attachment = (isset($params['attachment']))?$params['attachment']:'';

                if(!empty($replyTo)){
                    $message->replyTo($replyTo);
                }

                if(!empty($from)){
                    $message->from($from);
                }

                if(!empty($attachment)){
                    $message->attach($attachment);
                }

                $message->to($to);
                $message->subject($subject);

            }
        );
    }
    catch(\Exception $e){
            // Never reached
    }

    if( count(Mail::failures()) > 0 ) {
        return false;
    }       
    else {
        return true;
    }

}



  // private function send_email($gmail,$otp){
  //     $data = array('name'=>"Indian Skill Academy",'otp'=>$otp);
  //     Mail::send('mail', $data, function($message) {
  //      $message->to($gmail)->subject('Forgot Password Email - ISA');
  //      $message->from('techhub921@gmail.com','Indian Skill Academy');
  //  });
  //     // echo "HTML Email Sent. Check your inbox.";
  //     return true;
  // }



public function forget_password(Request $request){
 $validator =  Validator::make($request->all(), [
    'email' => 'required|email',
]);

 $user = null;

 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$exist = User::where('email',$request->email)->first();
if(!empty($exist)){
    $otp = rand(1111,9999);

    UserOtp::updateOrcreate([
        'email'=>$request->email],
        ['otp'=>$otp,
    ]);

    $to_email = $request->email;
    $from_email = 'techhub921@gmail.com';
    $subject = 'Forgot Password Email - ISA';
    $email_data = [];
    $email_data['otp'] = $otp;
    $success = $this->sendEmail('mail', $email_data, $to=$to_email, $from_email, $replyTo = $from_email, $subject);



    //$this->send_email($request->email,$otp);
    if($success){
        return response()->json([

            'result' => 'success',
            'message' => ' Successfully',
        ],200); 
    }else{
     return response()->json([

        'result' => 'failure',
        'message' => ' Something Went Wrong',
    ],200);
 }




}else{
    return response()->json([

        'result' => 'failure',
        'message' => 'User Not Exist',
    ],200);
}





}



public function verify_otp_fp(Request $request){
  $validator =  Validator::make($request->all(), [
    'email' => 'required|email',
    'otp' => 'required',
    // 'password'=>'required',
    // 'confirm_password'=>'required|same:password',
]);

  $user = null;

  if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$exist = UserOtp::where('email',$request->email)->first();
if(!empty($exist)){
    if($request->otp == $exist->otp){

        UserOtp::where('email',$request->email)->update(['otp'=>null]);

        $updates = User::where('email',$request->email)->update(['password'=>bcrypt($request->password)]);

        return response()->json([

            'result' => 'success',
            'message' => 'Verified Successfully',
        ],200);
    }else{
     return response()->json([

        'result' => 'failure',
        'message' => 'Invalid OTP',
    ],200);
 }

}else{
 return response()->json([

    'result' => 'failure',
    'message' => 'Invalid OTP',
],200);
}

}

// public function change_password(Request $request){
//    $validator =  Validator::make($request->all(), [
//     'email' => 'required|email',
//     'password'=>'required',
//     'confirm_password'=>'required|same:password',
// ]);

//    $user = null;

//    if ($validator->fails()) {

//     return response()->json([
//         'result' => 'failure',
//         'message' => json_encode($validator->errors()),

//     ],400);

// }

// $updates = User::where('email',$request->email)->update(['password'=>bcrypt($request->password)]);
// return response()->json([
//     'result' => 'success',
//     'message' => 'Password Changed Successfully',
// ],200);



// }





    /**

     * @param Request $request

     * @return \Illuminate\Http\JsonResponse

     */

    public function generate_random_password($length = 10) {

        $numbers = range('0','9');

        $alphabets = range('A','Z');



        //$additional_characters = array('_','.');

        $final_array = array_merge($alphabets,$numbers);

        //$final_array = array_merge($numbers);

        $password = '';

        while($length--) {

            $key = array_rand($final_array);

            $password .= $final_array[$key];

        }



        return $password;

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



    public function send_otp(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'phone' => 'required',

            'otp' => 'required|min:4',

        ]);

        $status = 'new';



        if ($validator->fails()) {



            return response()->json([

                'result' => 'failure',

                'otp'=> '',

                'message' => json_encode($validator->errors()),

                'status' =>$status

            ],400);

        }

        $message = $request['otp']." is your authentication Code to register.";

        $mobile = $request['phone'];

        $this->send_message($mobile,$message);

        $check  = User::where(['phone'=>$mobile])->first();

        if (!empty($check))

        {

            $status = 'old';

        }

        return response()->json([

            'result' => 'success',

            'otp'=> $request['otp'],

            'message' => 'SMS Sent SuccessFully',

            'status' =>$status

        ],200);

    }



    public function send_otp2(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'phone' => 'required',

        ]);

        $status = 'new';



        if ($validator->fails()) {



            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'status' =>$status

            ],400);

        }

        $otp = rand(1000,9999);

        $message = $otp." is your authentication Code to register.";

        $mobile = $request['phone'];

        $this->send_message($mobile,$message);

        UserOtp::create([

            "mobile"=>$mobile,

            "otp"=>$otp

        ]);

        $check  = User::where(['phone'=>$mobile])->first();

        if (!empty($check))

        {

            $status = 'old';

        }

        return response()->json([

            'result' => 'success',

            'message' => 'SMS Sent SuccessFully',

            'status' =>$status

        ],200);

    }


    public function social_login(Request $request)

    {


        // $tsuccess = DB::table('new')->insert(array('name'=>json_encode($request->toArray())));
        // die();


        $validator =  Validator::make($request->all(), [

            'email' => 'required',
            'name' => 'required',
            'status' => 'required',
            'type' => 'required',

            'deviceID' => 'required',

            'deviceToken' => 'required',

            'deviceType' => 'required',

        ]);

        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'token' => null,

                'message' => json_encode($validator->errors()),
                'status' => 'new',


                'user'=>$user

            ],400);

        }

        if($request->status == 0){
            return response()->json([

                'result' => 'failure',

                'token' => null,

                'message' => 'Email Not Verified',
                'status' => 'new',


                'user'=>$user

            ],400);

        }



        $credentials = $request->only('email');
        $status = 'new';
        $user = User::where($credentials)->first();
        if(!empty($user)){
            $user->city_id = isset($user->city_id) ? $user->city_id : 1;
        }



        try {

            if (!empty($user)) {
                $status = 'old';

                if (!$token = JWTAuth::fromUser($user)) {

                    return response()->json([

                        'result' => 'failure',

                        'status' => $status,

                        'token' => null,

                        'message' => 'invalid_credentials',

                        'user' => null], 400);

                }

            } else {

                // return response()->json([

                //     'result' => 'failure',

                //     'token' => null,
                //     'status' => $status,

                //     'message' => 'invalid_credentials',

                //     'user' => null], 400);


                $dbArray = [];

                $dbArray['name'] = $request->name;
                $dbArray['email'] = $request->email;
                $dbArray['is_change'] = "1";
                
                User::create($dbArray);

                $user = User::where('email',$request->email)->first();

                if(!empty($user)){
                    $user->city_id = isset($user->city_id) ? $user->city_id : 1;
                }


                $token = JWTAuth::fromUser($user);

                return response()->json([

                    'result' => 'success',

                    'token' => $token,

                    'message' => 'Successful Login',
                    'status' => $status,


                    'user' => $user

                ],200);


            }



        } catch (JWTException $e) {

            return response()->json([

                'result' => 'failure',

                'token' => null,
                'status' => $status,


                'message' => 'could_not_create_token',

                'user' => null], 500);

        }



        $deviceID = $request->input("deviceID");

        $deviceToken = $request->input("deviceToken");

        $deviceType = $request->input("deviceType");

        $device_info = UserLogin::where(['user_id'=>$user->id])->first();

        if (!empty($device_info)){
             // && $user->id != 6 

            // if ($device_info->deviceID != $deviceID){

            //     return response()->json([

            //         'result' => 'failure',

            //         'token' => null,

            //         'message' => "You Can't Login to Multiple Device",

            //         'user' => null

            //     ],200);

            // }

            $device_info->deviceToken = $deviceToken;

            $device_info->deviceType = $deviceType;
            $device_info->deviceID = $deviceID;

            $device_info->save();

            

            return response()->json([

                'result' => 'success',

                'token' => $token,

                'message' => 'Successful Login',
                'status' => $status,


                'user' => $user

            ],200);

        }

        UserLogin::create([

            "user_id"=>$user->id,

            "ip_address"=>$request->ip(),

            "deviceID"=>$deviceID,

            "deviceToken"=>$deviceToken,

            "deviceType"=>$deviceType,

        ]);


        return response()->json([

            'result' => 'success',

            'token' => $token,

            'message' => 'Successful Login',
            'status' => $status,
            

            'user' => $user

        ],200);



    }





































    public function verifyOtp(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'phone' => 'required',

            'otp'=>'required',

        ]);

        $status = 'N';



        if ($validator->fails()) {



            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'status' =>$status

            ],400);

        }

        $otp = $request->input('otp');

        $mobile = $request->input('phone');

        $check  = UserOtp::where(['email'=>$mobile,'otp'=>$otp])->first();

        if (!empty($check))

        {

            $status = 'Y';

            return response()->json([

                'result' => 'success',

                'message' => '',

                'status' =>$status

            ],200);

        }else{
         return response()->json([

            'result' => 'success',

            'message' => 'Incorrect OTP',

            'status' =>$status

        ],200);
     }



 }



 public function login2(Request $request)

 {

    $validator =  Validator::make($request->all(), [

        'phone' => 'required',

        'otp' => 'required',

        'deviceID' => 'required',

        'deviceToken' => 'required',

        'deviceType' => 'required',

    ]);

    $user = null;

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => json_encode($validator->errors()),

            'user'=>$user

        ],400);

    }

    $mobile = $request->input('phone');

    $otp  = $request->input('otp');

    $checkOtp = UserOtp::where(['mobile'=>$mobile,'otp'=>$otp])->first();

    if (empty($checkOtp)){

        return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => 'Incorrect Otp',

            'user' => null], 200);

    }

    $credentials = $request->only('phone');

    $user = User::where($credentials)->first();

    try {

        if (!empty($user)) {

            if (!$token = JWTAuth::fromUser($user)) {

                return response()->json([

                    'result' => 'failure',

                    'token' => null,

                    'message' => 'invalid_credentials',

                    'user' => null], 400);

            }

        } else {

            return response()->json([

                'result' => 'failure',

                'token' => null,

                'message' => 'invalid_credentials',

                'user' => null], 400);

        }



    } catch (JWTException $e) {

        return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => 'could_not_create_token',

            'user' => null], 500);

    }



    $deviceID = $request->input("deviceID");

    $deviceToken = $request->input("deviceToken");

    $deviceType = $request->input("deviceType");

    $device_info = UserLogin::where(['user_id'=>$user->id])->first();

    if (!empty($device_info) && $user->id != 6 ){

            // if ($device_info->deviceID != $deviceID){

            //     return response()->json([

            //         'result' => 'failure',

            //         'token' => null,

            //         'message' => "You Can't Login to Multiple Device",

            //         'user' => null

            //     ],200);

            // }

        $device_info->deviceToken = $deviceToken;

        $device_info->deviceType = $deviceType;

        $device_info->save();

        $checkOtp->delete();

        return response()->json([

            'result' => 'success',

            'token' => $token,

            'message' => 'Successful Login',

            'user' => $user

        ],200);

    }

    UserLogin::create([

        "user_id"=>$user->id,

        "ip_address"=>$request->ip(),

        "deviceID"=>$deviceID,

        "deviceToken"=>$deviceToken,

        "deviceType"=>$deviceType,

    ]);

    $checkOtp->delete();

    return response()->json([

        'result' => 'success',

        'token' => $token,

        'message' => 'Successful Login',

        'user' => $user

    ],200);



}

public function login_with_pw(Request $request){
  $validator =  Validator::make($request->all(), [

    'email' => 'required',

    'password' => 'required',

    'deviceID' => '',

    'deviceToken' => '',

    'deviceType' => '',

]);

  $user = null;

  if ($validator->fails()) {

    return response()->json([

        'result' => 'failure',

        'token' => null,

        'message' => json_encode($validator->errors()),

        'user'=>$user

    ],400);

}

$email = $request->input('email');

$password  = $request->input('password');
$hash_chack = '';

$checkuser = User::where(['email'=>$email])->first();

if(!empty($checkuser)){
    if($checkuser->is_change == 1){
        if(!empty($checkuser)){
            $existing_password = $checkuser->password;
            $hash_chack = Hash::check($request->password, $existing_password);

        }else{
           return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => 'Account Doesnt Exist',

            'user' => null], 200);
       }


       if (empty($hash_chack)){

        return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => 'Incorrect Password',

            'user' => null], 200);

    }

}
}else{
   return response()->json([

    'result' => 'failure',

    'token' => null,

    'message' => 'Account Does not Exist',

    'user' => null], 200);
}

$credentials = $request->only('email');

$user = User::where($credentials)->first();
if(!empty($user)){
    $user->city_id = isset($user->city_id) ? $user->city_id : 1;
}
try {

    if (!empty($user)) {

        if (!$token = JWTAuth::fromUser($user)) {

            return response()->json([

                'result' => 'failure',

                'token' => null,

                'message' => 'invalid_credentials',

                'user' => null], 400);

        }

    } else {

        return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => 'invalid_credentials',

            'user' => null], 400);

    }



} catch (JWTException $e) {

    return response()->json([

        'result' => 'failure',

        'token' => null,

        'message' => 'could_not_create_token',

        'user' => null], 500);

}



$deviceID = $request->input("deviceID");

$deviceToken = $request->input("deviceToken");

$deviceType = $request->input("deviceType");

$device_info = UserLogin::where(['user_id'=>$user->id])->first();

if (!empty($device_info) && $user->id != 6 ){

    // if ($device_info->deviceID != $deviceID){

    //     return response()->json([

    //         'result' => 'failure',

    //         'token' => null,

    //         'message' => "You Can't Login to Multiple Device",

    //         'user' => null

    //     ],200);

    // }

    $device_info->deviceToken = $deviceToken;

    $device_info->deviceType = $deviceType;

    $device_info->save();

            //$checkOtp->delete();

    return response()->json([

        'result' => 'success',

        'token' => $token,

        'message' => 'Successful Login',

        'user' => $user

    ],200);

}

UserLogin::create([

    "user_id"=>$user->id,

    "ip_address"=>$request->ip(),

    "deviceID"=>$deviceID,

    "deviceToken"=>$deviceToken,

    "deviceType"=>$deviceType,

]);



        // $checkOtp->delete();

return response()->json([

    'result' => 'success',

    'token' => $token,

    'message' => 'Successful Login',

    'user' => $user

],200);




}


public function contactUs(Request $request)
{

    $validator =  Validator::make($request->all(), [
        'name' => 'required',
        'email' => 'required',
        'subject' => 'required',
        'message' => 'required',
    ]);
    if ($validator->fails()) {

        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
        ],400);
    }
    ContactUs::create([
        "name"=>$request->input("name"),
        "email"=>$request->input("email"),
        "subject"=>$request->input("subject"),
        "message"=>$request->input("message"),
        "status"=>"NEW",
    ]);
    return response()->json([

        'result' => 'success',

        'message' => 'Query Submitted Successfully',

    ],200);
}



public function login(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'phone' => 'required',

        'deviceID' => 'required',

        'deviceToken' => 'required',

        'deviceType' => 'required',

    ]);

    $user = null;

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => json_encode($validator->errors()),

            'user'=>$user

        ],400);

    }
    $credentials = $request->only('phone');
    $user = User::where($credentials)->first();
    try {

        if (!empty($user)) {

            if (!$token = JWTAuth::fromUser($user)) {

                return response()->json([

                    'result' => 'failure',

                    'token' => null,

                    'message' => 'invalid_credentials',

                    'user' => null], 400);

            }

        } else {

            return response()->json([

                'result' => 'failure',

                'token' => null,

                'message' => 'invalid_credentials',

                'user' => null], 400);

        }



    } catch (JWTException $e) {

        return response()->json([

            'result' => 'failure',

            'token' => null,

            'message' => 'could_not_create_token',

            'user' => null], 500);

    }



    $deviceID = $request->input("deviceID");

    $deviceToken = $request->input("deviceToken");

    $deviceType = $request->input("deviceType");

    $device_info = UserLogin::where(['user_id'=>$user->id])->first();

    if (!empty($device_info) && $user->id != 1 && $user->id != 2){

        if ($device_info->deviceID != $deviceID){

            return response()->json([

                'result' => 'failure',

                'token' => null,

                'message' => "You Can't Login to Multiple Device",

                'user' => null

            ],200);

        }

        $device_info->deviceToken = $deviceToken;

        $device_info->deviceType = $deviceType;

        $device_info->save();

        return response()->json([

            'result' => 'success',

            'token' => $token,

            'message' => 'Successful Login',

            'user' => $user

        ],200);

    }

    UserLogin::create([

        "user_id"=>$user->id,

        "ip_address"=>$request->ip(),

        "deviceID"=>$deviceID,

        "deviceToken"=>$deviceToken,

        "deviceType"=>$deviceType,

    ]);

    return response()->json([

        'result' => 'success',

        'token' => $token,

        'message' => 'Successful Login',

        'user' => $user

    ],200);



}



    /**

     * @param Request $request

     * @return \Illuminate\Http\JsonResponse

     * @throws \Illuminate\Validation\ValidationException

     */

    public function logout(Request $request)

    {

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'user_id' => 'required|max:255',

            'deviceID' => 'max:255',

        ]);



        if ($validator->fails()) {



            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors())

            ],400);

        }



        try {

            JWTAuth::invalidate($request->token);



            $user_login = UserLogin::where(['user_id' => $request->input("user_id"),'deviceID' => $request->input("deviceID"), ])->first();

            $user_login->deviceToken = '';

            $user_login->save();



            return response()->json([

                'result' => 'success',

                'message' => 'User logged out successfully'

            ],200);

        } catch (JWTException $exception) {

            return response()->json([

                'result' => 'failure',

                'message' => 'Sorry, the user cannot be logged out'

            ], 500);

        }

    }



    public function profile(Request $request){

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

        ]);

        $user = null;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'user'=>$user

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        $user->slug = strtoupper($user->slug);
        $user->image = asset('public/uploads/images/'.$user->image);

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'user'=>$user

            ],401);

        }



        return response()->json([

            'result' => 'success',

            'message' => '',

            'user'=>$user

        ],200);

    }



    /**

     * @param RegistrationFormRequest $request

     * @return \Illuminate\Http\JsonResponse

     */

    public function getState(){

        $list = State::get();

        return response()->json([

            'result'   =>  "success",

            "message" => '',

            'list'      =>  $list

        ], 200);

    }



    public function getCity(Request $request){

        $validator = Validator::make($request->all(), [

            'state_id' => 'required|max:255',

        ]);

        if ($validator->fails()) {



            return response()->json([

                'result' => 'success',

                'message' => "",

                'list'=>City::get()

            ],400);

        }

        $state_id = $request->input('state_id');

        return response()->json([

            'result' => 'success',

            'message' => "",

            'list'=>City::where(['stateID'=>$state_id])->get()

        ],200);

    }



    public function getCourses(){

        return response()->json([

            'result' => 'success',

            'message' => "",

            'list'=>Board::get()

        ],200);

    }



    public function register2(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|max:255',

            'password' => 'required',
            'email' => 'required|unique:users',

            'phone' => 'required|unique:users',

            'deviceID' => 'required',

            'deviceToken' => 'required',

            'deviceType' => 'required',

        ]);

        if ($validator->fails()) {



            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'token'=>null,

                'user'=>null

            ],400);

        }

        $mobile = $request->input('phone');

        $password  = $request->input('password');

        // if ($request->email != ''){

        //     $check_email = User::where(['email'=>$request->email])->first();

        //     if(!empty($check_email)){

        //         return response()->json([

        //             'result' => 'failure',

        //             'token' => null,

        //             'message' => 'Incorrect Otp',

        //             'user' => null], 200);

        //     }

        // }

        // $checkOtp = User::where(['mobile'=>$mobile,'otp'=>$otp])->first();

        // if (empty($checkOtp)){

        //     return response()->json([

        //         'result' => 'failure',

        //         'token' => null,

        //         'message' => 'Incorrect Otp',

        //         'user' => null], 200);

        // }

        $user = new User();

        $user->name = $request->name;

        $user->username = $request->name;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->password = bcrypt($request->password);

        $user->date_of_birth = $request->dob;

        $user->board_id = $request->courseId;

        $user->state_id = $request->stateId;

        $user->city_id = $request->cityId;
        $user->is_change = 1;

        $user->slug = strtoupper(substr($request->name,0,2).Str::random(6));

        $user->login_enabled = 1;

        $user->save();

        //$checkOtp->delete();

        if ($this->loginAfterSignUp) {

            return $this->login($request);

        }

        return response()->json([

            'success'   =>  true,

            'user'      =>  $user

        ], 200);

    }



    public function validateEmail(Request $request){

        $validator = Validator::make($request->all(), [

            'email' => 'required|unique:users',

        ]);

        if ($validator->fails()) {



            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

            ],200);

        }

        return response()->json([

            'result' => 'success',

            'message' => '',

        ],200);

    }



    public function validateEmail2(Request $request){

        $validator = Validator::make($request->all(), [

            'email' => 'required',

            'token' => 'required',

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



        $user_id = $user->id;

        $check = User::where(['email'=>$request->email])->where('id','!=',$user_id)->first();

        if (!empty($check)) {

            return response()->json([

                'result' => 'failure',

                'message' => 'this email has already been taken',

            ],200);

        }

        return response()->json([

            'result' => 'success',

            'message' => '',

        ],200);

    }



    public function register(Request $request)

    {

        $validator = Validator::make($request->all(), [

            'name' => 'required|max:255',

            'phone' => 'required|unique:users',

            'deviceID' => 'required',

            'deviceToken' => 'required',

            'deviceType' => 'required',

        ]);

        if ($validator->fails()) {



            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'token'=>null,

                'user'=>null

            ],400);

        }

        $user = new User();

        $user->name = $request->name;

        $user->username = $request->name;

        $user->email = $request->email;

        $user->phone = $request->phone;

        $user->password = bcrypt($request->phone);

        $user->slug = strtoupper(substr($request->name,0,2).Str::random(6));

        $user->login_enabled = 1;

        $user->save();

        if ($this->loginAfterSignUp) {

            return $this->login($request);

        }

        return response()->json([

            'success'   =>  true,

            'user'      =>  $user

        ], 200);

    }



    public function update_profile(Request $request){

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'name'=>'required',

            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',

        ]);

        $content = array();

        $user = null;

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

        $user_id = $user->id;

        $d= $request->dob;

        $s= strtotime($d);

        $data = array(

            'name'=>request('name'),

            'email'=>request('email'),

            'board_id'=>request('courseId'),

            'state_id'=>request('stateId'),

            'city_id'=>request('cityId'),

        );

        if($d != '' || $d != null){

            $data['date_of_birth'] = date('Y-m-d',$s);

        }

        if($request->hasFile('image')){

            $destinationPath = public_path("/uploads/images");

            $side = $request->file('image');

            $side_name = $user_id.'_user_profile'.time().'.'.$side->getClientOriginalExtension();

            $side->move($destinationPath, $side_name);

            $data['image'] = $side_name;

        }

        User::where(['id'=>$user_id])->update($data);

        $result = User::where(['id'=>$user_id])->first();

        if($result->image != null && $result->image != ''){

            $result->image = asset('public/uploads/images/'.$result->image);

        }



        return response()->json([

            'result' => 'success',

            'message' => 'Profile updated',

            'content' =>$result,

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
            return true;
        }
        return false;
    }

    public function home(Request $request)

    {

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'deviceID' => '',

            'deviceToken' => '',

            'deviceType' => '',
            'topic_id' => '',

        ]);


        $content = array();

        $subStatus = false;

        $current_timestamp = time();
        $quiz = '';
        $user = null;
        $news= '';
        $courses= '';
        $pdfs= '';
        $trendings= [];


        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'content' =>$content,

                'user'=>$user,

                'subStatus'=>$subStatus

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content,

                'user'=>$user,

                'subStatus'=>$subStatus

            ],401);

        }

        $user = User::find($user->id);

        if($user->image != null && $user->image != ''){

            $user->image = $this->getImageUrl($user->image);

        }

        $free_video = array();

        $banners = Banner::where(['status'=>'Y'])->latest()->get();

        foreach ($banners as  $value) {
            $subject_id = 0;

            if(!empty($value->link)){
                $subject = Subject::where('id',$value->link)->first();
                if(!empty($subject)){
                    $value->link_type = $subject->title ??'';
                    $subject_id = $value->link;
                    

                }

            }
            $value->id = $subject_id;

            $value->banner = url('admin/public/images/app_banner/'.$value->banner);

        }

        $board_id= $user->board_id;

        $course_name = Board::where(['id'=>$board_id])->first();

        // $free_video=  Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.boards_id'=>$board_id,'contents.is_paid'=>'N','contents.status'=>'Y','contents.type'=>'video'])->select(['contents.*'])->take(10)->latest()->with('topic')->get();


        $free_video=  Content::join('topics','contents.topic_id','=','topics.id')->where(['contents.is_paid'=>'N','contents.status'=>'Y','contents.type'=>'video'])->select(['contents.*'])->take(5)->latest()->with('topic')->get();




        foreach ($free_video as $fv) {

          // $fv->thumbnail = $this->getThumbnailUrl($fv->thumbnail);
          $fv->thumbnail = $fv->hls;

          $fv->url = $this->storageUrl($fv->hls);

          $fv->skip = 0;

          $fv->duration = 100;

      }



      $corner = Corner::latest()->take(5)->get();

      foreach ($corner as $row) {

       $row->image = $this->getGalleryimage($row->image);

   }

   $live = LiveClass::where(['status'=>'Y','course_id'=>$user->board_id])->with('faculties')->get();

   foreach ($live as  $row) {

      $row->image = $this->getBanners($row->image);

  }

  $quiz = Exams::where('type',3)->take(5)->latest()->get();
  if(!empty($request->topic_id)){
    $quiz = Exams::where('type',3)->where('topic_id',$request->topic_id)->take(5)->latest()->get();
}


if(!empty($quiz)){
    foreach($quiz as $q){
        $bookmark = DB::table('bookmark_master')->where('user_id',$user->id)->where('type','quiz')->where('quiz_id',$q->id)->first();
        if(!empty($bookmark)){
            $q->is_bookmark = '1';
        }else{
            $q->is_bookmark = '0';
        }
    }
}

$this->add_user_to_group($user->id);

$is_prime = 'N';

$exist = DB::table('prime')->where('user_id',$user->id)->first();
if(!empty($exist)){
    $is_prime = 'Y';

}



$news = News::where('status',1)->latest()->paginate(5);
if(!empty($news)){
    foreach($news as $new){

        // $recent->short_description =  mb_strlen(strip_tags($recent->short_description),'utf-8') > 50 ? mb_substr(strip_tags($recent->short_description),0,50,'utf-8').'...' : strip_tags($recent->short_description);



        if(!empty($new->image)){
            $new->is_bookmark = 0;
            $bookmark = Bookmark::where('user_id',$user->id)->where('news_id',$new->id)->first();
            if(!empty($bookmark)){
                $new->is_bookmark = 1;
            }
            if(!empty($new->image) && $new->type == 'image'){
                $new->image = url('admin/public/images/news/').'/'.$new->image;
            }
        } 
    }
}

$courses = Boards::select('id','board_name','board_name_hindi','image','status','type')->where('status','Y')->get();
if(!empty($courses)){
    foreach($courses as $cour){
        if(!empty($cour->image)){
            $cour->image = url('admin/public/images/course/').'/'.$cour->image;
        } 
    }
}


$subscribed_course = DB::table('subscription_histories')
->select('topic_id', DB::raw('count(*) as total'))
->groupBy('topic_id')
->orderBy('total','desc')
->limit(5)
->get();
if(!empty($subscribed_course)){
    foreach($subscribed_course as $use){
        $program_list = Topic::where('id',$use->topic_id)->first();
        if(!empty($program_list)){
            if(!empty($program_list->image)){
                $program_list->image = url('admin/public/images/topic/'.$program_list->image);
            }
            $subject = Subject::select('id','title')->where('id',$program_list->subject_id)->first();


            $board = Boards::select('id','board_name')->where('id',$program_list->course_id)->first();


            $program_list->boards = $board;
            $program_list->subject = $subject;

            array_push($trendings,$program_list);

        }


    }


}



$subscription = SubscriptionHistory::where('user_id',$user->id)->latest()->first();
//$pdfs = Content::where('type','notes')->take(10)->latest()->get();


$pdfs =  Content::join('topics','contents.topic_id','=','topics.id')->where(['contents.is_paid'=>'N','contents.status'=>'Y','contents.type'=>'notes'])->select(['contents.*'])->take(5)->latest()->get();;




if(!empty($pdfs)){
    foreach($pdfs as $pdf){
        $bookmark = DB::table('bookmark_master')->where('type','pdf')->where('pdf_id',$pdf->id)->first();
        if(!empty($bookmark)){
            $pdf->is_bookmark = 1;
        }else{
            $pdf->is_bookmark = 0;
        }

        if(!empty($pdf->hls)){
            $pdf->hls = url('admin/public/content/notes/').'/'.$pdf->hls;
            $pdf->expiredOn = isset($subscription->end_date) ? $subscription->end_date.' 23:59:59' :"";
        } 
    }
}






$content = [

    "banners"=>$banners,

    "free_video"=>$free_video,

    "corner"=>$corner,

    "liveclass"=>$live,

    "quiz"=>$quiz,

    "pdfs"=>$pdfs,

    "courses"=>$courses,
    "trendings"=>$trendings,

    "news"=>$news,


];

return response()->json([
//            'session'=>Session::count(),

    'result' => 'success',

    'message' => '',

    'content' =>$content,

    'user'=>$user,

    'course_name'=>"Upsc",
    'is_prime'=>$is_prime,
    'is_change'=>$user->is_change,

    'subStatus'=>$this->_checkCourseSubscription($board_id,$user->id),



],200);

}







public function course_list_v2(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);

    $user = null;
    $courses = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'courses' => $courses,

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([
            'result' => 'failure',
            'message' => '',
            'courses' => $courses,

        ],401);
    }

    $array = [];


    $courses = Boards::select('id','board_name')->where('status',1)->orderby('priority','asc')->get();
    if(!empty($courses)){
        foreach($courses as $course){
            $subjects = Subject::where('board_id',$course->id)->where('status',1)->orderby('priority','asc')->get();
            if(!empty($subjects) && count($subjects) > 0){
               foreach($subjects as $subject){
                if(!empty($subject->image)){
                    $subject->image = $this->url.'/admin/public/images/subject/'.$subject->image;
                }

                $faculties = Faculties::where('id',$subject->faculties_id)->first();
                $subject->is_subscription = 'N';

                $sub_history = SubscriptionHistory::where('subject_id',$subject->id)->where('user_id',$user->id)->first();
                if(!empty($sub_history)){
                    $subject->is_subscription = 'Y';

                }
                $description = "";
                if(!empty($subject->description)){
                    $description = mb_strlen(strip_tags($subject->description),'utf-8') > 50 ? mb_substr(strip_tags($subject->description),0,50,'utf-8').'...' : strip_tags($subject->description);
                }  

                $subject->description =$description;


                $subject->faculties_name = $faculties->name ??'';
            }
            $course->subjects = $subjects;
            array_push($array,$course);
            // $array[] = $course;

        }


    }
}

return response()->json([
    'result' => 'success',
    'message' => 'Course List',
    'courses'=>$array,

],200);

}

public function get_batch_from_course(Request $request){

 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'subject_id' => 'required',
]);
 $user = null;
 $batches = null;
 if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'batches' => $batches,

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();

if (empty($user)){

    return response()->json([
        'result' => 'failure',
        'message' => '',
        'batches' => $batches,

    ],401);
}

$batches = Topic::where('subject_id',$request->subject_id)->get();
 //print_r($batches);

if(!empty($batches)){
    foreach($batches as $batch){

        $batch->remaining = 10;
        $batch->is_enrolled = 'N';

        $sub_history = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$batch->id)->first();


        if(!empty($sub_history)){
            $batch->is_enrolled = 'Y';

        }


        //$batch->is_status = 3;


        $today = date('Y-m-d');
        /*anisha*/
       /* if($batch->start_date > $today){
            $batch->is_status = 2;
        }*/

        // if($today <= $batch->end_date && $today >= $batch->start_date){
        //     $batch->is_status = 3;
        // }

        
        /*anisha*/

       /* if($batch->end_date < $today){
            $batch->is_status = 1;
        }



        if($batch->start_date < $today && $batch->end_date > $today){
         $batch->is_status = 3;
     }*/

        //  if($batch->start_date < $today){
        //     $batch->is_status = 1;
        // }

     /*$batch->start_date = date('d-m-Y',strtotime($batch->start_date));
     $batch->end_date = date('d-m-Y',strtotime($batch->end_date));*/

 }
}


$subject =  Subject::select('faculties_id','id','image','title','about','batch_schedule','contents')->where('id',$request->subject_id)->first();

/*if(!empty($subject->image)){
    $subject->image = $this->url.'/admin/public/images/subject/'.$subject->image;
}*/

if(!empty($subject->about)){
    $subject->about = $this->url.'/admin/public/images/subject/pdf/'.$subject->about;

}
if(!empty($subject->batch_schedule)){
    $subject->batch_schedule = $this->url.'/admin/public/images/subject/pdf/'.$subject->batch_schedule;

}
if(!empty($subject->contents)){
    $subject->contents = $this->url.'/admin/public/images/subject/pdf/'.$subject->contents;

}



if(!empty($subject->faculties_id)){
    $faculties = Faculties::where('id',$subject->faculties_id)->first();
    if(!empty($faculties->image)){
        $faculties->image = $this->url.'/admin/public/images/faculties/'.$faculties->image;
    }
    $subject->is_subscription = 'N';
    $subject->faculties_name = $faculties->name ??'';
    $subject->image = $faculties->image ??'';

}



return response()->json([
    'result' => 'success',
    'message' => 'Batches US',
    'batches'=>$batches,
    'subject'=>$subject,

],200);

}


public function pdf_list_v2(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'subject_id' => 'required',
        'type' => 'required',
    ]);
    $user = null;
    $contents = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'contents' => $contents,

        ],400);
    }

    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([
            'result' => 'failure',
            'message' => '',
            'contents' => $contents,

        ],401);
    }



    $contents = Content::where('type',$request->type)->where('topic_id',$request->subject_id)->get();
    if(!empty($contents)){
        foreach($contents as $content){
            if($content->type == 'notes'){
                $content->hls = $this->url.'/admin/public/content/notes/'.$content->hls; 
            }else if($content->type == 'video'){

            }
        }
    }


    $topic = Topic::where('id',$request->subject_id)->first();


    $subject =  Subject::select('faculties_id','id','image','title','about','batch_schedule','contents')->where('id',$topic->subject_id)->first();

    if(!empty($subject->image)){
        $subject->image = $this->url.'/admin/public/images/subject/'.$subject->image;
    }
    if(!empty($subject->about)){
        $subject->about = $this->url.'/admin/public/images/subject/pdf/'.$subject->about;

    }
    if(!empty($subject->batch_schedule)){
        $subject->batch_schedule = $this->url.'/admin/public/images/subject/pdf/'.$subject->batch_schedule;

    }
    if(!empty($subject->contents)){
        $subject->contents = $this->url.'/admin/public/images/subject/pdf/'.$subject->contents;

    }


    $faculties = Faculties::where('id',$subject->faculties_id)->first();
    $subject->is_subscription = 'N';
    $subject->faculties_name = $faculties->name ??'';

    return response()->json([
        'result' => 'success',
        'message' => 'Contents',
        'contents'=>$contents,
        'subjects'=>$subject,

    ],200);



}




public function aboutUs(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);

    $user = null;
    $about_us = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'about_us' => $about_us,

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([
            'result' => 'failure',
            'message' => '',
            'about_us' => $about_us,

        ],401);
    }

    $user = User::find($user->id);




    $setting = DB::table('settings')->where('id',1)->first();
    if(!empty($setting)){
        $about_us = isset($setting->about_us) ? $setting->about_us :'';
    }


    return response()->json([
        'result' => 'success',
        'message' => 'About US',
        'about_us'=>$about_us,

    ],200);

}



public function DailyNewsAnalyisis(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);

    $user = null;
    $news = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'news' => $news,

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([
            'result' => 'failure',
            'message' => '',
            'news' => $news,

        ],401);
    }

    $user = User::find($user->id);


    $recent_news = News::where('date',date('Y-m-d'))->paginate(10);
    if(!empty($recent_news)){
        foreach($recent_news as $recent){
            if($recent->type == 'image'){
                $recent->image = url('admin/public/images/news/'.$recent->image);
            }

            $recent->date = date('d M Y',strtotime($recent->date));
        }
    }

    return response()->json([
        'result' => 'success',
        'message' => 'news List',
        'news'=>$recent_news,

    ],200);

}


public function newsList(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);

   $user = null;
   $news = null;
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'news' => $news,

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();

if (empty($user)){

    return response()->json([

        'result' => 'failure',

        'message' => '',
        'news' => $news,

    ],401);
}

$user = User::find($user->id);


$recent_news = News::where('date',date('Y-m-d'))->latest()->take(10)->get();
if(!empty($recent_news)){
    foreach($recent_news as $recent){
        if($recent->type == 'image'){
            $recent->image = url('admin/public/images/news/'.$recent->image);
        }

        // $recent->short_description =  mb_strlen(strip_tags($recent->short_description),'utf-8') > 50 ? mb_substr(strip_tags($recent->short_description),0,50,'utf-8').'...' : strip_tags($recent->short_description);


        $recent->date = date('d M Y',strtotime($recent->date));
    }
}


$month = date('F');
$month_num = date('m');
$year = date('Y');
$months = [];
$datesArr = [];


for($i=0;$i<=17;$i++){
    $datesArr['date'] = date("F, Y", strtotime("-".$i." months")); 
    $curmonth = date("m", strtotime("-".$i." months")); 
    $curyear = date("Y", strtotime("-".$i." months")); 
    $news = News::whereYear('date', '=', $curyear)->whereMonth('date', '=', $curmonth)->count();
    $datesArr['count'] = (string)$news;
    if($news > 0){
        array_push($months,$datesArr);

    }
}

$all_news = [];

return response()->json([
    'result' => 'success',
    'message' => 'news List',
    'recent_news'=>$recent_news,
    'news_months'=>$months,
],200);

}



public function getNewsListFromMonth(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'date' => 'required',
]);

   $user = null;
   $news = null;
   if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),
        'news' => $news,

    ],400);
}
$user = JWTAuth::parseToken()->authenticate();

if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
        'news' => $news,

    ],401);
}

$date = isset($request->date) ? $request->date :'';
$date = explode(", ", $date);
$curmonth = $date[0]; 
$curyear = $date[1]; 
$monthnum = date("m", strtotime($curmonth));
//DB::enableQueryLog(); // Enable query log

$news = News::whereYear('date', '=', $curyear)->whereMonth('date', '=', $monthnum)->orderby('id','desc')->paginate(10);
//dd(DB::getQueryLog()); // Show results of log


if(!empty($news)){
    foreach ($news as $key ) {
        $key->is_bookmark = 0;
        $bookmark = Bookmark::where('user_id',$user->id)->where('news_id',$key->id)->first();
        if(!empty($bookmark)){
            $key->is_bookmark = 1;
        }
        if($key->type == 'image'){
            if(!empty($key->image)){
                $key->image = url('admin/public/images/news/'.$key->image);
            }
        }else{
            $key->image = $key->image;
        }

    }
}


return response()->json([
    'result' => 'success',
    'message' => 'news List',
    'news'=>$news,

],200);

}






public function newsDetail(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'newsId' => 'required',
    ]);

    $user = null;
    $news = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'news' => $news,

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'news' => $news,

        ],401);
    }


    $news = News::where('id',$request->newsId)->first();
    if(!empty($news)){
        if($news->type == 'image'){
            $news->image = url('admin/public/images/news/'.$news->image);
        }
    }


    return response()->json([
        'result' => 'success',
        'message' => 'News Details',
        'news'=>$news,

    ],200);
}



public function bookmarkNews(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'newsId' => 'required',
]);

   $user = null;
   $news = null;
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


$news = News::where('id',$request->newsId)->first();

$bookmark = Bookmark::where('user_id',$user->id)->where('news_id',$request->newsId)->first();

$dbArray = [];
if(!empty($news)){
    if(empty($bookmark)){
        $dbArray['user_id'] = $user->id;
        $dbArray['is_bookmark'] = 1;
        $dbArray['news_id'] = $news->id;

        Bookmark::create($dbArray);
        return response()->json([
            'result' => 'success',
            'message' => 'Added To Bookmark Successfully',

        ],200);
    }else{
        Bookmark::where('id',$bookmark->id)->delete();
        return response()->json([
            'result' => 'success',
            'message' => 'Removed To Bookmark Successfully',

        ],200);
    }
}



}














public function changeCourse(Request $request){
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'course_id' => 'required',
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

            'message' => ''

        ],401);

    }

    $user = User::find($user->id);

    $user->board_id = $request->input("course_id");
    $user->save();

    return response()->json([

        'result' => 'success',

        'message' => 'success',

    ],200);
}


public function viewAllFreeVideoes(Request $request)

{

 $validator =  Validator::make($request->all(), [

    'token' => 'required',

]);

 $content = array();

 $current_timestamp = time();

 $user = null;

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

$user = User::find($user->id);



$board_id = $user->board_id;

$content=  Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.boards_id'=>$board_id,'contents.is_paid'=>'N','contents.type'=>'video'])->select(['contents.*'])->latest()->with('topic')->get();

foreach ($content as $fv) {

    $fv->thumbnail = $this->getBanners($fv->thumbnail);

}

return response()->json([

    'result' => 'success',

    'message' => '',

    'content' =>$content,

    'user'=>$user

],200);

}

public function resumeVideosList(Request $request)
{
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

    ]);

    $content = array();

    $current_timestamp = time();

    $user = null;

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

    $user = User::find($user->id);



    $board_id = $user->board_id;

    $content = Resume:: join('contents','resumes.content_id','=','contents.id')
    ->join('topics','contents.topic_id','=','topics.id')
    ->join('chapters','topics.chapter_id','=','chapters.id')
    ->where(['chapters.boards_id'=>$board_id,'contents.type'=>'video','resumes.user_id'=>$user->id,'resumes.status'=>'Playing'])
    ->select(['contents.*','resumes.time'])->latest()->take(10)->get();
    foreach ($content as $fv) {

        $fv->thumbnail = $this->getBanners($fv->thumbnail);
        $expired_on = $this->checkContentSubscription($fv->id,$user->id);
        if($expired_on > $current_timestamp){
            $fv->is_paid = "N";
        }
        $fv->topic = Topic::find($fv->topic_id);
    }


    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,

        'user'=>$user

    ],200);
}

public function allCourses(Request $request)
{
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

    ]);

    $content = array();

    $current_timestamp = time();

    $user = null;

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content

        ],401);

    }

    $content = Board::where(['status'=>'Y'])->get();

    foreach ($content as $row) {

        $row->subStatus = $this->_checkCourseSubscription($row->id,$user->id);
        if($row->image != null && $row->image != ""){
            $row->image = $this->getCourseImage($row->image);
        }

    }

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

    ],200);
}

public function viewAllCorner(Request $request)
{
   $validator =  Validator::make($request->all(), [

    'token' => 'required',

]);

   $content = array();

   $current_timestamp = time();

   $user = null;

   if ($validator->fails()) {

    return response()->json([

        'result' => 'failure',

        'message' => json_encode($validator->errors()),

        'content' =>$content

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();

if (empty($user)){

    return response()->json([

        'result' => 'failure',

        'message' => '',

        'content' =>$content

    ],401);

}

$content = Corner::latest()->paginate(10);

foreach ($content as $row) {

   $row->image = $this->getGalleryimage($row->image);

}

return response()->json([

    'result' => 'success',

    'message' => '',

    'content' =>$content

],200);



}

public function faqList(Request $request)
{

   $validator =  Validator::make($request->all(), [

    'token' => 'required',

]);

   $content = array();

   $current_timestamp = time();

   $user = null;

   if ($validator->fails()) {

    return response()->json([

        'result' => 'failure',

        'message' => json_encode($validator->errors()),

        'content' =>$content

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();

if (empty($user)){

    return response()->json([

        'result' => 'failure',

        'message' => '',

        'content' =>$content

    ],401);

}



$content = Faq::latest()->get();

return response()->json([

    'result' => 'success',

    'message' => '',

    'content' =>$content

],200);



}

public function classes(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'course_id'=>'required',

    ]);

    $content = array();

    $user = null;

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

        ],400);

    }

    $course_id = $request->course_id;

    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

        ],401);

    }

    $content = Subject::whereRaw("find_in_set($course_id,courses)")->get();

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,

    ],200);

}

public function topic(Request $request)
{
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'subjectId'=>'required'

    ]);

    $content = array();

    $subject = null;

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

            'subject'=>$subject

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    $subjectId = $request->subjectId;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

            'subject'=>$subject

        ],401);

    }

    $subject = Subject::find($subjectId);

    if (empty($subject)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

            'subject'=>$subject

        ],400);

    }

    $content = Topic::where(['subject_id'=>$subjectId])->oldest()->get();

    return response()->json([

        'result' => 'success',

        'message' => json_encode($validator->errors()),

        'content' =>$content,

        'subject'=>$subject

    ],200);

}

public function appSearch(Request $request)
{
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'key'=>'required'

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

    $key = $request->key;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

        ],401);

    }

    $topics = Topic::where('name','LIKE',"%{$key}%")->take(10)->get();

    foreach ($topics as $t){
        $content[] = array(
            "id"=>$t->id,
            "name"=>$t->name,
            "image"=>$this->getTopicImageUrl($t->image),
            "type"=>"Topic"
        );
    }

    return response()->json([

        'result' => 'success',

        'message' => "success",

        'content' =>$content,

    ],200);

}

    //User Notes
public function addUserNotes(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'type' => 'required',
        'id' => 'required',
        'status'=>'required',
        'text' =>'required'
    ]);
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors())
        ],400);
    }
    $status = $request->input('status');
    $type = $request->input('type');
    $id = $request->input('id');
    $text = $request->input('text');
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => 'Please Login Again',
        ],401);
    }
    if ($status == 'Y'){
        UserNote::updateOrCreate(
            ['user_id'=>$user->id,'type'=>$type,'type_id'=>$id],
            ['notes'=>$text]
        );
    }else{
        $bookmark = UserNote::where(['user_id'=>$user->id,'type'=>$type,'type_id'=>$id])->first();
        $bookmark->delete();
    }
    return response()->json([
        'result' => 'success',
        'message' => 'Successfully Updated',
    ],200);

}

public function listUserNotes(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $content = array();
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'content'=>$content
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => 'Please Login Again',
            'content'=>$content
        ],401);
    }
    $content = UserNote::where(['user_id'=>$user->id])->latest()->get();
    foreach ($content as $item)
    {
        $item->info = $this->getContent($item->type,$item->type_id,$user->id);
    }
    return response()->json([
        'result' => 'success',
        'message' => '',
        'content'=>$content
    ],200);
}

private function getContent($type,$id,$userId){
    $content = array(
        "title"=>'',
        "sub_title"=>'',
        "image"=>'',
        "text"=>'',
        "is_paid"=>'',
    );
    switch ($type){
        case 'video':
        $video = Content::find($id);
        if($video->is_paid == 'Y'){

            $expired_on = $this->checkContentSubscription($video->id,$userId);

            if($expired_on > time()){

                $video->is_paid = 'N';

            }

        }
        if (!empty($video)){
            $topic = Topic::where(['id'=>$video->topic_id])->first();
            $content = array(
                "title"=>$topic->name,
                "sub_title"=>'Video',
                "image"=>$this->getThumbnailUrl($video->thumbnail),
                "is_paid"=>$video->is_paid,
            );
        }
        break;
    }
    return $content;

}

public function getSingleNote(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'type'=>'required',
        "type_id"=>'required'
    ]);
    $content = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'content'=>$content
        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();
    $type = $request->input('type');
    $type_id = $request->input('type_id');
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => 'Please Login Again',
            'content'=>$content
        ],401);
    }
    $content = UserNote::where(['user_id'=>$user->id,'type'=>$type,'type_id'=>$type_id])->first();
    return response()->json([
        'result' => 'success',
        'message' => '',
        'content'=>$content
    ],200);
}

public function topicContents(Request $request)
{
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'id'=>'required'

    ]);

    $content = null;

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    $id = $request->id;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

        ],401);

    }

    $videos = Content::where(['topic_id'=>$id,'type'=>'video','contents.status'=>'Y'])->count();

    $notes = Content::where(['topic_id'=>$id,'type'=>'notes','contents.status'=>'Y'])->count();

    $mock_tests= Exams::where(['type'=>2,'topic_id'=>$id])->count();

    $content = array(
      "videos"=>$videos,
      "notes"=>$notes,
      "mock_test"=>$mock_tests
  );

    return response()->json([

        'result' => 'success',

        'message' => "success",

        'content' =>$content,

    ],200);

}


public function contents(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'topicId'=>'required'

    ]);

    $video = array();

    $documents = array();

    $current_timestamp = time();

    $content = [

        'video'=>$video,

        'documents'=>$documents,

    ];

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    $topicId = $request->topicId;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content

        ],401);

    }

    $video = Content::where(['type'=>'video','status'=>'Y','topic_id'=>$topicId])->with('topic')->get();

    foreach ($video as $r){

        if($r->is_paid == 'Y'){

            $expired_on = $this->checkContentSubscription($r->id,$user->id);

            if($expired_on > $current_timestamp){

                $r->is_paid = 'N';

            }

        }

    }

    $documents = Content::where(['type'=>'pdf','status'=>'Y','topic_id'=>$topicId])->with('topic')->get();

    foreach ($documents as $r){

        if($r->is_paid == 'Y'){

            $expired_on = $this->checkContentSubscription($r->id,$user->id);

            if($expired_on > $current_timestamp){

                $r->is_paid = 'N';

            }

        }

    }

    $booklets = Content::where(['type'=>'booklet','status'=>'Y','topic_id'=>$topicId])->with('topic')->get();

    foreach ($booklets as $r){

        if($r->is_paid == 'Y'){

            $expired_on = $this->checkContentSubscription($r->id,$user->id);

            if($expired_on > $current_timestamp){

                $r->is_paid = 'N';

            }

        }

    }

    $content = [

        'video'=>$video,

        'documents'=>$documents,

        'booklets'=>$booklets,

    ];

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

    ],200);

}



public function openContent(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'contentId'=>'required'

    ]);

    $content = null;

    $expired_on = strtotime("2020-05-31");



    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

            'expired_on'=>$expired_on,

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    $contentId = $request->contentId;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

            'expired_on'=>$expired_on,

        ],401);

    }

    $expired_on = $this->checkContentSubscription($contentId,$user->id);

    $content = Content::where(['id'=>$contentId,'status'=>'Y'])->with('topic')->with('subject')->first();

    $storage = Storage::where(['content_id'=>$contentId,'status'=>'Y'])->orderBy('id','DESC')->get();



    if (empty($content) || empty($storage)){

        return response()->json([

            'result' => 'failure',

            'message' => 'something went wrong',

            'content' =>$content,

        ],400);

    }

//        if($expired_on > time()){

//            $content->is_paid = 'N';

//        }

    foreach ($storage as $s){

        if ($content->type == 'video'){

            $s->content = $this->getVideoUrl($s->content);

        }else{



            if($expired_on < time()){

                $s->content = NULL;

                    // $s->exp = $expired_on;

                    // $s->ct = time();

            }else{

                $s->content = $this->getDocUrl($s->content);

            }



        }

    }

    $content->storage = $storage;

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,

        'expired_on'=>$expired_on,

    ],200);

}



public function relatedVideos(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'contentId'=>'required'

    ]);

    $content = array();

    $current_timestamp = time();

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    $contentId = $request->contentId;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content

        ],401);

    }

    $current = Content::where(['id'=>$contentId])->first();

    if (empty($current)){

        return response()->json([

            'result' => 'failure',

            'message' => 'something went wrong',

            'content' =>$content,

        ],400);

    }

    $content1 = Content::where(['topic_id'=>$current->topic_id,'type'=>'video','status'=>'Y'])

    ->where('id','>',$contentId)

    ->with('topic')->with('subject')->get();

    $content2 = Content::where(['topic_id'=>$current->topic_id,'type'=>'video','status'=>'Y'])

    ->where('id','<',$contentId)

    ->with('topic')->with('subject')->get();

    $content = array_merge(json_decode(json_encode($content1),true),json_decode(json_encode($content2),true));

    $content = json_decode(json_encode($content));

    foreach ($content as $r){

        if($r->is_paid == 'Y'){

            $expired_on = $this->checkContentSubscription($r->id,$user->id);

            if($expired_on > $current_timestamp){

                $r->is_paid = 'N';

            }

        }


        $check_subscription = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$r->topic_id)->first();



        if(!empty($check_subscription)){
            echo "string";

            $r->is_paid = 'N';
        }




    }

    foreach ($content as $c){
      $check_subscription = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$c->topic_id)->first();
      if(!empty($check_subscription)){
        echo "string";
        $c->is_paid == 'N';
    }
    $c->thumbnail = $this->getThumbnailUrl($c->thumbnail);

}

return response()->json([

    'result' => 'success',

    'message' => '',

    'content' =>$content,

],200);

}



public function courses(Request $request)

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

    $content = Course::get();

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,

    ],200);

}



private function checkContentSubscription($content_id,$student_id){

    $content = Content::find($content_id);
    $subject_id = $content->subject_id;
    $topic_id = $content->topic_id;
    $subject = Subject::find($subject_id);
    $courses = $subject->board_id;
    $expired_on = "2020-05-31";
        //check in course subscription
    $subscription = SubscriptionHistory::where(['user_id'=>$student_id,'subs_sub_type_id'=>$courses])
    ->whereDate('start_date', '<=', date('Y-m-d'))
    ->orderBy('end_date','desc')->first();
//        var_dump(['user_id'=>$student_id,'subs_sub_type_id'=>$courses]);
    if (!empty($subscription)){
        if ($subscription->end_date > $expired_on){
            $expired_on = $subscription->end_date;
        }
    }
    return strtotime($expired_on) + (3600 * 24);

}



public function requestMoney(Request $request)

{

    $validator = Validator::make($request->all(), [

        'token' => 'required',

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



    $message = $user->name." with mobile number ".$user->phone." has requested money from wallet.";

    $mobile = "7033016586";

    $this->send_message($mobile,$message);

    return response()->json([

        'result' => 'success',

        'message' => 'Request Submitted SuccessFully',

    ],200);

}



public function app_version(){

    $app_version = AppVersion::first();

    return response()->json([

        'result' => 'success',

        'message' => '',

        'version' => $app_version,

    ],200);

}

public function activeUsers(){
    return \Rainwater\Active\Active::usersWithinMinutes(10)->count();
}



public function moveFile(){

        // $contents = Content::where('type','!=','video')->where('id','>',73)->get();

        // foreach ($contents as $c){

        //     $storage = Storage::where(['content_id'=>$c->id])->get();

        //     foreach ($storage as $item){

        //         MoveFiles::dispatch($item->content,$c->type);

        //     }

        // }

    MoveFiles::dispatch('notes-4-current-electricity.pdf','pdf');

}



public function add_chat(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'live_class_id'=>'required',

        'faculties_id'=>'required',

        'text'=>'required',

    ]);

    $content = array();

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

    $user_id = $user->id;

    UserChats::create([

        'user_id'=>$user_id,

        'live_class_id'=>request('live_class_id'),

        'faculties_id'=>request('faculties_id'),

        'text'=>request('text'),

    ]);

    return response()->json([

        'result' => 'success',

        'message' => 'Send Message',

    ],200);

}

public function get_chat(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'live_class_id'=>'required',

        'faculties_id'=>'required',

    ]);

    $content = array();

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

    $liveclassid = request('live_class_id');

    $faculties_id = request('faculties_id');

    $content= DB::table('chats')

    ->select('chats.user_id','chats.text','users.name')

    ->join('users','chats.user_id','=','users.id')

    ->orderby('chats.id','DESC')

    ->where(['chats.live_class_id'=>$liveclassid,'chats.faculties_id'=>$faculties_id])

    ->limit(50)

    ->get();

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content'=>$content,

    ],200);

}

public function join_user(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'live_class_id'=>'required',

        'faculties_id'=>'required',

    ]);

    $content = array();

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

    $user_id = $user->id;

    $liveclassid = request('live_class_id');

    $faculties_id = request('faculties_id');

    $user_id = request('faculties_id');

    JoinLiveClasses::create([

        'live_class_id'=>$liveclassid,

        'faculties_id'=>$faculties_id,

        'user_id'=>$user_id

    ]);

    return response()->json([

        'result' => 'success',

        'message' => 'Join success',

    ],200);

}

public function join_user_list(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'live_class_id'=>'required',

        'faculties_id'=>'required',

    ]);

    $content = array();

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

    $content = JoinLiveClasses::where(['live_class_id'=>request('live_class_id'),'faculties_id'=>request('faculties_id')])->count();

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content'=>$content

    ],200);

}





public function contact(Request $request)

{

 $validator =  Validator::make($request->all(), [

    'token' => 'required',

    'name'=>'required',

    'email'=>'required',

    'subject'=>'required',

    'msg'=>'required'

]);

 $content = array();

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



Enquiry::create([

    'name'=>request('name'),

    'email'=>request('email'),

    'subject'=>request('subject'),

    'msg'=>request('msg'),

    'user_id'=>$user->id,



]);

return response()->json([

    'result' => 'success',

    'message' => 'Thank You for contacting us ',

],200);

}

public function resumeVideo(Request $request)
{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'content_id'=>'required',

        'time'=>'required',

        'status'=>'required',

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



    Resume::updateOrCreate(
        ['user_id'=>$user->id,'content_id'=>$request->input('content_id'),],

        ['time'=>$request->input('time'),'status'=>$request->input('status'),]
    );

    return response()->json([

        'result' => 'success',

        'message' => 'Success',

    ],200);

}





public function bookmark_list(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);
 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$bookmarks = [];
if (empty($user)){

    return response()->json([

        'result' => 'failure',

        'message' => '',

    ],401);

}

$bookmark =[];


$newsArr = [];
$pdfArr = [];
    // $bookmarks = Testimonial::where('status',1)->orderby('id','desc')->get();
$news_bookmark = DB::table('news_bookmark')->where('user_id',$user->id)->get();
if(!empty($news_bookmark)){

    foreach($news_bookmark as $news){
        $news_id = $news->news_id;
        $news_details = DB::table('news_feeds')->where('id',$news_id)->first();
        if(!empty($news_details)){
            if($news_details->type == 'image'){
                $news_details->image = url('admin/public/images/news/').'/'.$news_details->image;

            }
        }
        $newsArr[] = $news_details;
        //$news->details = $news_details;
    }


    $bookmark['news'] = $newsArr;

}

$pdf_bookmark = DB::table('bookmark_master')->where('type','pdf')->where('user_id',$user->id)->get();
if(!empty($pdf_bookmark)){
    //$bookmark['pdf'] = $pdf_bookmark;

    foreach($pdf_bookmark as $pdf){
        $pdf_id = $pdf->pdf_id;

        $pdfs = DB::table('weekmonthpdf')->where('id',$pdf_id)->first();
        if(!empty($pdfs)){
            if(!empty($pdfs->pdf)){
                $pdfs->pdf = url('/admin/public/images/monthlypdf/'.$pdfs->pdf);
            }
            $pdfs->is_bookmark = 1;
            $pdfArr[] = $pdfs;

        }


    }

    $bookmark['pdf'] = $pdfArr;

}

$videoArr = [];
$video_bookmark = DB::table('bookmark_master')->where('type','video')->where('user_id',$user->id)->get();
if(!empty($video_bookmark)){
   // $bookmark['video'] = $video_bookmark;

    foreach ($video_bookmark as $fv) {

        $videos =  Content::where('type','video')->where('id',$fv->video_id)->first();

        if(!empty($videos)){
          $videos->thumbnail = $videos->hls;

          //$videos->url = $this->storageUrl($videos->hls);

          $videos->skip = 0;

          $videos->duration = 100;
      }

      $videoArr[] = $videos;


  }

  $bookmark['video'] = $videoArr;
}

$quizArr = [];
$quiz_bookmark = DB::table('bookmark_master')->where('type','quiz')->where('user_id',$user->id)->get();
if(!empty($quiz_bookmark)){

    foreach ($quiz_bookmark as $fv) {
       $quiz = Exams::where('type',3)->where('id',$fv->quiz_id)->first();



       if(!empty($quiz)){
        $quiz->is_bookmark = 1;
    }else{
        $quiz->is_bookmark = 0;
    }


    $exam_list = Result::where('user_id',$user->id)->where('exam_id',$fv->quiz_id)->first();
    if(!empty($exam_list)){
     $quiz->exam_status = "Y";
 }else{
   $quiz->exam_status = "N";

}


$exam_question =  DB::table('questions')

->select('questions.*','exam_question.q_id','exam_question.marks')

->join('exam_question','questions.id','=','exam_question.q_id')

->where(['exam_question.exam_id'=>$fv->quiz_id])

->get();

$quiz->exam_question_count = count($exam_question);











$quizArr[] = $quiz;
}

$bookmark['quiz'] = $quizArr;
}




return response()->json([

    'result' => 'success',
    'message' => 'Bookmark List',
    'bookmarks' =>$bookmark,

],200);

}


public function bookmark_master(Request $request){
  $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => 'required',
    'id'=>'required',
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

$dbArray = [];
$dbArray['type'] = $request->type;

if($request->type == 'pdf'){

    $pdf_id = $request->id;
    $exist = DB::table('weekmonthpdf')->where('id',$pdf_id)->first();
    if(!empty($exist)){
        $dbArray['pdf_id'] = $pdf_id;
        $dbArray['video_id'] = '';
        $dbArray['user_id'] = $user->id;
        $pdf_exist = DB::table('bookmark_master')->where('user_id',$user->id)->where('pdf_id',$pdf_id)->first();
        if(empty($pdf_exist)){
            DB::table('bookmark_master')->insert($dbArray);
            return response()->json([
                'result' => 'success',
                'message' => 'Added to Bookmark Successfully',
            ],200);
        }else{
         DB::table('bookmark_master')->where('user_id',$user->id)->where('pdf_id',$pdf_id)->delete();
         return response()->json([
            'result' => 'success',
            'message' => 'Removed  Successfully',
        ],200);
     }
 }
} if($request->type == 'video'){
    $video_id = $request->id;
    $exist = Content::where('id',$video_id)->where('type','video')->first();
    if(!empty($exist)){
        $dbArray['pdf_id'] = '';
        $dbArray['video_id'] = $video_id;
        $dbArray['user_id'] = $user->id;

        $book_exist = DB::table('bookmark_master')->where('user_id',$user->id)->where('video_id',$video_id)->first();

        if(empty($book_exist)){
         DB::table('bookmark_master')->insert($dbArray);
         return response()->json([

            'result' => 'success',

            'message' => 'Added to Bookmark Successfully',


        ],200);
     }else{
         DB::table('bookmark_master')->where('user_id',$user->id)->where('video_id',$video_id)->delete();
         return response()->json([
            'result' => 'success',
            'message' => 'Removed  Successfully',
        ],200);
     }


 }
}

if($request->type == 'quiz'){
    $quiz_id = $request->id;

    $exist = Exams::where('id',$quiz_id)->first();
    if(!empty($exist)){
        $dbArray['pdf_id'] = '';
        $dbArray['quiz_id'] = $quiz_id;
        $dbArray['user_id'] = $user->id;

        $book_exist = DB::table('bookmark_master')->where('user_id',$user->id)->where('quiz_id',$quiz_id)->first();

        if(empty($book_exist)){
         DB::table('bookmark_master')->insert($dbArray);
         return response()->json([
            'result' => 'success',
            'message' => 'Added to Bookmark Successfully',
        ],200);
     }else{
         DB::table('bookmark_master')->where('user_id',$user->id)->where('quiz_id',$quiz_id)->delete();
         return response()->json([
            'result' => 'success',
            'message' => 'Removed  Successfully',
        ],200);
     }

 }
}




}



public function program_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',

]);
   $user = null;
   $content = [];
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
$array = [];
$boards = Board::get();
if(!empty($boards)){
    foreach($boards as $board){
        $programs = Topic::where('course_id',$board->id)->get();

        if(!empty($programs)){
            if(!empty($programs->image)){
                $programs->image = url('admin/public/images/topic/'.$programs->image);
            }
        }
        $array['board_name'] = $board->board_name;
        $array['program'] = $programs;
        $content[] = $array;
    }
}






return response()->json([

    'result' => 'success',

    'message' => 'Program List',
    'content' =>$content,

],200);

}




public function subscription_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
    ]);
    $user = null;
    $content = [];
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

    $subscription_array = [];
    $nonsubscription_array = [];
    $existArr = [];

    $subExist = [];
///////Subscription
    $check_exist = DB::table('subscription_histories')->where('user_id',$user->id)->orderby('id','desc')->groupBy('subject_id')->get();
    if(!empty($check_exist) && count($check_exist) > 0){
        foreach($check_exist as $check){
            $topic = Topic::where('id',$check->topic_id)->first();
            if(!empty($topic)){
                if(!empty($topic->image)){
                    $topic->image = url('admin/public/images/topic/'.$topic->image);
                }
            }
            $subject = Subject::where('id',$check->subject_id)->where('is_delete',0)->first();



            if(!empty($subject)){
               $faculties = Faculties::select('id','name')->where('id',$subject->faculties_id)->first();

               $subject->faculties_name = $faculties->name ?? '';
           }



           $board = Boards::where('id',$check->board_id)->first();
           $check->board = $board;
           $check->subject = $subject;
           $check->topic = $topic;
           $existArr[] = $check->topic_id;




           if(!empty($subject) && !empty($faculties)){
            $subExist[] = $check;

        }

    }
}
$content['subscription_array'] = $subExist;
////NonSubscription

$topics = Topic::whereNotIn('id',$existArr)->orderby('id','desc')->get();
if(!empty($topics)){
    foreach($topics as $topic){
        $subject = Subject::where('id',$topic->subject_id)->first();
        $board = Boards::where('id',$topic->course_id)->first();

        if(!empty($topic->image)){
            $topic->image = url('admin/public/images/topic/'.$topic->image);
        }



        $topic->board = $board;
        $topic->subject = $subject;
    }
}



$content['nonsubscription_array'] = $topics;




return response()->json([

    'result' => 'success',

    'message' => 'Subscription List',
    'content' =>$content,

],200);

}

public function notification(Request $request){
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
    $notification = [];
    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

        ],401);

    }

    $notification = DB::table('notifications')->where('userID',$user->id)->get();
    return response()->json([
        'result' => 'success',
        'message' => 'notification List',
        'notification' =>$notification,

    ],200);





}




public function testimonials(Request $request){
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
    $testimonials = [];
    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

        ],401);

    }

    $testimonials = Testimonial::where('status',1)->orderby('id','desc')->get();
    if(!empty($testimonials)){
        foreach ($testimonials as $key) {
          //  $user =  DB::table('users')->select('id','name','image')->where('id',$key->user_id)->first();
            // if(empty($user->image)){
            //     $user->image = url('/public/uploads/images/img_avatar.png');
            // }
            $user = null;
            if(!empty($user)){
                $key->user = $user;
            }

            $key->image = url('admin/public/images/testimonial/').'/'.$key->image;
        }
    }


    return response()->json([

        'result' => 'success',

        'message' => 'Testimonials List',
        'testimonials' =>$testimonials,

    ],200);
}

public function news_search(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'key' => 'required',
]);

 $user = null;

 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$news = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$keyword = isset($request->key) ? $request->key :'';

$exist_news = News::where('title', 'like', '%' . $keyword . '%')->orwhere('description', 'like', '%' . $keyword . '%')->orwhere('tags', 'like', '%' . $keyword . '%')->get();

if(!empty($exist_news)){
    foreach ($exist_news as $new) {

       if(!empty($new->image)){
        $new->is_bookmark = 0;
        $bookmark = Bookmark::where('user_id',$user->id)->where('news_id',$new->id)->first();
        if(!empty($bookmark)){
            $new->is_bookmark = 1;
        }

        $new->image = url('admin/public/images/news/').'/'.$new->image;
    } 
}
}

return response()->json([

    'result' => 'success',

    'message' => 'news List',
    'news' =>$exist_news,

],200);






}


public function program_details(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'topic_id' => 'required',
    ]);

    $user = null;

    if ($validator->fails()) {

        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();
    $content = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }
    $topic_id = isset($request->topic_id) ? $request->topic_id :'';

    $topics = Topic::where('id',$topic_id)->first();
    $boards = Boards::where('id',$topics->course_id)->first();
    $subject = Subject::where('id',$topics->subject_id)->first();

    $topics->board_name = isset($boards->board_name) ? $boards->board_name :'';
    $topics->subject_name = isset($subject->title) ? $subject->title :'';
    $gst_price = 0;
    $subscription_amount = isset($topics->subscription_amount) ? $topics->subscription_amount :0;
    if($subscription_amount !=0){
        $percent = ($subscription_amount * 18) / 100 ;

        $gst_price = $percent + $subscription_amount;
    }
    $topics->gst_price = (string) round($gst_price, 2);   


    $subscription_histories = SubscriptionHistory::where('topic_id',$topic_id)->where('user_id',$user->id)->first();
    if(!empty($subscription_histories)){
        $date2 = $subscription_histories->end_date;
        $date1 = date('Y-m-d');

        $diff = abs(strtotime($date2) - strtotime($date1));

        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));



        $topics->remainingday =  $days;
    }




    $content = $topics; 




    return response()->json([

        'result' => 'success',
        'message' => 'Program Details',
        'content' =>$content,

    ],200);


}











private function checkExamSubscriptionNew($board_id,$student_id){
    $expired_on = "2020-05-31";
        //check in course subscription
    $subscription = SubscriptionHistory::where(['user_id'=>$student_id,'subs_sub_type_id'=>$board_id])
    ->whereDate('start_date', '<=', date('Y-m-d'))
    ->orderBy('end_date','desc')->first();
//        var_dump(['user_id'=>$student_id,'subs_sub_type_id'=>$courses]);
    if (!empty($subscription)){
        if ($subscription->end_date > $expired_on){
            $expired_on = $subscription->end_date;
        }
    }
    return strtotime($expired_on) + (3600 * 24);
}





public function exam_list(Request $request){
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
$exam_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$exam_details = [];
$exam_list = Result::where('user_id',$user->id)->get();
if(!empty($exam_list)){
    foreach($exam_list as $ex){
        $exam_id = $ex->exam_id;
        $row = Exams::where('id',$exam_id)->first();
        $expired_on = $this->checkExamSubscriptionNew($row->board_id,$user->id);
        if($row->is_paid == 'Y' && $expired_on > time()){
            $row->is_paid = 'N';
        }

        $user_id = $user->id;
        $row->image ='';
        if(!empty($row->image)){
            $row->image = $this->getExamImageUrl($row->image);
        }

        if (isset($row)) {

            $row->exam_status = "Y";

        }

        else{

            $row->exam_status = "N";

        }
        $exam_question =  DB::table('questions')

        ->select('questions.*','exam_question.q_id','exam_question.marks')

        ->join('exam_question','questions.id','=','exam_question.q_id')

        ->where(['exam_question.exam_id'=>$row->id])

        ->get();

        $row->exam_question_count = count($exam_question);

        $exam_details[] = $row;
    }
}



return response()->json([

    'result' => 'success',

    'message' => 'exam_list',
    'content' =>$exam_details,

],200);


}


public function ask_doubt(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
]);

 $user = null;
 $content =[];

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

$content = DB::table('doubts')->where('sender_id',$user->id)->orWhere('receiver_id',$user->id)->paginate(10);

return response()->json([

    'result' => 'success',

    'message' => 'Successfully',
    'content' =>$content,

],200);


}




public function send_messages(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'message' => 'required',
]);
 $user = null;
 $content =[];

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

$dbArray = [];

$dbArray['sender_id'] = $user->id;
$dbArray['receiver_id'] = 0;
$dbArray['sender_type'] = 'user';
$dbArray['receiver_type'] = 'admin';
$dbArray['message'] = $request->message;
$dbArray['status'] = 1;

DB::table('doubts')->insert($dbArray);

return response()->json([

    'result' => 'success',

    'message' => 'Successfully',
    // 'content' =>$content,

],200);

}

public function app_sidebar(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type_id' => 'required',

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




$sidebarArr = config('custom.sidebars');


$Arr = [];
$app_sidebar = null;

if(!empty($sidebarArr)){
    foreach ($sidebarArr as $key => $value) {


       // print_r($value);



        $details = null;
        if($key == $request->type_id){
            $details =  DB::table('app_sidebar')->where('bar_id',$key)->latest()->get();
            if(!empty($details)){
                foreach($details as $de){
                    if($de->type == 'pdf' || $de->type == 'image'){
                        $de->file = url('admin/public/images/app_sidebar/'.$de->file);
                    }
                }
            }


            $app_sidebar['name'] = $value;
            $app_sidebar['details'] = $details;


        }
        



    }
}



return response()->json([

    'result' => 'success',

    'message' => 'list',
    'app_sidebar' =>$app_sidebar,

],200);






}


public function settings(Request $request){
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

    $setting = DB::table('settings')->where('id',1)->first();


    return response()->json([

        'result' => 'success',

        'message' => 'exam_list',
        'settings' =>$setting,

    ],200);






}






public function video_rating(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'videoId' => 'required',
    'star' => 'required',
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

$dbArray = [];

$dbArray['user_id'] = $user->id;
$dbArray['video_id'] = $request->videoId;
$dbArray['star'] = $request->star;
$dbArray['text'] = isset($request->text) ? $request->text :'';

DB::table('video_rating')->insert($dbArray);




return response()->json([

    'result' => 'success',

    'message' => 'Rating Successfully',


],200);
}



public function user_video_rating(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'videoId' => 'required',
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
$average_rating = 0;
$is_bookmark = 0;

$rating = DB::table('video_rating')->where('video_id',$request->videoId)->where('user_id',$user->id)->first();

$is_rating = 0;
if(!empty($content)){
    $is_rating = 1;
}


$all_rating = DB::table('video_rating')->where('video_id',$request->videoId)->get();
$rat = 0;
if(!empty($all_rating)){
    foreach($all_rating as $ra){
        $rat+=$ra->star;
    }
}
if(!empty($all_rating)){
  $average_rating = $rat / count($all_rating);

}



$bookmarks = DB::table('bookmark_master')->where('user_id',$user->id)->where('video_id',$request->videoId)->first();
if(!empty($bookmarks)){
    $is_bookmark = 1;
}


return response()->json([

    'result' => 'success',
    'message' => 'Rating Successfully',
    'is_rating' =>$is_rating,
    'is_bookmark' =>$is_bookmark,

    'rating'=>$rating,
    'average_rating'=>$average_rating,

],200);
}



private function add_user_to_group($userid){
    if(!empty($userid)){
        $groups = DB::table('chat_group')->get();
        if(!empty($groups)){
            foreach($groups as $group){
                $subscription_histories = SubscriptionHistory::where('user_id',$userid)->where('end_date','>',date('Y-m-d'))->where('topic_id',$group->program_id)->first();
                if(!empty($subscription_histories)){
                    $dbArray = [];

                    $dbArray['user_id'] = $userid;
                    $dbArray['group_id'] = $group->id;
                    $check = DB::table('chat_users')->where('user_id',$userid)->where('group_id',$group->id)->first();
                    if(empty($check)){
                        DB::table('chat_users')->insert($dbArray);

                    }


                }


            }
        }
    }


}


public function chat_program(Request $request){
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
$programs = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$chat_users = DB::table('chat_users')->where('user_id',$user->id)->get();
if(!empty($chat_users)){
    foreach($chat_users as $chat){
        $group = DB::table('chat_group')->where('id',$chat->group_id)->first();
        $topics = Topic::select('id','name','image')->where('id',$group->program_id)->first();

        $topics->image = $this->getTopicImageUrl($topics->image);
        $topics->group_id = $chat->group_id;
        $topics->topic_id = $topics->id;

        $programs[] = $topics;
    }
}





return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'programs' =>$programs,
],200);




}


public function get_group_chat(Request $request){
  $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'group_id' => 'required',
]);

  $user = null;

  if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$chats = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
// echo $user->id;
$chats = DB::table('group_chat')->where('g_id',$request->group_id)->latest()->paginate(10);
if(!empty($chats)){
    foreach($chats as $chat){
        if($chat->user_id == $user->id){
            $user = User::where('id',$chat->user_id)->first();
            $chat->side = 'right';
            $chat->name = $user->name;
            
            $chat->image = url('/public/images/user.png');
            if(!empty($user)){
                if(!empty($user->image)){
                    $chat->image = url('/public/images/'.$user->image);
                }
            }
        }else{
            $chat->side = 'left';

            //$user = User::where('id',$chat->user_id)->first();
            $chat->name = 'Admin';
            
            $chat->image = url('/public/images/user.png');
            // if(!empty($user)){
            //     if(!empty($user->image)){
            //         $chat->image = url('/public/images/'.$user->image);
            //     }
            // }
        }
        


    }
}

return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'chats' =>$chats,
],200);


}

public function submit_chat(Request $request){
  $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'group_id' => 'required',
    'text' => 'required',

]);

  $user = null;

  if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$chats = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$dbArray = [];
$dbArray['g_id'] = $request->group_id;
$dbArray['user_id'] = $user->id;
$dbArray['text'] = $request->text;


DB::table('group_chat')->insert($dbArray);

// $chats = DB::table('group_chat')->where('g_id',$request->group_id)->orderby('id','desc')->get();
// if(!empty($chats)){
//     foreach($chats as $chat){

//     }
// }

return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    //'chats' =>$chats,
],200);


}

public function video_categories(Request $request){
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
    $allcategories =[];
    $categories = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }

    $categories = config('custom.video_categories');
    if(!empty($categories)){
        foreach ($categories as $key => $value) {

          $dbArray = [];
          $dbArray['id'] = $key;
          $dbArray['value'] = $value;  


          array_push($allcategories,$dbArray);

      }
  }

  return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'categories' =>$allcategories,
],200);

}


public function promotional_video(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    //'cat_id' => 'required',

]);

   $user = null;

   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$promotional_video = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$promotional_video = DB::table('promotional_video')->latest()->where('status',1)->get();


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'promotional_video' =>$promotional_video,
],200);




}


public function offer_zone(Request $request){
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
    $offer_zone = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }

    $offer_zone = Topic::where('is_offer','Y')->get();
    if(!empty($offer_zone)){
        foreach($offer_zone as $offer){
            $offer->offer_banner = url('admin/public/images/topic/'.$offer->offer_banner);
        }
    }

    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',
        'program_list' =>$offer_zone,
    ],200);


}


public function course_list(Request $request){
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
$courses = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$boards= Boards::select('id','board_name')->where('status','Y')->get();
return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'courses' =>$boards,
],200);




}


public function program_list_by_course(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'course_id' => 'required',
]);

   $user = null;

   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$program_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$program_list = Topic::where('course_id',$request->course_id)->get();
if(!empty($program_list)){
    foreach($program_list as $pro){
        if(!empty($pro->image)){
            $pro->image = url('admin/public/images/topic/'.$pro->image);
        }

        $subject = Subject::select('id','title')->where('id',$pro->subject_id)->first();


        $board = Boards::select('id','board_name')->where('id',$pro->course_id)->first();


        $pro->boards = $board;
        $pro->subject = $subject;
    }
}





return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'program_list' =>$program_list,
],200);






}


public function assignment_list(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'topic_id' => 'required',
    ]);

    $user = null;

    if ($validator->fails()) {

        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();
    $assignments = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }
    $program_ids = [];
    $sub_history = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$request->topic_id)->where('end_date','>=',date('Y-m-d'))->first();
    if(!empty($sub_history)){
        // foreach($sub_history as $sub){
        //     $program_ids[] = $sub->topic_id;
        // }


        $assignments = DB::table('assignment')->where('topic_id',$request->topic_id)->orderby('id','desc')->paginate(10);
        if(!empty($assignments)){
            foreach($assignments as $assign){
                $assign->pdf = url('/admin/public/assignment/admin/'.$assign->pdf);
                $assign->is_submit = 'N';

                $exist = DB::table('assignment_result')->where('assignment_id',$assign->id)->first();
                if(!empty($exist)){
                    $assign->is_submit = 'Y';
                    $assign->created_at = date('Y-m-d',strtotime($assign->created_at));

                }
            }
        }




    }

    




    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',
        'assignments' =>$assignments,
    ],200);




}




public function solveed_assignment(Request $request){
  $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'assignment_id' => 'required',
    'pdf' => 'required|mimes:pdf',
]);

  $user = null;

  if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$solveed_assignment = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}


$dbArray = [];
if($request->hasFile('pdf')){

    $destinationPath = public_path("/assignment");

    $side = $request->file('pdf');

    $side_name = $user->id.'_assignment'.time().'.'.$side->getClientOriginalExtension();

    $side->move($destinationPath, $side_name);

    $dbArray['pdf'] = $side_name;

}

$dbArray['assignment_id']= $request->assignment_id;
$dbArray['user_id']= $user->id;


DB::table('assignment_result')->insert($dbArray);



return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
],200);

}

public function quiz_list(Request $request){
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
$quiz = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}


$latest_quiz = Exams::where('type',3)->latest()->take(10)->get();
if(!empty($latest_quiz)){
    foreach($latest_quiz as $q){
        $bookmark = DB::table('bookmark_master')->where('type','quiz')->where('user_id',$user->id)->where('quiz_id',$q->id)->first();
        if(!empty($bookmark)){
            $q->is_bookmark =1;
        }else{
            $q->is_bookmark =0;
        }
        $q->is_attend = 'N';
        $results = DB::table('results')->where('user_id',$user->id)->where('exam_id',$q->id)->first();
        if(!empty($result)){
            $q->is_attend = 'Y';

        }
        $q->created_at = date('Y-m-d',strtotime($q->created_at));
        $q->date = date('d M Y',strtotime($q->created_at));

        $exam_question =  DB::table('questions')

        ->select('questions.*','exam_question.q_id','exam_question.marks')

        ->join('exam_question','questions.id','=','exam_question.q_id')

        ->where(['exam_question.exam_id'=>$q->id])

        ->get();

        $q->exam_question_count = count($exam_question);


        $q->created_at = date('Y-m-d',strtotime($q->created_at));

    }
}

$month = date('F');
$month_num = date('m');
$year = date('Y');
$months = [];
$datesArr = [];


for($i=0;$i<=17;$i++){
    $datesArr['date'] = date("F, Y", strtotime("-".$i." months")); 
    $curmonth = date("m", strtotime("-".$i." months")); 
    $curyear = date("Y", strtotime("-".$i." months")); 
    $quizs = Exams::where('type',3)->whereYear('created_at', '=', $curyear)->whereMonth('created_at', '=', $curmonth)->count();
    $datesArr['count'] = (string)$quizs;
    if($quizs > 0){
        array_push($months,$datesArr);

    }
}


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'latest_quiz' =>$latest_quiz,
    'months' =>$months,
],200);
}



public function get_quiz_list_from_month(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'date' => 'required',
    ]);

    $user = null;
    $quizs = null;
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),
            'quizs' => $quizs,

        ],400);
    }
    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
            'quizs' => $quizs,

        ],401);
    }

    $date = isset($request->date) ? $request->date :'';
    $date = explode(", ", $date);
    $curmonth = $date[0]; 
    $curyear = $date[1]; 
    $monthnum = date("m", strtotime($curmonth));
//DB::enableQueryLog(); // Enable query log

    $quizs = Exams::select('*')->where('type',3)->whereYear('created_at', '=', $curyear)->whereMonth('created_at', '=', $monthnum)->orderby('id','desc')->paginate(10);

    if(!empty($quizs)){
        foreach ($quizs as $q) {
            $q->created_at = date('Y-m-d', strtotime($q->created_at));


            $bookmark = DB::table('bookmark_master')->where('user_id',$user->id)->where('type','quiz')->where('quiz_id',$q->id)->first();
            if(!empty($bookmark)){
                $is_bookmark = 1;
            }else{
                $is_bookmark = 0;
            }
            $is_attend = 'N';
            $results = DB::table('results')->where('user_id',$user->id)->where('exam_id',$q->id)->first();
            if(!empty($result)){
                $is_attend = 'Y';

            }

            $exam_question =  DB::table('questions')

            ->select('questions.*','exam_question.q_id','exam_question.marks')

            ->join('exam_question','questions.id','=','exam_question.q_id')

            ->where(['exam_question.exam_id'=>$q->id])

            ->get();

            $q->exam_question_count = count($exam_question);

            $q->date = date('Y-m-d',strtotime($q->created_at));


        // $quizs[] = array(
        //     'id'=>$q->id,
        //     'title'=>$q->_title,
        //     'type'=>$q->type,
        //     'instruction'=>$q->instruction,
        //     'image'=>$q->image,
        //     'start_date'=>$q->start_date,
        //     'end_date'=>$q->end_date,
        //     'start_time'=>$q->start_time,
        //     'end_time'=>$q->end_time,
        //     'session_time'=>$q->session_time,
        //     'board_id'=>$q->board_id,
        //     'class_id'=>$q->class_id,
        //     'sub_id'=>$q->sub_id,
        //     'topic_id'=>$q->topic_id,
        //     'language'=>$q->language,
        //     'create_by'=>$q->create_by,
        //     'create_id'=>$q->create_id,
        //     'status'=>$q->status,
        //     'is_paid'=>$q->is_paid,
        //     'reward_mark'=>$is_attend,
        //     'is_bookmark'=>$q->status,
        //     'is_attend'=>$is_attend,
        //     'created_at'=>date('Y-m-d',strtotime($q->created_at)),
        //     'exam_question_count'=>count($exam_question),


        // );




        }
    }


    return response()->json([
        'result' => 'success',
        'message' => 'quizs List',
        'quizs'=>$quizs,

    ],200);
}





public function video_list(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'cat_id' => 'required',

]); 

   $user = null;

   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$video = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$free_video=  DB::table('promotional_video')->where('cat_id',$request->cat_id)->where('status',1)->latest()->get();

if(!empty($free_video)){
    foreach ($free_video as $fv) {
        $fv->created_at = date('Y-m-d',strtotime($fv->created_at));

    }
}







return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'videos' =>$free_video,
],200);
}


public function pdf_list(Request $request){
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
$pdf = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$pdfs =  Content::join('topics','contents.topic_id','=','topics.id')->where(['contents.is_paid'=>'N','contents.status'=>'Y','contents.type'=>'notes'])->select(['contents.*'])->latest()->paginate(10);;




if(!empty($pdfs)){
    foreach($pdfs as $pdf){
        $bookmark = DB::table('bookmark_master')->where('type','pdf')->where('pdf_id',$pdf->id)->first();
        if(!empty($bookmark)){
            $pdf->is_bookmark = 1;
        }else{
            $pdf->is_bookmark = 0;
        }

        $pdf->date = date('Y-m-d',strtotime($pdf->created_at));
        if(!empty($pdf->hls)){
            $pdf->hls = url('admin/public/content/notes/').'/'.$pdf->hls;
            $pdf->expiredOn = isset($subscription->end_date) ? $subscription->end_date.' 23:59:59' :"";
        } 
    }
}








return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'pdfs' =>$pdfs,
],200);
}


public function books_list(Request $request){
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
    $books = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }

    $is_prime = 'N';

    $exist = DB::table('prime')->where('user_id',$user->id)->first();
    if(!empty($exist)){
        $is_prime = 'Y';
    }

    $book_cate = [];
    $all_book = [];

    $books_category = BookCategory::where('status',1)->get();
    if(!empty($books_category)){
        foreach($books_category as $book_cat){


            $books = Book::where('category',$book_cat->id)->get();
            if(!empty($books)){
                foreach($books as $book){
                    $book->file_name = $this->url.'/admin/public/images/books/'.$book->file_name;
                    $book->image = $this->url.'/admin/public/images/books/book_one.jpg';
                    $book->is_prime = $is_prime;

                }

                

            }

            $book_cat->books = $books;

            if(!empty($books) && count($books) > 0){
                $book_cate[] = $book_cat;
            }


        }
    }


    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',
        //'is_prime' => $is_prime,
        'books' =>$book_cate,
    ],200);







}





















public function monthlypdf(Request $request){
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
$pdf = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$pdfs = DB::table('weekmonthpdf')->where('status',1)->latest()->take(10)->get();
if(!empty($pdfs)){
    foreach($pdfs as $pdf){
        $pdf->new_id = 'A'.$pdf->id;
        $pdf->pdf = url('/admin/public/images/monthlypdf/'.$pdf->pdf);
        $pdf->is_bookmark = 0;
        $bookmark_master = DB::table('bookmark_master')->where('user_id',$user->id)->where('pdf_id',$pdf->id)->first();
        if(!empty($bookmark_master)){
            $pdf->is_bookmark = 1;
        }

    }
}


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'pdfs' =>$pdfs,
],200);



}

public function get_prime_content(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => 'required',
]);

   $user = null;

   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$contents = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$type = isset($request->type) ? $request->type :'video';

$all_content = [];


$courses = PrimeCourse::select('id','title')->where('status',1)->get();
if(!empty($courses)){
    foreach($courses as $course){
        // if(!empty($course->file_name)){
        //     $course->file_name = $this->url.'/admin/public/images/primecourse/'.$course->file_name;
        // }
//         $videos = PrimeContent::where('course_id',$course->id)->where('type','video')->where('status',1)->get();
//         $course->videos = $videos;
//         $notes = PrimeContent::where('course_id',$course->id)->where('type','notes')->where('status',1)->get();
//         if(!empty($notes)){
//            foreach($notes as $note){
//              if(!empty($note->file_name)){
//                     $note->file_name = $this->url.'/admin/public/primecontent/notes/'.$note->file_name;

//             }

//            }
//         }
//         $course->notes = $notes;


        $contents = PrimeContent::where('course_id',$course->id)->where('type',$type)->where('status',1)->get();
        if(!empty($contents)){
           foreach($contents as $content){
             if(!empty($content->type == 'notes')){
                $content->file_name = $this->url.'/admin/public/primecontent/notes/'.$content->file_name;
            }
        }


        $course->contents = $contents;
    }

    if(!empty($contents) && count($contents) > 0){
        $all_content[] = $course;
    }



}
}



return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'video' =>$all_content,
],200);




}





public function get_feedback_content(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => 'required',
]);

   $user = null;

   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$contents = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$type = isset($request->type) ? $request->type :'video';

$courses = FeedbackCourse::select('id','title')->where('status',1)->get();
if(!empty($courses)){
    foreach($courses as $course){
        $contents = FeedbackContent::where('course_id',$course->id)->where('type',$type)->where('status',1)->get();
        if(!empty($contents)){
           foreach($contents as $content){
             if(!empty($content->type == 'notes')){
                $content->file_name = $this->url.'/admin/public/feedbackcontent/notes/'.$content->file_name;
            }
        }


        $course->contents = $contents;
    }

}
}



return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'video' =>$courses,
],200);




}





public function monthlypdf_list(Request $request){
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
$pdf = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$pdfs = DB::table('weekmonthpdf')->where('status',1)->latest()->paginate(10);
if(!empty($pdfs)){
    foreach($pdfs as $pdf){
        $pdf->new_id = 'A'.$pdf->id;
        $pdf->pdf = url('/admin/public/images/monthlypdf/'.$pdf->pdf);
        $pdf->is_bookmark = 0;
        $bookmark_master = DB::table('bookmark_master')->where('user_id',$user->id)->where('pdf_id',$pdf->id)->first();
        if(!empty($bookmark_master)){
            $pdf->is_bookmark = 1;
        }
    }
}


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'pdfs' =>$pdfs,
],200);



}


//////////////////////////////////////////Exam Type Chat//////////////////////



public function get_exam_groups(Request $request){
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
$exam_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$is_prime = 'N';


$prime = DB::table('prime')->where('user_id',$user->id)->first();

if(!empty($prime)){
    $is_prime = 'Y';
}

$exam_list = DB::table('exam_type')->where('status',1)->get();
if(!empty($exam_list)){
    foreach($exam_list as $exam){
        $already_joined = 'N';


        $exist = DB::table('exam_group')->where('user_id',$user->id)->where('type_id',$exam->id)->first(); 
        if(!empty($exist)){
            $already_joined = 'Y';

        }

        if($is_prime == 'N'){
            $exam->status = "0";
        }
        else if($is_prime == 'Y' && $already_joined == 'Y'){
            $exam->status = "1";

        }else{
            $exam->status = "0";

        }

    }
}



return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'is_prime' =>$is_prime,
    'exam_list' =>$exam_list,
],200);


}


public function add_user_to_exam_group(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'exam_group_id' => 'required',
]);

 $user = null;

 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$exam_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}


$dbArray = [];
$dbArray['type_id'] = $request->exam_group_id;
$dbArray['user_id'] = $user->id;
$dbArray['status'] = 1;

$exist = DB::table('exam_group')->where(['user_id'=>$user->id])->first();
if(!empty($exist)){

    DB::table('exam_group')->where('id',$exist->id)->update($dbArray);

}else{
    DB::table('exam_group')->insert($dbArray);

}


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    
],200);







}




public function get_exam_type_chat(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'exam_group_id' => 'required',
]);

 $user = null;

 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$exam_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$chats = DB::table('exam_type_chat')->where('type_id',$request->exam_group_id)->paginate(15);

if(!empty($chats)){
    foreach($chats as $chat){
        $image = url('/public/images/user.png');

        if($chat->user_id == 0){
            $chat->name = 'Admin';
        }else{
         $users = User::where('id',$chat->user_id)->first();
         $chat->name = $users->name ?? '';
         if(!empty($users->image)){
            $image =  url('/public/images/'.$users->image);
        }
    }



    $chat->image = $image;

    $chat->created_at = date('d M Y',strtotime($chat->created_at));

    if($chat->user_id == $user->id){
        $poition = 'right';
    }else{
        $poition = 'left';

    }

    $chat->poition = $poition;

}
}



return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'chats' =>$chats,
    
],200);





}


public function get_live_classes(Request $request){

   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'batch_id' => 'required',
]);

   $user = null;

   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();

$live = LiveClass::where(['status'=>'Y','course_id'=>$request->batch_id])->with('faculties')->get();

foreach ($live as  $row) {

  $row->image = $this->getBanners($row->image);

}

return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'live_classes' => $live,
    
],200);


}

public function save_chats(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'exam_group_id' => 'required',
    'text' => 'required',
]);

 $user = null;

 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);

}

$user = JWTAuth::parseToken()->authenticate();
$exam_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$dbArray = [];
$dbArray['user_id'] = $user->id;
$dbArray['type_id'] = $request->exam_group_id;
$dbArray['text'] = $request->text;


DB::table('exam_type_chat')->insert($dbArray);

return response()->json([

    'result' => 'success',
    'message' => ' Successfully',

    
],200);


}


public function add_get_subject(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'type' => '',
    ]);

    $user = null;
    if ($validator->fails()) {

        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);
    }

    $user = JWTAuth::parseToken()->authenticate();
    $sub_list = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }

    if($request->type == 'add'){
       $validator1 =  Validator::make($request->all(), [
        'token' => 'required',
        'sub_name' => 'required',
    ]);

       if ($validator1->fails()) {

        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator1->errors()),

        ],400);
    }




    $dbArray = [];
    $dbArray['user_id'] = $user->id;
    $dbArray['sub_name'] = $request->sub_name;
    DB::table('student_subject')->insert($dbArray);
}

$sub_list = DB::table('student_subject')->where('user_id',$user->id)->get();


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'sub_list' =>$sub_list,

],200);

}




public function get_student_events(Request $request){
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
    $sub_list = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }



    $event_dates = DB::table('events')->where('user_id',$user->id)->groupBy('date')->get();
    if(!empty($event_dates)){
        foreach ($event_dates as $key) {
            $dbArray = [];

            $dbArray['date'] = $key->date;

            $all_event = DB::table('events')->where('user_id',$user->id)->whereDate('date',$key->date)->get();

            if(!empty($all_event)){
                foreach($all_event as $all){
                    $event_details = DB::table('student_events')->where('user_id',$user->id)->where('id',$all->event_id)->first();

                    $all->sub_name = $event_details->sub_name;
                    $all->chapter_name = $event_details->chapter_name;
                    $all->status = $event_details->status;
                    $all->strategy = $event_details->strategy;
                    $all->start_date = $event_details->start_date;
                    $all->end_date = $event_details->end_date;
                    $all->dates = "";
                    $all_dates = [];
                    $event_dates = DB::table('events')->where('user_id',$user->id)->where('event_id',$event_details->id)->get();
                    if(!empty($event_dates)){
                        foreach($event_dates as $data){
                            $all_dates[] = $data->date;
                        }
                    }


                    if(!empty($all_dates)){
                        $all->dates = implode(",",$all_dates);

                    }



                }
            }



            $dbArray['events'] = $all_event;


            $sub_list[] = $dbArray;  
        }
    }



    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',
        'events_details' =>$sub_list,


    ],200);

}




public function add_date_wise_events(Request $request){
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
    $sub_list = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }

    $dbArray = [];

    $dbArray['user_id'] = $user->id;
    $dbArray['sub_name'] = $request->sub_name;
    $dbArray['chapter_name'] = $request->chapter_name;
    $dbArray['preference'] = $request->preference;
    $dbArray['start_date'] = $request->start_date;
    $dbArray['end_date'] = $request->end_date;
    $dbArray['strategy'] = $request->strategy;
    $dbArray['pattern'] = $request->pattern ?? '';


    $event_id = DB::table('student_events')->insertGetId($dbArray);


    $this->update_event($event_id,$request->strategy,$request->pattern);



    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',


    ],200);

}



public function update_event($event_id,$strategy='',$pattern=''){

    $events = DB::table('student_events')->where('id',$event_id)->first();
    if(!empty($events)){

        $preference = $events->preference;
        $preference = explode(",", $preference);


        $start_date = $events->start_date;
        $end_date = $events->end_date;


        $diff = abs(strtotime($end_date) - strtotime($start_date));

        $years = floor($diff / (365*60*60*24));
        $months = floor(($diff - $years * 365*60*60*24) / (30*60*60*24));
        $days = floor(($diff - $years * 365*60*60*24 - $months*30*60*60*24)/ (60*60*24));
        $differencesStartEnd = $days;
        $checkArr = ['comb1','comb2','comb3'];

        $fourDays = 4;
        
        if (in_array("comb1", $preference)){
            $revisionDays = "1-7-15-30";
            if ($fourDays <= $differencesStartEnd) {
                $revisionDays = "1-1-7-1-15-1-30-1";
            }
        }

        if (in_array("comb2", $preference)){
            $revisionDays = "3-8-15";
            if ($fourDays <= $differencesStartEnd) {
                $revisionDays = "3-1-8-1-15-1";
            }
        }

        if (in_array("comb3", $preference)){
            $revisionDays = "2-6-10";
            if ($fourDays <= $differencesStartEnd) {
                $revisionDays = "2-1-6-1-10-1";
            }
        }


        if (in_array("comb1", $preference) && in_array("comb2", $preference)){
            $revisionDays = "1-7-18-25";
            if ($fourDays <= $differencesStartEnd) {
                $revisionDays = "1-1-7-1-18-1-25-1";
            }
        }
        if (in_array("comb2", $preference) && in_array("comb3", $preference)){
            $revisionDays = "3-10-17";
            if ($fourDays <= $differencesStartEnd) {
                $revisionDays = "3-1-10-1-17-1";
            }
        }




        if (in_array("comb1", $preference) && in_array("comb3", $preference)){
            $revisionDays = "2-8-16";
            if ($fourDays <= $differencesStartEnd) {
                $revisionDays = "2-1-8-1-16-1";
            }
        }




        if(in_array("comb1", $preference) && in_array("comb2", $preference) && in_array("comb3", $preference)){
            $revisionDays = "1-5-12-20";
            if ($fourDays <= $differencesStartEnd) {
                $revisionDays = "1-1-5-1-12-1-20-1";
            }
        }


        if($strategy == 'own'){
            $revisionDays = $pattern;
        }




      DB::table('student_events')->where('id',$event_id)->update(['pattern'=>$revisionDays]);


        $revisionDays = explode("-", $revisionDays);
        $i=0;
        $date = $events->end_date;

        foreach ($revisionDays as $key => $value) {
                    //echo $value;
            $insertArr = [];

                    // if($i == 0){
                    //     $insertArr['date'] = $date;
                    //     $insertArr['event_id'] = $events->id;
                    //     $insertArr['user_id'] = $events->user_id;
                    // }else{
            if($i == 0 && $value == 1){
             $value = $value - 1;

         }


         $date = date('Y-m-d', strtotime($date. ' + '.$value.' days'));

         $insertArr['date'] = $date;
         $insertArr['event_id'] = $events->id;
         $insertArr['user_id'] = $events->user_id;

                    // }

         //if($date <= $end_date){
            DB::table('events')->insert($insertArr);
       // }

        ++$i;
    }












}


}


public function get_student_events_date_wise(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'date' => 'required',
]);

   $user = null;
   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

$user = JWTAuth::parseToken()->authenticate();
$sub_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$event_dates = DB::table('events')->where('user_id',$user->id)->where('date',$request->date)->groupBy('date')->get();

// print_r($event_dates);
// die;
$eventArr = [];


if(!empty($event_dates)){
    foreach ($event_dates as $key) {
        $dbArray = [];

        $dbArray['date'] = $key->date;

        $all_event = DB::table('events')->where('user_id',$user->id)->whereDate('date',$key->date)->get();

        if(!empty($all_event)){
            foreach($all_event as $all){
                $event_details = DB::table('student_events')->where('user_id',$user->id)->where('id',$all->event_id)->first();

                if(empty($all->notes)){
                    $all->notes = '';
                }

                $all->sub_name = $event_details->sub_name;
                $all->chapter_name = $event_details->chapter_name;

                $date = date('Y-m-d');
                if($date == $all->date){
                    $all->status = (string)$all->status;

                }else{
                    $all->status = "0";

                }



                $all->strategy = $event_details->strategy;
                $all->start_date = $event_details->start_date;
                $all->end_date = $event_details->end_date;
                $all->dates = "";
                $all_dates = [];
                $event_dates = DB::table('events')->where('user_id',$user->id)->where('event_id',$event_details->id)->get();
                if(!empty($event_dates)){
                    foreach($event_dates as $data){
                        $all_dates[] = $data->date;
                    }
                }


                if(!empty($all_dates)){
                    $all->dates = implode(",",$all_dates);

                }



            }
        }



        $dbArray['events'] = $all_event;


        $sub_list[] = $dbArray;  
    }
}



return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'events_details' =>$sub_list,


],200);
}


public function dashboard(Request $request){
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
$dashboard = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

//DB::enableQueryLog(); // Enable query log

$events = DB::table('events')->where('user_id',$user->id)->where('date', '>=',date('Y-m-d', strtotime('-7 days'))
)->get();
//dd(DB::getQueryLog()); // Show results of log

$percentage = 0;
$per_event = 0;
if(count($events) > 0){
    $per_event = 100 / count($events);

}



$upcommingTask = [];
$recentTask = [];

if(!empty($events)){
    foreach($events as $event){

        $student_event = DB::table('student_events')->where('id',$event->event_id)->first();

        $event->sub_name = $student_event->sub_name;
        $event->chapter_name = $student_event->chapter_name;
        $event->strategy = $student_event->strategy;


        if($event->status == 1){
         $recentTask[] = $event; 

         $percentage+=$per_event;
     }
     if($event->status == 0 && $event->date > date('Y-m-d')){
         $upcommingTask[] = $event; 

     }



 }
}




$dashboard['percentage'] = round($percentage);

$dashboard['upcomming_task'] = $upcommingTask;
$dashboard['recent_task'] = $recentTask;


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'dashboard' =>$dashboard,


],200);

}

public function add_notes(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'event_id' => 'required',
    'notes' => 'required',
]);

 $user = null;
 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

$user = JWTAuth::parseToken()->authenticate();
$sub_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}


$notes = $request->notes;

$event_id = DB::table('events')->where('id',$request->event_id)->update(['notes'=>$notes]);

return response()->json([

    'result' => 'success',
    'message' => ' Successfully',


],200); 
}

public function add_reminders(Request $request){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'event_id' => 'required',
    'type' => '',
]);

 $user = null;
 if ($validator->fails()) {
    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

$user = JWTAuth::parseToken()->authenticate();
$sub_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$remainder = [];

if($request->type == 'add'){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'event_id' => 'required',
        'date' => 'required',
        'time' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);
    }

    $dbArray = [];
    $dbArray['user_id'] = $user->id;
    $dbArray['event_id'] = $request->event_id;
    $dbArray['date'] = $request->date;
    $dbArray['time'] = $request->time;
    $dbArray['status'] = 0;

    DB::table('event_remainders')->insert($dbArray);



}
elseif($request->type == 'delete'){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'remainder_id' => 'required',
    ]);
    if ($validator->fails()) {
        return response()->json([
            'result' => 'failure',
            'message' => json_encode($validator->errors()),

        ],400);



    }

    DB::table('event_remainders')->where('id',$request->remainder_id)->where('user_id',$user->id)->delete();


}

$remainder = DB::table('event_remainders')->where('event_id',$request->event_id)->get();
if(!empty($remainder)){
    foreach($remainder as $rem){
        $rem->date = date('d M',strtotime($rem->date));
        $rem->time = date('h:i A',strtotime($rem->time));
    }
}


return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'reminders'=>$remainder,

],200); 
}




public function update_events(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'event_id' => 'required',
        'type' => 'required',
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


    if($request->type == 'delete'){
        $events = DB::table('events')->where('id',$request->event_id)->first();
        

        // echo $events->event_id;
        // echo "string";
        // echo $user->id;

        DB::table('student_events')->where('id',$events->event_id)->where('user_id',$user->id)->delete();
        DB::table('events')->where('event_id',$events->event_id)->where('user_id',$user->id)->delete();


    }
    if($request->type == 'update'){
        DB::table('events')->where('id',$request->event_id)->update(['status'=>1]);
    }



    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',

    ],200); 
}


public function my_notes(Request $request){
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
    $sub_list = [];
    if (empty($user)){
        return response()->json([
            'result' => 'failure',
            'message' => '',
        ],401);

    }



    $event_dates = DB::table('events')->where('user_id',$user->id)->where('notes','!=','')->groupBy('date')->get();
    if(!empty($event_dates)){
        foreach ($event_dates as $key) {
            $dbArray = [];

            $dbArray['date'] = $key->date;

            $all_event = DB::table('events')->where('user_id',$user->id)->where('notes','!=','')->whereDate('date',$key->date)->get();

            if(!empty($all_event)){
                foreach($all_event as $all){
                    $event_details = DB::table('student_events')->where('user_id',$user->id)->where('id',$all->event_id)->first();

                    $all->sub_name = $event_details->sub_name;
                    $all->chapter_name = $event_details->chapter_name;
                    $all->status = $event_details->status;
                    $all->strategy = $event_details->strategy;
                    $all->start_date = $event_details->start_date;
                    $all->end_date = $event_details->end_date;
                    $all->dates = "";
                    $all_dates = [];
                    $event_dates = DB::table('events')->where('notes','!=','')->where('user_id',$user->id)->where('event_id',$event_details->id)->get();
                    if(!empty($event_dates)){
                        foreach($event_dates as $data){
                            $all_dates[] = $data->date;
                        }
                    }


                    if(!empty($all_dates)){
                        $all->dates = implode(",",$all_dates);

                    }



                }
            }



            $dbArray['events'] = $all_event;


            $sub_list[] = $dbArray;  
        }
    }



    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',
        'events_details' =>$sub_list,


    ],200);

}


public function daily_goals(Request $request){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => '',
]);

   $user = null;
   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

$user = JWTAuth::parseToken()->authenticate();
$daily_goals = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$dbArray = [];


if($request->type == 'add'){
   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => '',
    'task_name' => 'required',
    'time' => 'required',
]);
   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}



$dbArray['user_id'] = $user->id;
$dbArray['task_name'] = $request->task_name;
$dbArray['time'] = $request->time;

DB::table('daily_goals')->insert($dbArray);

}
if($request->type == 'update'){
 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => '',
    'task_name' => 'required',
    'goal_id' => 'required',
]);
 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

// $dbArray['user_id'] = $user->id;
$dbArray['task_name'] = $request->task_name;
$dbArray['time'] = $request->time;

DB::table('daily_goals')->where('id',$request->goal_id)->update($dbArray);



}
if($request->type == 'delete'){


 $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => '',
    'goal_id' => 'required',
]);
 if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

DB::table('daily_goals')->where('id',$request->goal_id)->delete();

}


$daily_goals = DB::table('daily_goals')->where('user_id',$user->id)->get();
if(!empty($daily_goals)){
    foreach($daily_goals as $daily){
        $daily->time = date('h:i A',strtotime($daily->time));
    }

}

return response()->json([

    'result' => 'success',
    'message' => ' Successfully',
    'daily_goals' =>$daily_goals,


],200);

}


public function send_notification($title, $body, $deviceToken){
    $sendData = array(
        'body' => !empty($body) ? $body : '',
        'title' => !empty($title) ? $title : '',
        'sound' => 'Default'
    );


    // print_r($sendData);
    // die;

    $result =  $this->fcmNotification($deviceToken,$sendData);

    print_r($result);
    die;


}

public function fcmNotification($device_id, $sendData)
{
        #API access key from Google API's Console
    if (!defined('API_ACCESS_KEY')){
        define('API_ACCESS_KEY', 'AAAAP7qjih4:APA91bF4HF9dBjykpOL-dSi7CCgDRHS59QR65UP-5tmF-gtUDuDDBVlRZwoG_1tPSELqzOkIt5cf24fPyEj6UKZPKdsLqA1cR6OokKNCWTWymJfj4P0ebOgTcGqAazOE50Ku3KKLAVVJ');
    }

    $fields = array
    (
        'to'    => $device_id,
        'data'  => $sendData,
        'notification'  => $sendData,
       // "click_action"=> "FLUTTER_NOTIFICATION_CLICK",
    );

    $headers = array
    (
        'Authorization: key=' . API_ACCESS_KEY,
        'Content-Type: application/json'
    );
        #Send Reponse To FireBase Server
    $ch = curl_init();
    curl_setopt( $ch,CURLOPT_URL, 'https://fcm.googleapis.com/fcm/send' );
    curl_setopt( $ch,CURLOPT_POST, true );
    curl_setopt( $ch,CURLOPT_HTTPHEADER, $headers );
    curl_setopt( $ch,CURLOPT_RETURNTRANSFER, true );
    curl_setopt( $ch,CURLOPT_SSL_VERIFYPEER, false );
    curl_setopt( $ch,CURLOPT_POSTFIELDS, json_encode( $fields ) );
    $result = curl_exec($ch);
    if($result === false){
        die('Curl failed ' . curl_error($ch));
    }

    curl_close($ch);

    return $result;
}




public function send_notification_all(Request $request){


 $title = 'Test';
 $text = 'Description';
 $body = $text;
    //$deviceToken = $login->deviceToken;

$devices = UserLogin::where('user_id','287807')->first();


 // $deviceToken = 'dyUWqRAFQqKv6mZCfiy_FC:APA91bFO__SoCrhJbKmNSfodok4svDCR4GLCaOPO7fh5lPUtaYO6VlXBsAGtH_9KcHZ-hJ1L3NgwWSCSZnH42xwNYs7N1SX08hegLk_r_8Vu4AbWYcS7iIpqBubAxypKK4j83SDDVoE0';
 $deviceToken = $devices->deviceToken;

 // echo $deviceToken;
    //$type = 'incomming_request';

 $success = $this->send_notification($title, $body, $deviceToken);
 if($success){
    //     $dbArray = [];
    //     $dbArray['user_id'] = $login->user_id;
    //     $dbArray['text'] = $title??'';
    //     $dbArray['title'] = $title ?? '';

    //     DB::table('notifications')->insert($dbArray);


    return response()->json([

        'result' => 'success',
        'message' => ' Successfully',


    ],200);

}else{
    return response()->json([

        'result' => 'false',
        'message' => 'Notification Not Working',


    ],400);
}





}




public function show_revision_data(Request $request){

   $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'start_date' => 'required',
    'end_date' => 'required',
    'pattern' => 'required',
]);

   $user = null;
   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

$user = JWTAuth::parseToken()->authenticate();
$pattern_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}
$dbArray = [];
$pattern = $request->pattern;
$pattern = explode("-", $pattern);
// print_r($pattern);
$date = $request->start_date;
if(!empty($pattern)){
  $i=0;
  foreach($pattern as $key => $value){

    if($i == 0 && $value == 1){
     $value = $value - 1;
 }
 $date = date('Y-m-d', strtotime($date. ' + '.$value.' days'));
 $dbArray[] = $date;
 ++$i;
}
}




return response()->json([
    'result' => 'success',
    'message' => 'Successfully',
    'pattern_list' => $dbArray,
],200);
}

public function get_pattern(Request $request){
     $validator =  Validator::make($request->all(), [
    'token' => 'required',
    'type' => '',
]);

   $user = null;
   if ($validator->fails()) {

    return response()->json([
        'result' => 'failure',
        'message' => json_encode($validator->errors()),

    ],400);
}

$user = JWTAuth::parseToken()->authenticate();
$pattern_list = [];
if (empty($user)){
    return response()->json([
        'result' => 'failure',
        'message' => '',
    ],401);

}

$patternArr = config('custom.patternArr');
if(!empty($patternArr)){
    foreach ($patternArr as $key => $value) {
        


       $pattern_list[] = $value; 
    }
}


if($request->type == 'add'){
    $dbArray = [];
    $dbArray['user_id'] = $user->id;
    $dbArray['pattern'] = $request->pattern;
    DB::table('user_pattern')->insert($dbArray);
}

$paterns = DB::table('user_pattern')->where('user_id',$user->id)->get();
if(!empty($paterns)){
    foreach($paterns as $pat){
        $pattern_list[] = $pat->pattern;
    }
}




return response()->json([
    'result' => 'success',
    'message' => 'Successfully',
    'pattern_list' => $pattern_list,
],200);
}




public function reminder_notification(Request $request){
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
        $reminder_notification_list = [];
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
            ],401);
        }

        $notifications = DB::table('event_remainders')->where('user_id',$user->id)->get();
        if(!empty($notifications)){
            foreach ($notifications as $notification){
                $notification->ori_time = $notification->time;
                $notification->time = date('h:i A',strtotime($notification->time));
                $event_det = DB::table('student_events')->where('id',$notification->event_id)->first();
                    $notification->sub_name = $event_det->sub_name ?? '';
                    $notification->chapter_name = $event_det->chapter_name ?? '';

            }
        }


        return response()->json([
            'result' => 'success',
            'message' => 'Successfully',
            'reminder_notification_list' => $notifications,
        ],200);
    }

    public function daily_goal_notification(Request $request){
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
        $reminder_notification_list = [];
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
            ],401);
        }

        $notifications = DB::table('daily_goals')->where('user_id',$user->id)->get();
        if(!empty($notifications)){
            foreach ($notifications as $notification){
                $notification->ori_time = $notification->time;
                $notification->time = date('h:i A',strtotime($notification->time));

            }
        }


        return response()->json([
            'result' => 'success',
            'message' => 'Successfully',
            'daily_goal_notification_list' => $notifications,
        ],200);
    }
    
    
    
    public function tracking_chart(Request $request){
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'status'=>'',
            'sort_by'=>'',
            'subject'=>'',

        ]);

        $user = null;
        if ($validator->fails()) {

            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),

            ],400);
        }

        $user = JWTAuth::parseToken()->authenticate();
        $tracking_chart = [];
        $details = [];
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
            ],401);
        }
//echo $user->id;
        $student_events = DB::table('student_events')->select('id','sub_name','user_id','chapter_name','pattern','start_date','end_date')->where('user_id',$user->id);
        if($request->subject !=''){
            $student_events->where('sub_name', 'like', '%' . $request->subject . '%');
        }

        if(!empty($request->sort_by) && $request->sort_by == 'date'){
            $student_events->orderBy('start_date');
        }

        if(!empty($request->sort_by) && $request->sort_by == 'subject'){
            $student_events->orderBy('sub_name');
        }

        $student_events = $student_events->get();
        if(!empty($student_events)){
            foreach ($student_events as $stud_ev){
                $status = '';
                $dates = [];
                    $events = DB::table('events')->where('event_id',$stud_ev->id);

                    $events = $events->get();

                    if(!empty($events)){
                        foreach ($events as $event){
                            $dates[] =$event->date;
                        }

                        $dates = implode(",",$dates);
                        $details['dates'] = $dates;
                    }


                $details['start_date'] = $stud_ev->start_date;
                $details['end_date'] = $stud_ev->end_date;



                if($stud_ev->start_date > date('Y-m-d')){
                    $status = 'Upcoming';
                }
                if($stud_ev->end_date > date('Y-m-d') && $stud_ev->end_date < date('Y-m-d')){
                    $status = 'Ongoing';
                }

                $stud_ev->status = $status;



                $stud_ev->details = $details;

                $tracking_chart[] =  $stud_ev;

            }
        }






        return response()->json([
            'result' => 'success',
            'message' => 'Successfully',
            'tracking_chart' => $tracking_chart,
        ],200);


    }

}
