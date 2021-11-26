<?php

namespace App\Http\Controllers\Discussion;

use App\DiscussionTopic;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class TopicController extends Controller
{
    //
    public function index(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'page'=>'required'
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
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'content' =>$content
            ],401);
        }
        $per_page_item = 10;
        $page_no = $request->page;
        $skip = $per_page_item * ($page_no - 1);
        $total_item_count = DiscussionTopic::where([
            "status"=>'Y'
        ])->count();
        $questions = DiscussionTopic::where([
            "status"=>'Y'
        ])
            ->latest()
            ->skip($skip)
            ->take($per_page_item)->with('user')
            ->get();
        foreach ($questions as $question)
        {
            $added_time = strtotime($question->created_at);
            $time_diff = time() - $added_time;
            if ($time_diff > 72 * 3600){
                $time_string = date("d M Y",strtotime($question->created_at));
            }else{
                $time_string = $this->formatHHMMSS($time_diff);
            }
            $question->time_string = $time_string;
            $question->file = $this->get_image_url($question->file);
            $question->link = "";
        }
        $content = array(
            "questions"=>$questions,
            "total_count"=>$total_item_count,
            "page_number"=>$page_no
        );
        return response()->json([
            'result' => 'success',
            'message' => '',
            'content' =>$content
        ],200);
    }

    public function myDoubts(Request $request){
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'page'=>'required'
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
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'content' =>$content
            ],401);
        }
        $per_page_item = 10;
        $page_no = $request->page;
        $skip = $per_page_item * ($page_no - 1);
        $total_item_count = DiscussionTopic::where([
            "user_id"=>$user->id,
            "status"=>'Y'
        ])->count();
        $questions = DiscussionTopic::where([
            "user_id"=>$user->id,
            "status"=>'Y'
        ])
            ->latest()
            ->skip($skip)
            ->take($per_page_item)->with('user')
            ->get();
        foreach ($questions as $question)
        {
            $added_time = strtotime($question->created_at);
            $time_diff = time() - $added_time;
            if ($time_diff > 72 * 3600){
                $time_string = date("d M Y",strtotime($question->created_at));
            }else{
                $time_string = $this->formatHHMMSS($time_diff);
            }
            $question->time_string = $time_string;
            $question->file = $this->get_image_url($question->file);
            $question->link = "";
        }
        $content = array(
            "questions"=>$questions,
            "total_count"=>$total_item_count,
            "page_number"=>$page_no,
            'user'=>$user
        );
        return response()->json([
            'result' => 'success',
            'message' => '',
            'content' =>$content,
        ],200);
    }

    public function addDoubts(Request $request){
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
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
            ],401);
        }
        $text =$request->text;
        $add_doubt = new DiscussionTopic();
        $add_doubt->text = $text;
        $add_doubt->user_id = $user->id;
        if ($request->hasFile('image')){
            $destinationPath = public_path("/uploads/discussion_forum");
            $side = $request->file('image');
            $side_name = 'doubt'.time().'.'.$side->getClientOriginalExtension();
            $side->move($destinationPath, $side_name);
            $add_doubt->file = $side_name;
            $add_doubt->file_type = 'image';
        }
        $add_doubt->save();
        return response()->json([
            'result' => 'success',
            'message' => 'Doubt Submitted Successfully',
        ],200);
    }

    private function formatHHMMSS($seconds) {
        $days = floor($seconds / (3600 * 24));
        $seconds = ($seconds % (3600 * 24));
        $hours = floor($seconds / 3600);
        $seconds = ($seconds % 3600);
        $minutes = floor($seconds / 60);
        $seconds = ($seconds % 60);
        if ($days <= 0) {
            if ($hours == 0) {
                if ($minutes == 0) {
                    if($seconds <= 0)
                    {
                        return "0 s";
                    }
                    return "$seconds s";
                }
                return "$minutes m";
            }
            return "$hours h $minutes m";
        }
        return "$days days";
    }

    private function get_image_url($image){
        return url("uploads/discussion_forum/$image");
    }
}
