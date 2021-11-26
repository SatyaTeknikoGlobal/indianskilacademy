<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boards;
use App\Classes;
use App\Exams;
use App\Instructions;
use App\Question;
use App\ExamQuestion;
use App\Subject;
use DB;
use Excel;
use App\UserLogin;
use App\Exports\ReportExport;

use App\Imports\QuestionImport;

use Yajra\DataTables\DataTables;


class ExamController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data = Exams::where(['type'=>1])->latest()->orderby('id','desc')->get();
       if (auth()->user()->is_admin == 2) {
        $id = auth()->user()->faculties_id;
        $data = Exams::where(['type'=>1,'create_by'=>'Teacher','create_id'=>$id])->latest()->orderby('id','desc')->get();
    }

    return view('admin/exam/list',array('data'=>$data));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
     $boards = Boards::latest()->get();
     $class = Classes::latest()->get();
     $instructions = Instructions::latest()->get(); 

     return view('admin/exam/add',array('boards'=>$boards,'class'=>$class,'instructions'=>$instructions));
 }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
     $request->validate([
        'board_id' =>'required',
        'subject_id' =>'required',
        'time'=>'required',
        'topic_id'=>'required',
        'title'=>'required',
        
        'instruction'=>'required',
        'start_date'=>'required',
        'marks'=>'required',
        'negetive_marks'=>'required',
        
        'start_time'=>'required',
        
    ]);
     $image = $request->file('image');
      // $subject_id = implode($request->subject_id, ',');
     if (!empty($image)) {
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
         request()->image->move(public_path('images/exam'), $imageName);
     }
     else{
       $imageName = '';  
   }
   $result = Exams::create([
    'board_id'=>request('board_id'),
    'sub_id'=>request('subject_id'),
    'topic_id'=>request('topic_id'),
    'type'=>1,
    'title'=>request('title'),
    'session_time'=>request('time'),
    'instruction'=>request('instruction'),
    'start_date'=>request('start_date'),
    'end_date'=>request('end_date'),
    'image'=>$imageName,
    'start_time'=>request('start_time'),
    'end_time'=>request('end_time'),
    'reward_mark'=>request('reward_mark'),
    'negetive_marks'=>request('negetive_marks'),
    'marks'=>request('marks'),
    'create_by'=>'Admin',
    'create_id'=>1,
    'status'=>'Active',
    'language'=>null,
]);

   $title = 'New Exam Created';
   $msg = '';
   if($result){
       $data = UserLogin::get();
       foreach ($data as $row) {
        $deviceToken = $row->deviceToken;
        $this->send_notification($title, $msg, $deviceToken , "admin");
        
    }
    return redirect()->route('exam.index');
}

return redirect()->route('exam.index')
->with('success','Exam Added successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {

        $exam = Exams::where(['id'=>$id])->first();
        $subject = explode(",", $exam->sub_id);
        $boards = Boards::latest()->get();
        $q = ExamQuestion::where(['exam_id'=>$id])->get();
        $question = Question::latest()->with('subjects');


        $s = array();

        // Fiter By Subject
        if(isset($_GET['subject']) && $_GET['subject']!="0"){
            $question =$question->where(['subject'=>$_GET['subject']]);
        }
        //Filter By Chapter
        if(isset($_GET['chapter']) && $_GET['chapter']!="0"){

            $question =$question->where(['chapter'=>$_GET['chapter']]);
        }
        //Filter By Topic
        if(isset($_GET['topic']) && $_GET['topic']!="0" ){
            $question =$question->where(['topic'=>$_GET['topic']]);
        }

        else{
         if (!empty($subject)) {
             foreach ($subject as $row) {
                 $question = $question->where(['subject'=>$row]);
                 $s = Subject::where(['id'=>$row])->get();
             }
         }
     }

     $items=array();
     foreach ($question->get() as $key => $qt) {
        $item=array(
            "id"=>$qt->id,
            "e_question"=>$qt->e_question,
            "makrs"=>$qt->makrs,
            "difficulty_level"=>$qt->difficulty_level,
        );
        array_push($items,$item);
    }

    $question = $question->get();
    return view('admin/exam/qlist',array('question'=>$question,'id'=>$id,'boards'=>$boards,'subject'=>$s,'qData'=>$q,'parameters'=>count($_GET)!=0 ));
}

public  function addQuestionExam(Request $request){
    $request->validate([
        'exam_id' =>'required',
        'questions' =>'required'
    ]);
    $exam_id  = request('exam_id');
    $question  = request('questions');
    ExamQuestion::where(['exam_id'=>$exam_id])->delete();
    foreach ($question as $row) {
        ExamQuestion::create([
            'exam_id'=>$exam_id,
            'q_id'=>$row,
            'marks'=>1,
            'negative_mark'=>0.25,
        ]);
    }
    return redirect()->route($type.'.index')
    ->with('success','Question Added successfully.');

}

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $instructions = Instructions::latest()->get(); 
        $exam = Exams::where(['id'=>$id])->first();
        $boards = Boards::latest()->get();

        $subject = Subject::where(['board_id'=>$exam->board_id])->get();

        return view('admin/exam/edit',array('instructions'=>$instructions,'exam'=>$exam,'boards'=>$boards,'subject'=>$subject));
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
            'board_id' =>'required',
            'subject_id' =>'required',
            'time'=>'required',
            'title'=>'required',
            //'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'instruction'=>'required',
            'start_date'=>'required',
            //'end_date'=>'required',
            'start_time'=>'required',
            //'end_time'=>'required',
            'id'=>'required',
            'negetive_marks'=>'required',
            'marks'=>'required',
        ]);

        $id = request('id');
        $subject_id = implode($request->subject_id, ',');
        $board_id = implode($request->board_id, ',');
        $data = array();
        $image = $request->file('image');
        if (!empty($image)) {
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
         request()->image->move(public_path('images/exam'), $imageName);

         $data = array(
            'board_id'=>$board_id,
          //  'sub_id'=>$subject_id,
            'type' =>1,
            'session_time' =>request('time'),
            'title' =>request('title'),
            'image' =>$imageName,
            'instruction' =>request('instruction'),
            'start_date' =>request('start_date'),
            'end_date' =>request('end_date'),
            'start_time' =>request('start_time'),
            'end_time' =>request('end_time'),
            'reward_mark'=>request('reward_mark'),
            'negetive_marks'=>request('negetive_marks'),
            'marks'=>request('marks'),

        );
     }
     else{
        $data = array(
            'board_id'=>$board_id,
          //  'sub_id'=>$subject_id,
            'type' =>1,
            'session_time' =>request('time'),
            'title' =>request('title'),
            'instruction' =>request('instruction'),
            'start_date' =>request('start_date'),
            'end_date' =>request('end_date'),
            'start_time' =>request('start_time'),
            'end_time' =>request('end_time'),
            'reward_mark'=>request('reward_mark'),
            'negetive_marks'=>request('negetive_marks'),
            'marks'=>request('marks'),
            
        );
    }

    Exams::where(['id'=>$id])->update($data);
    return redirect()->route('exam.index')
    ->with('success','Exam Updated successfully.');

}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */


    public function importQuestionExamWise(Request $request)
    {
        $this->validate($request, [
          'select_file'  => 'required|mimes:xls,xlsx',
          'exam_id'  => 'required'

      ]);
        $exam_id = $request->input('exam_id');
        Excel::import(new QuestionImport,request()->file('select_file'));

        return redirect()->route('exam.index')
        ->with('success','Question Import successfully.');
    }
    public function destroy($id)
    {
     Exams::where('id',$id)->delete();
     return redirect()->route('exam.index')
     ->with('success','Exams Delete successfully.');
 }

 public function reports(Request $request){
    $exam_id = isset($request->id) ? $request->id : 0;
    $data = [];
    $result = '';
    $result = DB::table('results')->where('exam_id',$exam_id)->orderby('rank');

    if($request->method('post')|| $request->method('POST')){
        $export_xls = isset($request->export_xls) ? $request->export_xls : 0;
        if(!empty($export_xls) && ($export_xls == 1 || $export_xls == '1') ){
            return $this->exportXls($result);
        }
    }
    $result = $result->paginate(15);
    $users = DB::table('users')->get();

    $data['users'] = $users;
    $data['results'] = $result;
    $data['exam_id'] = $exam_id;
    return view('admin/exam/reports',$data);

}

private function exportXls($reports){
    $reports = $reports->get();
    $exportArr = [];
    $users = DB::table('users')->get();

    if(!empty($reports) && $reports->count() > 0){
        foreach($reports as $report){
           foreach($users as $user){
            if($report->user_id == $user->id){
              $name = $user->name;
          }
      }
      $reportArr = [];

      $reportArr['Id'] = $report->id;
      $reportArr['Name'] = $name;
      $reportArr['Name'] = $name;
      $reportArr['Marks'] = $report->marks;

      $reportArr['Rank'] = $report->rank;
      $reportArr['Time'] = round(($report->time /60),2);
      $exportArr[] = $reportArr;
  }
}
$filedNames = array_keys($exportArr[0]);
$fileName = 'reports_'.date('Y-m-d-H-i-s').'.xlsx';

return Excel::download(new ReportExport($exportArr, $filedNames), $fileName);
}

public function send_notification($title, $body, $deviceToken , $type){
    $deviceToken = $deviceToken;
    $sendData = array(
        'body' => $body,
        'title' => $title,
        'type' => $type,
        'sound' => 'Default'
    );
    $this->fcmNotification($deviceToken,$sendData);
}

public function fcmNotification($device_id, $sendData)
{
        #API access key from Google API's Console
    if (!defined('API_ACCESS_KEY')){
        define('API_ACCESS_KEY', 'AAAAb9zEyQk:APA91bGh_dUdj54X_izuETEuN_eyqpGRTBcq2fQi2_a4oJvWsrozGidYoQ7zFCSsXS1OnTz6Z1tHuf2VQ_vjTRkrX73WsoRlu1pTR6J99MmeK__PtEep16nApRpwFaadW0stoXi8x57Q');
    }

    $fields = array
    (
        'to'    => $device_id,
        'data'  => $sendData,
        'notification'  => $sendData
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
        //$data = json_decode($result);
    if($result === false)
        die('Curl failed ' . curl_error($ch));

    curl_close($ch);
    return $result;
}





}
