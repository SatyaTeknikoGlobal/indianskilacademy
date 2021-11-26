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
use App\Exports\ReportExport;

use App\Imports\QuestionImport;

use Yajra\DataTables\DataTables;


class QuizController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Exams::where(['type'=>3])->latest()->get();
        if (auth()->user()->is_admin == 2) {
            $id = auth()->user()->faculties_id;
            $data = Exams::where(['type'=>1,'create_by'=>'Teacher','create_id'=>$id])->latest()->get();
        }
        return view('admin/quiz/list',array('data'=>$data));
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

     return view('admin/quiz/add',array('boards'=>$boards,'class'=>$class,'instructions'=>$instructions));
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
        'time'=>'required',
        'title'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'instruction'=>'required',
        'start_date'=>'required',
        'end_date'=>'required',
        'start_time'=>'required',
        'end_time'=>'required',
        // 'subject_id'=>'required',
        // 'topic_id'=>'required',
        'marks'=>'required',
        'negetive_marks'=>'required',
    ]);
     $image = $request->file('image');
     if (!empty($image)) {
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
         request()->image->move(public_path('images/exam'), $imageName);
     }
     else{
       $imageName = '';  
   }
   Exams::create([
    'board_id'=>request('board_id'),
    'sub_id'=>0,
    'type'=>3,
    'title'=>request('title'),
    'session_time'=>request('time'),
    'instruction'=>request('instruction'),
    'start_date'=>request('start_date'),
    'end_date'=>request('end_date'),
    'image'=>$imageName,
    'start_time'=>request('start_time'),
    'end_time'=>request('end_time'),
    'create_by'=>'Admin',
    'create_id'=>1,
    'status'=>'Active',
    'sub_id'=>1,
    'topic_id'=>1,
    'language'=>null,
    'negetive_marks'=>request('negetive_marks'),
    'marks'=>request('marks'),
]);
   return redirect()->route('quiz.index')
   ->with('success','Quiz Added successfully.');
}

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */

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
        return view('admin/quiz/edit',array('instructions'=>$instructions,'exam'=>$exam,'boards'=>$boards));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request,$id)
    {
        $request->validate([
            'board_id' =>'required',
            'time'=>'required',
            'title'=>'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'instruction'=>'required',
            'start_date'=>'required',
            'marks'=>'required',
            'negetive_marks'=>'required',
           // 'end_date'=>'required',
            'start_time'=>'required',
            //'end_time'=>'required',
            // 'subject_id'=>'required',
            // 'topic_id'=>'required',
        ]);
        $board_id = implode($request->board_id, ',');
        $data = array();
        $image = $request->file('image');
        if (!empty($image)) {
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
         request()->image->move(public_path('images/exam'), $imageName);
         $data = array(
            'board_id'=>$board_id,
            'session_time' =>request('time'),
            'title' =>request('title'),
            'image' =>$imageName,
            'instruction' =>request('instruction'),
            'start_date' =>request('start_date'),
            'end_date' =>request('end_date'),
            'start_time' =>request('start_time'),
            'end_time' =>request('end_time'),
            'sub_id'=>1,
            'topic_id'=>1,
            'negetive_marks'=>request('negetive_marks'),
            'marks'=>request('marks'),
        );
     }
     else{
        $data = array(
            'board_id'=>$board_id,
            'session_time' =>request('time'),
            'title' =>request('title'),
            'instruction' =>request('instruction'),
            'start_date' =>request('start_date'),
            'end_date' =>request('end_date'),
            'start_time' =>request('start_time'),
            'end_time' =>request('end_time'),
            'sub_id'=>1,
            'topic_id'=>1,
            'negetive_marks'=>request('negetive_marks'),
            'marks'=>request('marks'),
        );
    }

    Exams::where(['id'=>$id])->update($data);
    return redirect()->route('quiz.index')
    ->with('success','Quiz Updated successfully.');

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
          'exam_id'  => 'required',

      ]);
        $exam_id = $request->input('exam_id');
        Excel::import(new QuestionImport,request()->file('select_file'));

        return redirect()->route('quiz.index')
        ->with('success','Question Import successfully.');
    }
    public function destroy($id)
    {
     Exams::where('id',$id)->delete();
     return redirect()->route('exam.index')
     ->with('success','Exams Delete successfully.');
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

}
