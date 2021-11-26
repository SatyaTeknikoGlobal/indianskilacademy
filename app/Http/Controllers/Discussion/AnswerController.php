<?php

namespace App\Http\Controllers\Discussion;

use App\DiscussionAnswer;
use App\DiscussionTopic;
use App\DiscussionVotes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class AnswerController extends Controller
{
    //
    public function index(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'question_id'=>'required',
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
        $question_id = $request->question_id;
        $question = DiscussionTopic::where([
            "id"=>$question_id,
            "status"=>'Y'
        ])->with('user')->first();
        if (empty($question)){
            return response()->json([
                'result' => 'failure',
                'message' => 'Something Went Wrong',
                'content' =>$content
            ],400);
        }
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

        $skip = $per_page_item * ($page_no - 1);
        $total_item_count = DiscussionAnswer::where([
            "topic_id"=>$question_id,
            "status"=>'Y'
        ])->count();
        $answers = DiscussionAnswer::where([
            "topic_id"=>$question_id,
            "status"=>'Y'
        ])
            ->orderBy('votes','desc')
            ->skip($skip)
            ->take($per_page_item)->with('user')
            ->get();
        foreach ($answers as $answer){
            $added_time = strtotime($answer->created_at);
            $time_diff = time() - $added_time;
            if ($time_diff > 72 * 3600){
                $time_string = date("d M Y",strtotime($answer->created_at));
            }else{
                $time_string = $this->formatHHMMSS($time_diff);
            }
            $answer->vote_status = 'N';
            $userVote = DiscussionVotes::where(['answer_id'=>$answer->id,'user_id'=>$user->id])->first();
            if (!empty($userVote)){
                $answer->vote_status = $userVote->vote_status;
            }
            $answer->time_string = $time_string;
            $answer->file = $this->get_image_url($answer->file);
        }
        $content = array(
            "question"=>$question,
            "answers"=>$answers,
            "total_count"=>$total_item_count,
            "page_number"=>$page_no
        );
        return response()->json([
            'result' => 'success',
            'message' => '',
            'content' =>$content
        ],200);
    }

    public function addAnswer(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'question_id'=>'required',
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
        $question_id = $request->question_id;
        $question = DiscussionTopic::where([
            "id"=>$question_id,
            "status"=>'Y'
        ])->first();
        if (empty($question)){
            return response()->json([
                'result' => 'failure',
                'message' => 'Something Went Wrong',
            ],400);
        }
        $text =$request->text;
        $add_answer = new DiscussionAnswer();
        $add_answer->topic_id = $question_id;
        $add_answer->text = $text;
        $add_answer->user_id = $user->id;
        if ($request->hasFile('image')){
            $destinationPath = public_path("/uploads/discussion_forum");
            $side = $request->file('image');
            $side_name = $question_id.'_answer'.time().'.'.$side->getClientOriginalExtension();
            $side->move($destinationPath, $side_name);
            $add_answer->file = $side_name;
            $add_answer->file_type = 'image';
        }
        $add_answer->save();
        $question->answers += 1;
        $question->save();
        return response()->json([
            'result' => 'success',
            'message' => 'Answer Submitted Successfully',
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
