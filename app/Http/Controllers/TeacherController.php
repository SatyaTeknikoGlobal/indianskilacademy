<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use JWTAuth;
use Validator;
use Illuminate\Support\Facades\Hash;
use Tymon\JWTAuth\Exceptions\JWTException;
use App\Faculties;
use App\TeacherLogin;
use App\LiveClass;
use App\Banner;
use App\Board;
use App\Instructions;
use App\Subject;
use App\Exams;

use App\Corner;


class TeacherController extends Controller
{
    
    private function getAdminImageUrl($url,$image){

        return "https://iasgyan.org/public/images/".$url.'/'.$image;

    }

    private function getStorageImageUrl($image){
         return "https://iasgyan.org/images/".$image;
    }


     private function getGalleryimage($image)
    {
          return "https://iasgyan.org/public/images/gallery/".$image;
    }


    public function home(Request $request)
    {
       
         $validator =  Validator::make($request->all(), [
            'token' => 'required',
        ]);

        $content = array();
        if ($validator->fails()) {
            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content'=>$content,

            ],200);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => 'Session Timed Out',
                'content'=>$content,

            ],200);
        }
        $banners = Banner::where(['status'=>'Y'])->get();
        foreach ($banners as $row) {
           $row->banner = $this->getStorageImageUrl($row->banner);
        }

        $corner = Corner::latest()->take(10)->get();

        foreach ($corner as $row) {

            $row->image = $this->getGalleryimage($row->image);

        }
         $content = [

            "banners"=>$banners,

            "corner"=>$corner,
        ];
        return response()->json([
                'result' => 'success',
                'message' => '',
                'content'=>$content,
            ],200);
    }

    public function teacherLogin(Request $request)
    {
       $validator = Validator::make($request->all(), [
            'email' => 'required',
            'password' => 'required',
        ]);
       $user = null;
        if ($validator->fails()) {
            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'user' =>$user,
                'token' =>'',
            ],200);
        }
        $email = request('email');
        $password = request('password');
        $deviceID = $request->input("deviceID");
        $deviceToken = $request->input("deviceToken");
        $deviceType = $request->input("deviceType");
        $check  = Faculties::where(['email'=>$email])->first();
        if (empty($check)){
            return response()->json([
                'result' => 'failure',
                'message' => 'Invalid Credentials',
                'user' =>$user,
                'token' =>'',

            ],200);
        }
        $device_info = TeacherLogin::where(['user_id'=>$check->id,"deviceID"=>$deviceID])->first();
        if(empty($device_info))
        {
            TeacherLogin::create([
                "user_id"=>$check->id,
                "ip_address"=>$request->ip(),
                "deviceID"=>$deviceID,
                "deviceToken"=>$deviceToken,
                "deviceType"=>$deviceType,
            ]);
        } else {
            $device_info->deviceToken = $deviceToken;
            $device_info->deviceType = $deviceType;
            $device_info->save();
        }
       if (Hash::check($password, $check->password)) {
             $token = JWTAuth::fromUser($check);
             $check->image = $this->getAdminImageUrl('faculties',$check->image);
             unset($check->id);
            return response()->json([
            'result' => 'success',
             'message' => 'Login success',
             'token'=>$token,
             'user' =>$check,
            

        ],200);
       
       }
       else{
         return response()->json([

                'result' => 'failure',

                'message' => 'InValid Password, please try again',

                'user' =>$user,
                'token' =>'',

            ],200);
       }
    }

    public function listLiveClassTeacher(Request $request)
    {
       
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
        ]);

        $content = array();
        if ($validator->fails()) {
            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content'=>$content,

            ],200);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => 'Session Timed Out',
                'content'=>$content,

            ],200);
        }
        $content = LiveClass::where(['faculties_id'=>$user->id])->get();
        foreach ($content as $row) {
           $row->image = $this->getAdminImageUrl('liveclasses',$row->image);
        }
        return response()->json([
                'result' => 'success',
                'message' => '',
                'content'=>$content,

            ],200);
     }


     public function addLiveClass(Request $request)
     {
         $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'title'=>'required',
            'channel_id'=>'',
            'passcode'=>'',
            'description'=>'',
            'image'=>'',
            'start_date'=>'',
            'live_type'=>'',
            'start_time'=>'required',
            'end_time'=>'required',
            'status'=>'required',
         ]);

            $content = array();
            if ($validator->fails()) {
                return response()->json([
                    'result' => 'failure',
                    'message' => json_encode($validator->errors()),
                    'content'=>$content,

                ],200);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if (empty($user)){
                return response()->json([
                    'result' => 'failure',
                    'message' => 'Session Timed Out',
                    'content'=>$content,

                ],200);
            }

            $image= '';
            if($request->hasFile('image')){

                $destinationPath = public_path("../../admin.agricoaching.in/public/images/liveclasses/");

                $side = $request->file('image');

                $side_name = $user->id.'_live_class_images'.time().'.'.$side->getClientOriginalExtension();

                $side->move($destinationPath, $side_name);

               $image = $side_name;

            }
            LiveClass::create([
                'title'=>request('title'),
                'faculties_id'=>$user->id,
                'channel_id'=>request('channel_id'),
                'passcode'=>request('passcode'),
                'description'=>request('description'),
                'image'=>$image,
                'start_date'=>request('start_date'),
                'start_time'=>request('start_time'),
                'end_time'=>request('end_time'),
                'end_status'=>'N',
                'live_type'=>request('live_type'),
                'status'=>request('status')
            ]);
            return response()->json([
                    'result' => 'success',
                    'message' => 'LiveClass Created Successfully.',
                    'content'=>$content,

                ],200);
     }

     public function getCourse(Request $request)
     {
        $content = Board::where(['status'=>'Y'])->get();
        return response()->json([
                'result' => 'success',
                'message' => '',
                'content'=>$content,

            ],200);

     }

     public function getSubject(Request $request)
     {
         $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'course_id'=>'required'
        ]);

        $content = array();
        if ($validator->fails()) {
            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content'=>$content,

            ],200);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => 'Session Timed Out',
                'content'=>$content,

            ],200);
        }
        $course_id = request('course_id');
       $content =  Subject::where(['board_id'=>$course_id])->get();
       return response()->json([
                'result' => 'success',
                'message' => '',
                'content'=>$content,
            ],200);
     }

     public function getInstraction(Request $request)
     {
         $content = Instructions::get();
        return response()->json([
                'result' => 'success',
                'message' => '',
                'content'=>$content,
            ],200);

     }


     public function addExam(Request $request)
     {
          $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'title'=>'required',
            'instruction'=>'required',
            'image'=>'required',
            'start_date'=>'required',
            'end_date'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'session_time'=>'required',
            'course_id'=>'required',
            'sub_id'=>'required',
            'status'=>'required',
            'is_paid'=>'required',  

        ]);

        $content = array();
        if ($validator->fails()) {
            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'content'=>$content,

            ],200);
        }    
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => 'Session Timed Out',
                'content'=>$content,

            ],200);
        }

          $image= '';
            if($request->hasFile('image')){

                $destinationPath = public_path("../../admin.agricoaching.in/public/images/exam/");

                $side = $request->file('image');

                $side_name = $user->id.'_exam_image'.time().'.'.$side->getClientOriginalExtension();

                $side->move($destinationPath, $side_name);

               $image = $side_name;

            }
            $subject_id = $request->input('sub_id');
            Exams::create([
            'board_id'=>request('course_id'),
            'sub_id'=>$subject_id,
            'type'=>1,
            'title'=>request('title'),
            'session_time'=>request('session_time'),
            'instruction'=>request('instruction'),
            'start_date'=>request('start_date'),
            'end_date'=>request('end_date'),
            'image'=>$image,
            'start_time'=>request('start_time'),
            'end_time'=>request('end_time'),
            'is_paid'=>request('is_paid'),
            'create_by'=>'Teacher',
            'create_id'=>$user->id,
            'status'=>'Active',
            'language'=>null,
        ]);

        return response()->json([
                'result' => 'success',
                'message' => 'Exam Created Successfully.',
                'content'=>$content,
            ],200);

     }

     public function teacherExamList(Request $request)
     {
             $validator =  Validator::make($request->all(), [
                'token' => 'required',
            ]);

            $content = array();
            if ($validator->fails()) {
                return response()->json([
                    'result' => 'failure',
                    'message' => json_encode($validator->errors()),
                    'content'=>$content,

                ],200);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if (empty($user)){
                return response()->json([
                    'result' => 'failure',
                    'message' => 'Session Timed Out',
                    'content'=>$content,

                ],200);
            }

        $content = Exams::where(['create_by'=>'Teacher','create_id'=>$user->id])->get();
        foreach ($content as  $row) {
           $row->image = $this->getAdminImageUrl('exam', $row->image);
        }
         return response()->json([
                'result' => 'success',
                'message' => '',
                'content'=>$content,
            ],200);
     }


     public function updateExam(Request $request)
     {
         $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'title'=>'required',
            'instruction'=>'required',
            'image'=>'',
            'start_date'=>'required',
            'end_date'=>'required',
            'start_time'=>'required',
            'end_time'=>'required',
            'session_time'=>'required',
            'course_id'=>'required',
            'sub_id'=>'required',
            'status'=>'required',
            'is_paid'=>'required', 
            'exam_id' =>'required',
            ]);

            $content = array();
            if ($validator->fails()) {
                return response()->json([
                    'result' => 'failure',
                    'message' => json_encode($validator->errors()),
                    'content'=>$content,

                ],200);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if (empty($user)){
                return response()->json([
                    'result' => 'failure',
                    'message' => 'Session Timed Out',
                    'content'=>$content,

                ],200);
            }
            $data = array();
             $subject_id = $request->input('sub_id');
            if($request->hasFile('image')){

                $destinationPath = public_path("../../admin.agricoaching.in/public/images/exam/");

                $side = $request->file('image');

                $side_name = $user->id.'_exam_image'.time().'.'.$side->getClientOriginalExtension();

                $side->move($destinationPath, $side_name);

               $image = $side_name;

               $data = array(
                'board_id'=>request('course_id'),
                'sub_id'=>$subject_id,
                'type'=>1,
                'title'=>request('title'),
                'session_time'=>request('session_time'),
                'instruction'=>request('instruction'),
                'start_date'=>request('start_date'),
                'end_date'=>request('end_date'),
                'image'=>$image,
                'start_time'=>request('start_time'),
                'end_time'=>request('end_time'),
                'is_paid'=>request('is_paid'),
                'create_by'=>'Teacher',
                'create_id'=>$user->id,
                'status'=>'Active',
            );
            }
           else{
             $data = array(
                'board_id'=>request('course_id'),
                'sub_id'=>$subject_id,
                'type'=>1,
                'title'=>request('title'),
                'session_time'=>request('session_time'),
                'instruction'=>request('instruction'),
                'start_date'=>request('start_date'),
                'end_date'=>request('end_date'),
                'start_time'=>request('start_time'),
                'end_time'=>request('end_time'),
                'is_paid'=>request('is_paid'),
                'create_by'=>'Teacher',
                'create_id'=>$user->id,
                'status'=>'Active',
            );

           }
           Exams::where(['id'=>$request->exam_id])->update($data);
            return response()->json([
                    'result' => 'success',
                    'message' => 'Exam Updated Successfully.',
                    'content'=>$content,
                ],200);
     }


     public function teacherProfile(Request $request)
     {
           $validator =  Validator::make($request->all(), [
                'token' => 'required',
            ]);

            $content = array();
            if ($validator->fails()) {
                return response()->json([
                    'result' => 'failure',
                    'message' => json_encode($validator->errors()),
                    'content'=>$content,

                ],200);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if (empty($user)){
                return response()->json([
                    'result' => 'failure',
                    'message' => 'Session Timed Out',
                    'content'=>$content,

                ],200);
            }
          $content = Faculties::select(['name','email','dob','total_exp','phone','image','education','speciality','college_name','college_location'])->where(['id'=>$user->id])->first();
          $content->image = $this->getAdminImageUrl('faculties', $content->image);
         return response()->json([
                'result' => 'success',
                'message' => '',
                'token'=>request('token'),
                'user'=>$content,
            ],200);
     }


     public function updateTeacherProfile(Request $request)
     {
        $validator =  Validator::make($request->all(), [
                'token' => 'required',
                'name'=>'required',
                'email'=>'required',
                'dob'=>'required',
                'total_exp'=>'required',
                'phone'=>'required',
                'image'=>'',
                'education'=>'required',
                'speciality'=>'required',
                'college_name'=>'required',
                'college_location',
        ]);
        $content = array();
            if ($validator->fails()) {
                return response()->json([
                    'result' => 'failure',
                    'message' => json_encode($validator->errors()),
                    'content'=>$content,

                ],200);
            }
            $user = JWTAuth::parseToken()->authenticate();
            if (empty($user)){
                return response()->json([
                    'result' => 'failure',
                    'message' => 'Session Timed Out',
                    'content'=>$content,

                ],200);
            }
            $data = array();

            if($request->hasFile('image')){

                $destinationPath = public_path("../../admin.agricoaching.in/public/images/faculties/");

                $side = $request->file('image');

                $side_name = $user->id.'_faculties_image'.time().'.'.$side->getClientOriginalExtension();

                $side->move($destinationPath, $side_name);

               $image = $side_name;

               $data = array(
                'name'=>request('name'),
                'email'=>request('email'),
                'phone'=>request('phone'),
                'dob'=>request('dob'),
                'total_exp'=>request('total_exp'),
                'image'=>$image,
                'education'=>request('education'),
                'speciality'=>request('speciality'),
                'college_name'=>request('college_name'),
                'college_location'=>request('college_name'),
            );
            }

            else{

               $data = array(
                'name'=>request('name'),
                'email'=>request('email'),
                'phone'=>request('phone'),
                'dob'=>request('dob'),
                'total_exp'=>request('total_exp'),
                'education'=>request('education'),
                'speciality'=>request('speciality'),
                'college_name'=>request('college_name'),
                'college_location'=>request('college_name'),
            );
            }
            Faculties::where(['id'=>$user->id])->update($data);
             return response()->json([
                    'result' => 'success',
                    'message' => 'Profile Updated',
                    'content'=>$content,
                ],200);

     }


}
