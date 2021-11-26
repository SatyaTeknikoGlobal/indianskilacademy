<?php



namespace App\Http\Controllers;



use App\Course;

use App\ExamQuestion;
use App\Resume;
use Illuminate\Support\Facades\DB;

use App\Subject;

use App\SubscriptionHistory;

use App\Topic;

use App\UserLogin;

use JWTAuth;

use App\User;

use App\Chapter;

use App\Content;

use App\Board;

use App\State;

use App\Storage;

use App\Classes;

use App\Slides;

use Illuminate\Http\Request;

use Illuminate\Pagination\Paginator;

use Illuminate\Support\Collection;

use Illuminate\Pagination\LengthAwarePaginator;

use Tymon\JWTAuth\Exceptions\JWTException;

use Validator;

use Illuminate\support\str;

use App\Options;

use App\Solutions;

use App\CoursesSubject;

use App\Question;

use App\Result;

use App\Exams;

use App\Instructions;

use App\Notes;

use App\MainExam;

use App\CustomFilter;

use App\ReportList;

use App\ReportQuestion;





class ExamController extends Controller

{

    private function getSubjectImage($image){

        return "http://iasgyan.org/admin/public/images/subject/".$image;

    }
    private function getDocUrl($doc){

//        return asset("uploads/documents/$doc");

        return "http://iasgyan.org/admin/public/content/video/".$doc;

    }



    private function getVideoUrl($video){

        return "http://iasgyan.org/admin/public/content/video/".$video;

    }

    private function getExamImageUrl($image){

        return "http://iasgyan.org/admin/public/images/exam/".$image;
    }

    private function getSubjectImageUrl($image){
        return "http://iasgyan.org/admin/public/images/subject/".$image;
    }

    private function getChapterImageUrl($image){
        return "http://iasgyan.org/admin/public/images/chapter/".$image;
    }



    private function getTopicImageUrl($image){

        return "http://iasgyan.org/admin/public/images/topic/".$image;



    }



    public function getNotesUrl($image){

        return "https://iasgyan.org/admin/public/content/notes/".$image;


    }



    private function storageUrl()

    {

        return "http://iasgyan.org/admin/public/content/video/";

    }



    private function getImageUrl($image){
        return "http://iasgyan.org/admin/public/images/".$image;
    }


    private function getThumbImageUrl($image){
        return "http://iasgyan.org/admin/public/content/video/images/".$image;
    }



    private function getProfileImageUrl($image){

        return asset("uploads/images/$image");

    }



    private function getQuestionImageUrl($image){



        return "https://agriadmin.org/public/images/question/".$image;

    }



    private function getOptionImageUrl($image){



        return "https://agriadmin.org/public/images/options/".$image;



    }



    private function getSoutionImageUrl($image){



        return "https://agriadmin.org/public/images/solutions/".$image;

    }



    public function getExamInstruction(Request $request){

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'id'=>'required'

        ]);

        $content = null;

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

                'content' =>$content

            ],401);

        }

        $content = DB::table('instructions')->where(['id'=>$request->id])

        ->first();

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);



    }





    public function paginate($items, $perPage = 3, $page = null, $options = ['path'=>'https://agriadmin.org/api/review_question'])

    {

        $page = $page ?: (Paginator::resolveCurrentPage() ?: 1);

        $items = $items instanceof Collection ? $items : Collection::make($items);

        return new LengthAwarePaginator($items->forPage($page, $perPage), $items->count(), $perPage, $page, $options);

    }



    public function Courses(Request $request)

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

                'content' =>$content

            ],401);

        }

        $content = Course::where(['status'=>'Y'])

        ->get();

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);

    }





    public function subjectList(Request $request)

    {

        $validator =  Validator::make($request->all(), [

            'token' => 'required',
            'topic_id' => 'required',

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

        $classId = $request->classId;

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }





        $subjectIds = [];
        if(!empty($request->topic_id)){

            $topics = Topic::where('id',$request->topic_id)->first();
            // $user_subscription = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$request->topic_id)->get();
            // if(!empty($user_subscription)){
            //     foreach($user_subscription as $us){
            //         $subjectIds[] = $us->subject_id; 
            //     }
            // }else{
            //     $topics = Topic::where('id',$request->topic_id)->first();
            //     $subjectIds[] = $topics->subject_id;
            // }



        }

        $content =  Subject::where('id',$topics->subject_id)->with('faculties')->orderBy('id','desc');
        $content = $content->get();

        foreach ($content as  $row) {

            $row->image = $this->getSubjectImageUrl($row->image);

            $row->videos = Content::where(['subject_id'=>$row->id,'type'=>'video','contents.status'=>'Y'])->count();

            $row->notes = Content::where(['subject_id'=>$row->id,'type'=>'notes','contents.status'=>'Y'])->count();

            $row->mock_tests= Exams::where(['board_id'=>$user->board_id,'type'=>2,'sub_id'=>$row->id])->count();
            
        }

        // $v_count=  Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.boards_id'=>$user->board_id,'contents.type'=>'video','contents.status'=>'Y'])->count();

        // $notes = Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.boards_id'=>$user->board_id,'contents.type'=>'notes','contents.status'=>'Y'])->count();

        $v_count=  Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.boards_id'=>$user->board_id,'contents.type'=>'video','contents.status'=>'Y'])->count();

        $notes = Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.boards_id'=>$user->board_id,'contents.type'=>'notes','contents.status'=>'Y'])->count();

        $mock = Exams::where(['board_id'=>$user->board_id,'type'=>2])->count();
        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content,

            "videos"=>$v_count,

            "notes"=>$notes,

            "mock_tests"=>$mock

        ],200);



    }

    public function chapterList(Request $request)

    {



        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'subject_id'=>'required'

        ]);

        $content = array();

        $videos = 0;

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'content' =>$content,

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        $classId = $request->classId;

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }

        $subject_id = request('subject_id');

        $content =  Chapter::where(['subject_id'=>$subject_id])->get();

        foreach ($content as  $row) {
            $topic_list = Topic::where(['chapter_id'=>$row->id])->select(['id'])->get();
            $topic_array = [];
            foreach ($topic_list as $t) {
                $topic_array[] = $t->id;
            }

            $row->image = $this->getChapterImageUrl($row->image);

            $row->videos =  Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.id'=>$row->id,'type'=>'video','contents.status'=>'Y'])->count();

            $row->notes = Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.id'=>$row->id,'type'=>'notes','contents.status'=>'Y'])->count();

            $row->mock_tests= Exams::where(['board_id'=>$user->board_id,'type'=>2])->whereIn('topic_id',$topic_array)->count();

        }

        $notes =  Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.subject_id'=>$subject_id,'contents.type'=>'notes','contents.status'=>'Y'])->count();

        $videos = Content::where(['subject_id'=>$subject_id,'type'=>'video','contents.status'=>'Y'])->count();

        $mock = Exams::where(['board_id'=>$user->board_id,'sub_id'=>$subject_id,'type'=>2])->count();

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content,

            "videos"=>$videos,

            "notes"=>$notes,

            "mock_tests"=>$mock,

        ],200);



    }



    public function topicList(Request $request)

    {

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'chapter_id'=>'required',
            //'topic_id'=>'required',

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

        $classId = $request->classId;

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }

        $topic_id = request('chapter_id');
        $content = [];
        $contents =  Topic::where(['id'=>$topic_id])->get();

        foreach ($contents as  $row) {

            $topic_id = $row->id;
            $row->is_subscription = 'N';
            $subscription_history = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$topic_id)->first();
            if(!empty($subscription_history)){
                $row->is_subscription = 'Y';
                $row->image = $this->getTopicImageUrl($row->image);

                $row->videos = Content::where(['topic_id'=>$row->id,'type'=>'video','contents.status'=>'Y'])->count();

                $row->notes = Content::where(['topic_id'=>$row->id,'type'=>'notes','contents.status'=>'Y'])->count();

                $row->mock_tests= Exams::where(['type'=>2,'topic_id'=>$row->id])->count();


                $content[] = $row;
            }




        }

        // $notes = Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.id'=>$chapter_id,'contents.type'=>'notes','contents.status'=>'Y'])->count();

        // $videos =  Content::join('topics','contents.topic_id','=','topics.id')->join('chapters','topics.chapter_id','=','chapters.id')->where(['chapters.id'=>$chapter_id,'contents.type'=>'video','contents.status'=>'Y'])->count();



        $notes = Content::join('topics','contents.topic_id','=','topics.id')->where(['contents.type'=>'notes','contents.status'=>'Y'])->count();

        $videos =  Content::join('topics','contents.topic_id','=','topics.id')->where(['contents.type'=>'video','contents.status'=>'Y'])->count();

        $topic_list = Topic::where(['id'=>$topic_id])->select(['id'])->get();
        $topic_array = [];
        foreach ($topic_list as $t) {
            $topic_array[] = $t->id;
        }
        $mock = Exams::where(['type'=>2])->whereIn('topic_id',$topic_array)->count();

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content,

            "videos"=>$videos,

            "notes"=>$notes,

            "mock_tests"=>$mock

        ],200);

    }



    public function videoList(Request $request)

    {

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'topic_id'=>'required'

        ]);

        $content = array();

        $expired_on = strtotime("2020-05-31");

        if ($validator->fails()) {

            return response()->json([

                'result' => 'failure',

                'message' => json_encode($validator->errors()),

                'content' =>$content,

            ],400);

        }

        $user = JWTAuth::parseToken()->authenticate();

        $classId = $request->classId;

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }

        $topic_id = request('topic_id');

        $content = Content::where(['topic_id'=>$topic_id,'status'=>'Y','type'=>'video'])->with('subject','topic')->latest()->get();

        foreach ($content as $row) {

            $expired_on = $this->checkContentSubscription($row->id,$user->id);

            $resume = Resume::where(["user_id"=>$user->id,"content_id"=>$row->id,"status"=>'Playing'])->first();

            if($expired_on < time() && $row->is_paid == 'Y'){
                $row->hls = "";
            }
            
            $check_subscription = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$row->topic_id)->where('end_date','>=',date('Y-m-d'))->first();


            if(!empty($check_subscription)){
                $row->is_paid = 'N';
            }




            $row->expired_on = $expired_on;

            $row->thumbnail = $this->getThumbImageUrl($row->thumbnail);
           // $row->thumbnail = $this->getImageUrl($row->thumbnail);

            $row->url = $this->storageUrl().$row->hls.'.mp4';

            $row->skip = 0;

            $row->duration = 0;
            if(!empty($resume)){
                $row->skip = $resume->time;
            }

            $row->view_count = 0;



        }

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);

    }

    public function notesList(Request $request)

    {

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'topic_id'=>'required'

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

        $classId = $request->classId;

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }

        $topic_id = request('topic_id');

        $content = Content::where(['topic_id'=>$topic_id,'type'=>'notes','contents.status'=>'Y'])->latest()->get();

        foreach ($content as $row) {
            $expired_on = $this->checkContentSubscription($row->id,$user->id);

            $row->expired_on = $expired_on;
            $row->notes = $this->getNotesUrl($row->hls);
            // if($expired_on < time() && $row->is_paid == 'Y'){
            //     $row->notes = "";
            // }
            $check_subscription = SubscriptionHistory::where('user_id',$user->id)->where('topic_id',$topic_id)->where('end_date','>=',date('Y-m-d'))->first();


            if(!empty($check_subscription)){
                $row->is_paid = 'N';
            }







        }

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content,

        ],200);



    }

    public function ExamList(Request $request)

    {

        $validator =  Validator::make($request->all(), [

            'token' => 'required',

            'type'=>'required',

            'topic_id'=>'required'

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

        $classId = $request->classId;

        if (empty($user)){

            return response()->json([

                'result' => 'failure',

                'message' => '',

                'content' =>$content

            ],401);

        }

        $course_id = $user->board_id;

        $type = $request->input('type');

        $topic_id = $request->input('topic_id');
        $today = date('Y-m-d');
        if ($type==1) {
            $content = Exams::where(['type'=>$type,'topic_id'=>$topic_id])
            ->where('start_date','<=',$today)
            ->where('end_date','>=',$today)
            // ->whereTime('start_time', '<=',date('H:i'))
            // ->whereTime('end_time', '>=',date('H:i'))
            ->get();
        }

        if ($type==2) {

            $content = Exams::where(['type'=>$type,'topic_id'=>$topic_id])
            ->get();
        }
        if($type==3){
         $content=  Exams::where('type',3)->take(10)->latest()->get();
     }



     if (!empty($content)) {


        foreach ($content as  $row) {

            $row->board_id = 0;
            $expired_on = $this->checkExamSubscriptionNew($row->board_id,$user->id);
            if($row->is_paid == 'Y' && $expired_on > time()){
                $row->is_paid = 'N';
            }

            $user_id = $user->id;

            $exam = Result::where(['user_id'=>$user_id,'exam_id'=>$row->id])->first();

            if (isset($exam)) {

                $row->exam_status = "Y";

            }

            else{

                $row->exam_status = "N";

            }


            $row->is_bookmark = 0;

            $bookmark = DB::table('bookmark_master')->where('type','quiz')->where('quiz_id',$row->id)->first();
            if(!empty($bookmark)){
                $row->is_bookmark = 1;

            }



            $row->image = $this->getExamImageUrl($row->image);

            $exam_question =  DB::table('questions')

            ->select('questions.*','exam_question.q_id','exam_question.marks')

            ->join('exam_question','questions.id','=','exam_question.q_id')

            ->where(['exam_question.exam_id'=>$row->id])

            ->get();

            $row->exam_question_count = count($exam_question);

        }

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$content

        ],200);


    }

    else{

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content

        ],200);

    }

}

public function ExamDetails(Request $request)
{
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'examId'=>'required'

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

    $examId = $request->examId;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content

        ],401);

    }





    $content =  Exams::where(['id'=>$examId])->get();

    if (!empty($content)) {

        $question = array();

        foreach ($content as $row) {

            $exam_question =  Question::select('questions.*','exam_question.q_id','exam_question.marks')

            ->join('exam_question','questions.id','=','exam_question.q_id')

            ->where(['exam_question.exam_id'=>$row->id])

            ->with('subject')

            ->get();

            $row->exam_question_count = count($exam_question);

            $row->image = $this->getExamImageUrl($row->image);

            $instruction = Instructions::where(['id'=>$row->instruction])->first();

            $row->instructions = $instruction;

            $row->exam_question=$r=$exam_question;

            foreach ($exam_question as $r) {

                if (!empty($r->image)) {

                    $r->image = $this->getQuestionImageUrl($r->image);

                }

                else{

                    $r->image = null;

                }

                $options = Options::where(['q_id'=> $r->id])->get();

                foreach ($options as  $op) {

                    $op->image = $this->getOptionImageUrl($op->image);

                    $op->checked = 0;

                }

                $r->options = $options;

                $solutions = Solutions::where(['q_id'=> $r->id])->first();

                    // $solutions->image = $this->getSoutionImageUrl($solutions->image);

                $r->solutions = $solutions;

                $r->guess = 0;

                $r->mark = 0;

                $r->ansType = 0;

            }

        }

    }



    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

    ],200);



}

public function getReportList(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

    ]);

    $content = array();

    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,



        ],401);

    }

    $content = ReportList::get();



    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,



    ],200);



}

public function reportQuestion(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'q_id'=>'required',

        'report'=>'required',

    ]);

    $content = array();

    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => ''

        ],401);

    }



    ReportQuestion::create([

        'q_id'=>request('q_id'),

        'user_id'=>$user->id,

        'report'=>request('report'),



    ]);

    return response()->json([

        'result' => 'success',

        'message' =>'Reported'



    ],200);

}

public function OpenMockTest(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'topic_id'=>'required'

    ]);



    $content = array();

    $expired_on = strtotime("2021-05-31");

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

            'expired_on'=>$expired_on

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    $subjectId = $request->subjectId;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

            'expired_on'=>$expired_on

        ],401);

    }

    $user_id = $user->id;

    $topic_id = $request->input('topic_id');

    $content =  Question::where(['topic'=>$topic_id])->get();

    foreach ($content as $row) {

        $options = Options::where(['q_id'=> $row->id])->get();

        foreach ($options as  $op) {

            $op->checked = 0;

        }

        $row->options = $options;

        $solutions = Solutions::where(['q_id'=> $row->id])->first();

        $row->solutions = $solutions;

        $row->guess = 0;

        $row->mark = 0;

        $row->ansType = 0;



    }

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,

    ],200);

}

public function OpenExam(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'examId'=>'required'

    ]);



    $content = null;

    $expired_on = strtotime("2021-05-31");

    if ($validator->fails()) {

        return response()->json([

            'result' => 'failure',

            'message' => json_encode($validator->errors()),

            'content' =>$content,

            'expired_on'=>$expired_on

        ],400);

    }

    $user = JWTAuth::parseToken()->authenticate();

    $subjectId = $request->subjectId;

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

            'expired_on'=>$expired_on

        ],401);

    }

    $user_id = $user->id;

    $examId = $request->input('examId');

    $result = Result::where(['user_id'=>$user_id,'exam_id'=>$examId])->first();

    unset($result->exam_data);

    if (isset($result)) {

        $result->title = "";

        $result->h_content = "";

        $result->e_content = "";

        $result->exam_question_count =0;

        $result->exam_status = "Y";

        return response()->json([

            'result' => 'success',

            'message' => '',

            'content' =>$result,

            'expired_on'=>$expired_on

        ],200);

    }

    $question = array();

    $content= DB::table('exams')

    ->select('exams.title','exams.image','instructions.h_content','instructions.e_content')

    ->join('instructions','exams.instruction','=','instructions.id')

    ->where(['exams.id'=>$examId])

    ->first();

    $content->image = $this->getExamImageUrl($content->image);

    $exam_question =  DB::table('questions')

    ->select('questions.*','exam_question.q_id','exam_question.marks')

    ->join('exam_question','questions.id','=','exam_question.q_id')

    ->where(['exam_question.exam_id'=>$examId])

    ->get();

    $content->exam_question_count = count($exam_question);

    foreach ($exam_question as $row) {

        $options = Options::where(['q_id'=> $row->id])->get();

        foreach ($options as  $op) {

            $op->checked = 0;

        }

        $row->options = $options;

            //  $solutions = Solutions::where(['q_id'=> $row->id])->first();

            // $row->solutions = $solutions;

        $row->guess = 0;

        $row->mark = 0;

        $row->ansType = 0;



    }

    $expired_on = $this->checkExamSubscription($examId,$user->id);

    $content->exam_question=$exam_question;

    $content->exam_status = "N";

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,

        'expired_on'=>$expired_on

    ],200);

}

private function checkExamSubscription($content_id,$student_id){



    $content = Exams::find($content_id);

    $class_id = $content->class_id;

    $expired_on = "2020-05-31";

        //check in course subscription

    $subscription = SubscriptionHistory::where(['subs_sub_type'=>'class','user_id'=>$student_id,'subs_sub_type_id'=>$class_id])

    ->whereDate('start_date', '<=', date('Y-m-d'))

    ->orderBy('end_date','desc')->first();

    if (!empty($subscription)){

        if ($subscription->end_date > $expired_on){

            $expired_on = $subscription->end_date;

        }

    }

    return strtotime($expired_on) + (3600 * 24);

}

public function SubmitExam(Request $request){

    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'examId'=>'required',
        'examData'=>'required',
    ]);


    // DB::table('testimonials')->where('id',1)->update(array('text'=>$request->toArray()));

    // exit();

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

    $data  = json_decode($request->examData);
    // $data  = [];
    $subjectArray = array();

        // $data  = json_decode((Result::select(['exam_data'])->first())->exam_data);

    $correct = 0;

    $incorrect = 0;

    $unattemped = 0;

    $total = 0;

    $examId = $request->input('examId');

    $user_id = $user->id;

    $totalMarks = 0;

    $obtained = 0;

    $time = 0;
    $subjectAnalysisArray =[];
    if (isset($request->time)) {
        $time = $request->time;
    }

    $subjectArr = [];
    if(!empty($data)){
        foreach ($data as $value) {
            $subTotalMarks = 0;
            $subObtainedMarks = 0;
            $subAttempted = 0;
            $subCorrect = 0;
            $subTotal = 0;
            $subSkip = 0;
            $subIncorrect = 0;




            $subjectTotalMarks = 0;
            $subjectObtainedMarks = 0;
            $subjectAttempted = 0;
            $subjectCorrect = 0;
            $subjectTotal = 0;
            $subjectSkip = 0;
            $subjectIncorrect = 0;


            foreach ($value as $question) {

                $subject_name = $question->subject_name;

                $subjectNameArr = [];
                $e_qId = $question->q_id;
                $positive_marks = 1;
                $negative_marks = 0.25;
                $q_marks = ExamQuestion::where(["q_id"=>$e_qId,"exam_id"=>$examId])->first();

                $question_details = Question::where('id',$e_qId)->first();


                if(!empty($q_marks)){
                    $positive_marks = $q_marks->marks;
                    $negative_marks = $q_marks->negative_mark;
                }
                $total += 1;
                $totalMarks += $positive_marks;
                $subTotalMarks += $positive_marks;
                $subTotal += 1;
                $options = $question->options;
                $qstatus = '';
                $subjectSkip = 0;
                if($question->ansType == 1){
                    $qstatus = 'Y';
                    $subAttempted += 1;
                    foreach($options as $o){
                        if($o->correct == 1 && $o->checked != 1){
                            $qstatus = 'N';
                        }}
                        $question->qstatus = $qstatus;
                        if($qstatus == 'Y'){
                            $correct = $correct +1;
                            $obtained += $positive_marks;
                            $subCorrect += 1;
                            $subObtainedMarks += $positive_marks;
                            $subjectObtainedMarks = $positive_marks;

                        } else {
                            $incorrect = $incorrect +1;
                            $obtained -= $negative_marks;
                            $subObtainedMarks -= $negative_marks;
                            $subjectObtainedMarks = $negative_marks;

                            $subIncorrect  += 1;
                        }
                    } else {
                        $question->qstatus = $qstatus;
                        $unattemped = $unattemped +1;
                        $subSkip += 1;
                        $subjectSkip = 1;

                    }


                // if($question->ansType == 1){
                //     $qstatus = 'Y';
                //     $subjectAttempted = 1;
                //     foreach($options as $o){
                //         if($o->correct == 1 && $o->checked != 1){
                //             $qstatus = 'N';
                //         }}
                //         $question->qstatus = $qstatus;
                //         if($qstatus == 'Y'){
                //             $correct = 1;
                //             $obtained = $positive_marks;
                //             $subjectCorrect = 1;

                //             $subjectObtainedMarks = $positive_marks;
                //         } else {
                //             $incorrect = 1;
                //             $obtained = $negative_marks;
                //             $subjectObtainedMarks = $negative_marks;
                //             $subjectIncorrect = 1;
                //         }
                //     } else {
                //         $question->qstatus = $qstatus;
                //         $unattemped = 1;
                //         $subjectSkip = 1;
                //     }




                    $subject = DB::table('sub_topic')->where('subject_name',$subject_name)->first();
                    $core = 0;
                    $incore = 0;
                    $skip = 0;
                    if($qstatus == 'Y'){
                        $core = 1;
                        $incore = 0;
                        $skip = 0;
                    }

                    if($qstatus == 'N'){
                     $core = 0; 
                     $incore = 1; 
                     $skip = 0;

                 }
                 if($subjectSkip == 1){
                   $core = 0; 
                   $incore = 0; 
                   $skip = 1;
               }


               $subjectAnalysisArray [] = array(
                "total"=>$subTotal,
                "attempted"=>$subjectAttempted,
                "correct"=>$core,
                "incorrect"=>$incore, 
                        // "correct"=>$subjectCorrect,
                        // "incorrect"=>$subjectIncorrect,
                "skip"=>$skip,
                "obtainedMarks"=>$subjectObtainedMarks,
                "subjectName"=>isset($subject_name) ? $subject_name :'',
                "subjectId"=>isset($subject->id) ? $subject->id : 0,
            );

           }
           if(sizeof($value) > 0){
            $subjectName = $value[0]->subject->title;
            $subjectId = $value[0]->subject->id;
            $subjectArray[] = array(
                "total"=>$subTotal,
                "attempted"=>$subAttempted,
                "correct"=>$subCorrect,
                "incorrect"=>$subIncorrect,
                "skip"=>$subSkip,
                "totalMarks"=>$subTotalMarks,
                "obtainedMarks"=>round($subObtainedMarks),
                "subjectName"=>isset($subjectName) ? $subjectName :'',
                "subjectId"=>isset($subjectId) ? $subjectId:0,
            );
        }




    }
}



$subjectArr = json_encode($subjectAnalysisArray);
$subjectJson = json_encode($subjectArray);

Result::updateOrCreate(

    ['user_id'=>$user->id,'exam_id'=>$request->examId,],

    [
        'exam_data'=>$request->examData,

        'total_question'=>$total,

        'correct_question'=>$correct,

        'incorrect_question'=>$incorrect,

        'skip_question'=>$unattemped,

        'total_makrs'=>$totalMarks,

        'marks'=>$obtained,

        'time'=>$time,

        "subject_result"=>$subjectJson,
        "subject_analysis"=>$subjectArr,
    ]
);


$this->update_rank($request->examId);

$message = "IAS GYAN , thanks you for submitting your exam successfully.\n You have scored ".$obtained."/".$totalMarks  ;
            //$this->send_message($user->phone,$message);


$exam = Exams::where(['id'=>$examId])->first();
            //$this->sendCertificate($user->name,$user->email,$totalMarks,$obtained,$exam->title);

return response()->json([

    'result' => 'success',

    'message' => 'Exam Submitted Successfully.',

],200);

}

public function reSubmitForSubject(){
    $exam = Result::get();
    foreach ($exam as $e){
        $data = json_decode($e->exam_data);
        $subjectArray = array();
        foreach ($data as $value) {
            $subTotalMarks = 0;
            $subObtainedMarks = 0;
            $subAttempted = 0;
            $subCorrect = 0;
            $subTotal = 0;
            $subSkip = 0;
            $subIncorrect = 0;

            foreach ($value as $question) {
                $subTotalMarks += 1;
                $subTotal += 1;


                $options = $question->options;

                $qstatus = '';

                if($question->ansType == 1)

                {

                    $qstatus = 'Y';
                    $subAttempted += 1;

                    foreach($options as $o)

                    {

                        if($o->correct == 1 && $o->checked != 1)

                        {

                            $qstatus = 'N';

                        }

                    }

                    $question->qstatus = $qstatus;

                    if($qstatus == 'Y')

                    {
                        $subCorrect += 1;
                        $subObtainedMarks += 1;



                    } else {

                        $subObtainedMarks -= 1/4;

                        $subIncorrect  += 1;

                    }



                } else {

                    $question->qstatus = $qstatus;


                    $subSkip += 1;

                }

            }
            if(sizeof($value) > 0){
                $subjectName = $value[0]->subject->title;
                $subjectId = $value[0]->subject->id;
                $subjectArray[] = array(
                    "total"=>$subTotal,
                    "attempted"=>$subAttempted,
                    "correct"=>$subCorrect,
                    "incorrect"=>$subIncorrect,
                    "skip"=>$subSkip,
                    "totalMarks"=>$subTotalMarks,
                    "obtainedMarks"=>$subObtainedMarks,
                    "subjectName"=>$subjectName,
                    "subjectId"=>$subjectId
                );
            }


        }

        $result = Result::find($e->id);

        $result->subject_result = json_encode($subjectArray);
        $result->save();

    }
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

private function sendCertificate($name,$email,$total_makrs,$obtained,$exam_name)
{
    $html ="";
    $html .='<!DOCTYPE html>
    <html>
    <head>
    <meta name="viewport" content="width=device-width, initial-scale=1">
    </head>
    <body>
    <div class="container" style="position:relative;text-align: center;color: black;font-size: 18px;">
    <img src="https://agriadmin.org/public/images/exam/exam.jpg" alt="Snow" style="width:100%;">
    <div class="centered" style="position: absolute;top: 50%;left: 50%;transform: translate(-50%, -50%);"><p>This Certificate is presented to Mr/Ms <b>'.$name.'</b> For getting <b>'.$obtained.'/'.$total_makrs.'</b> Marks in <b> '.$exam_name.'</b> Test. <br>
    We Wish you all the best for your next test.
    </p></div>
    </div>

    </body>
    </html>';
    $ch = curl_init();

    curl_setopt($ch, CURLOPT_URL, base64_decode("aHR0cHM6Ly9hcGkuc2VuZGdyaWQuY29tL3YzL21haWwvc2VuZA=="));

    curl_setopt($ch, CURLOPT_POST, 1);

    curl_setopt($ch, CURLOPT_POSTFIELDS, json_encode(array("personalizations" => array(array("to" => array(array("email" =>$email)))),"from" => array("email" =>"info@iasgyan.in"),"subject" => "IASGYAN Coaching Certificate","content" => array(array("type" => "text/html","value" =>$html)))));
    curl_setopt($ch, CURLOPT_HTTPHEADER, array('Authorization: Bearer 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','Content-type: application/json'));

    curl_setopt($ch, CURLOPT_FOLLOWLOCATION, 1);

    curl_setopt($ch, CURLOPT_RETURNTRANSFER, 1);

    curl_exec($ch);

    curl_close($ch);

    return $ch;

}
public function update_rank($examId){
    $rank = 1;
    $result = Result::where(['exam_id'=>$examId])->orderBy('marks','desc')->get();
    foreach ($result as $key => $user_data){
        if ($key == 0){
            Result::find($user_data->id)->update(['rank'=>($key + 1)]);

            $rank = $key + 1;
        }else{
            $previous_key = ($key - 1);

            if ($result[$key]->marks==$result[$previous_key]->marks) {

                Result::find($user_data->id)->update(['rank'=>$rank]);
                $this->time_wise_rank($examId,$rank);
            }
            else{
                Result::find($user_data->id)->update(['rank'=>($key + 1)]);
                $rank = $key + 1;
            }
        }
    }
}

private function time_wise_rank($examId,$rank){
    $result = Result::where(['exam_id'=>$examId,'rank'=>$rank])->orderBy('time','asc')->get();
    foreach ($result as $r){
        Result::find($r->id)->update(['rank'=>$rank]);
        $rank += 1;
    }
}

public function viewResult(Request $request)
{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'examId'=>'required'

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

            'content' =>$content

        ],401);

    }

    $examId = $request->input('examId');

    $user_id = $user->id;

        // print_r($user_id);die();

    $content = Result::where(['exam_id'=>$examId,'user_id'=>$user_id])->orderBy('id','DESC')->get();

    foreach ($content as $c){

        $exam_data = json_decode($c->exam_data);
        $exam_data_list = array();
        foreach ($exam_data as $key => $value) {
            $exam_data_list[] = array(
                "subject_name"=>$key,
                "exam_list"=>$value
            );
        }
        $c->exam_data = $exam_data_list;


    }

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

    ],200);

}

public function graphData(Request $request)
{
    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'examId'=>'required'

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

    $examId = $request->input('examId');

    $user_id = $user->id;

    $my_result = Result::where(['exam_id'=>$examId,'user_id'=>$user_id])->orderBy('id','DESC')
    ->select([
        "total_makrs",
        "marks",
        "time",
        "subject_result",
        "subject_analysis",
    ])
    ->first();
    if(empty($my_result)){
        return response()->json([

            'result' => 'failure',

            'message' => 'Please appear in exam to view result',

            'content' =>$content

        ],200);
    }
    $topper_details = Result::where(['exam_id'=>$examId])->orderBy('marks','DESC')->select(['marks'])->first();

    $topper_marks = $topper_details->marks;

    $avg_marks = Result::where(['exam_id'=>$examId])->avg('marks');

    $sub_results = Result::where(['exam_id'=>$examId])->select([
        "subject_result"
    ])->get();
    $subs = [];
    foreach ($sub_results as $s){
        $re = json_decode($s->subject_result);
        foreach ($re as $r){
            $subs[$r->subjectId][] = $r->obtainedMarks;
        }
    }

//        print_r($subs);die;
    $mySubResult = json_decode($my_result->subject_result);
    $subject_analysis = json_decode($my_result->subject_analysis);
    $total_subs = [];
    foreach ($mySubResult as $mySu){
        $total_subs[] = array(
            "sub_id"=>$mySu->subjectId,
            "sub_name"=>$mySu->subjectName,
            "result"=>array(
                "topper"=>max($subs[$mySu->subjectId]),
                "average"=>array_sum($subs[$mySu->subjectId])/count($subs[$mySu->subjectId]),
                "mine"=>$mySu->obtainedMarks
            ),
        );
    }

    $content = array(
        "total_makrs"=>$my_result->total_makrs,
        "marks"=>$my_result->marks,
        "topper"=>$topper_marks,
        "avg_marks"=>floatval($avg_marks),
        "time"=>$my_result->time,
        "subject_wise_result"=>$total_subs,
        "subject_analysis"=>$subject_analysis,
    );


    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

    ],200);
}

public function viewResult1(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

        'examId'=>'required'

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

            'content' =>$content

        ],401);

    }

    $examId = $request->input('examId');

    $user_id = $user->id;

        // print_r($user_id);die();

    $content = Result::where(['exam_id'=>$examId,'user_id'=>$user_id])->orderBy('id','DESC')->get();

    foreach ($content as $c){

        $c->exam_data = json_decode($c->exam_data);

    }

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

    ],200);

}

public function viewRank(Request $request){
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'examId'=>'required'
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

            'content' =>$content

        ],401);

    }

    $examId = $request->input('examId');

    $user_id = $user->id;

    $studentResult = Result::where(['exam_id'=>$examId,'user_id'=>$user_id])->select([
        "id",
        "user_id",
        "exam_id",
        "subject_result",
        "total_question",
        "correct_question",
        "incorrect_question",
        "skip_question",
        "total_makrs",
        "marks",
        "time",
        "rank",
        "created_at",
        "updated_at",
    ])->latest()->first();

    if(empty($studentResult)){
        return response()->json([

            'result' => 'failure',

            'message' => 'Something went wrong',

            'content' =>$content

        ],200);
    }

    $totalRankers = Result::where(['exam_id'=>$examId])->count();

    if(!empty($studentResult)){
        $studentResult->subject_result = json_decode($studentResult->subject_result);
    }

    $queIds =[];
    $exam_questions = ExamQuestion::where('exam_id',$examId)->get();
    if(!empty($exam_questions)){
        foreach($exam_questions as $que){
            $queIds[] = $que->q_id;
        }
    }

    if(!empty($queIds)){

    }








    $content = array(
        "studentResult"=>$studentResult,
        "totalRankers"=>$totalRankers
    );

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

    ],200);

}

public function examRank(Request $request)
{
    $validator =  Validator::make($request->all(), [
        'token' => 'required',
        'examId'=>'required'
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
    $examId = $request->input('examId');

    $user_id = $user->id;

    $content = Result::join('users','results.user_id','=','users.id')->where(['results.exam_id'=>$examId])->select([
        "results.id",
        "results.user_id",
        "results.exam_id",
        "results.total_question",
        "results.correct_question",
        "results.incorrect_question",
        "results.skip_question",
        "results.total_makrs",
        "results.marks",
        "results.time",
        "results.rank",
        "results.created_at",
        "results.updated_at",
        "users.name",
        "users.image",
        "results.subject_analysis",
    ])->orderBy('results.rank','asc')->paginate(10);
//        print_r($content);die;





    if(!empty($content)){
        foreach ($content as $data){
            if($data->image != null && $data->image != ""){
                $data->image = $this->getProfileImageUrl($data->image);
                $data->subject_analysis = json_decode($data->subject_analysis);
            }
        }
    }

    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content

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



public function getVideoReportList(Request $request)

{

    $validator =  Validator::make($request->all(), [

        'token' => 'required',

    ]);

    $content = array();

    $user = JWTAuth::parseToken()->authenticate();

    if (empty($user)){

        return response()->json([

            'result' => 'failure',

            'message' => '',

            'content' =>$content,

        ],401);

    }



    return response()->json([

        'result' => 'success',

        'message' => '',

        'content' =>$content,



    ],200);



}

}

