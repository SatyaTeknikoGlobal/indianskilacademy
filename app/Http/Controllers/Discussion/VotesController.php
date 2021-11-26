<?php

namespace App\Http\Controllers\Discussion;

use App\DiscussionAnswer;
use App\DiscussionVotes;
use App\Http\Controllers\Controller;
use Illuminate\Http\Request;
use JWTAuth;
use Validator;

class VotesController extends Controller
{
    //
    public function index(Request $request)
    {
        $validator =  Validator::make($request->all(), [
            'token' => 'required',
            'answer_id'=>'required',
            'question_id'=>'required',
            'type'=>'in:U,D',
        ]);
        $type = 'D';
        if ($validator->fails()) {
            return response()->json([
                'result' => 'failure',
                'message' => json_encode($validator->errors()),
                'type'=>$type
            ],400);
        }
        $user = JWTAuth::parseToken()->authenticate();
        if (empty($user)){
            return response()->json([
                'result' => 'failure',
                'message' => '',
                'type'=>$type
            ],401);
        }
        $type = $request->type;
        $question_id = $request->question_id;
        $answer_id = $request->answer_id;
        $check = DiscussionVotes::where(['answer_id'=>$answer_id,'user_id'=>$user->id])->first();
        $vote_inc = 0;
        if (!empty($check)){
            if ($check->vote != $type){
                $check->vote = $type;
                $check->topic_id = $question_id;
                $check->save();
                if ($type == 'U'){
                    $vote_inc = 2;
                }else{
                    $vote_inc = -2;
                }
            }
        }else{
            DiscussionVotes::create([
                'answer_id'=>$answer_id,
                'user_id'=>$user->id,
                'topic_id'=>$question_id,
                'vote'=>$type
            ]);
            if ($type == 'U'){
                $vote_inc = 1;
            }else{
                $vote_inc = -1;
            }
        }
        $answer = DiscussionAnswer::find($answer_id);
        $answer->votes += $vote_inc;
        $answer->save();
        return response()->json([
            'result' => 'success',
            'message' => 'Successfully Submitted',
            'type'=>$type
        ],200);

    }

}
