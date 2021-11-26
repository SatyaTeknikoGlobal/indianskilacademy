<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boards;
use App\Classes;
use App\Exams;
use App\Instructions;
use App\Subject;
use App\Question;
use App\ExamQuestion;

class TestController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Exams::where(['type'=>2])->latest()->with('topic')->get();
        return view('admin/test/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         $boards = Boards::latest()->get();
          $instructions = Instructions::latest()->get(); 
        return view('admin/test/add',array('boards'=>$boards,'instructions'=>$instructions));
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
        'chapter'=>'required',
        'topic'=>'required',
        'time'=>'required',
        'title'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'instruction'=>'required',
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
            'sub_id'=>request('subject_id'),
            'topic_id'=>request('topic'),
            'type'=>2,
            'title'=>request('title'),
            'session_time'=>request('time'),
            'instruction'=>request('instruction'),
            'image'=>$imageName,
            'create_by'=>'Admin',
            'create_id'=>1,
            'status'=>'Active',
            'language'=>null,
        ]);
        return redirect()->route('test.index')
        ->with('success','Mock Test Added successfully.');
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
        $topic = $exam->topic_id;
        $question = Question::where(['topic'=>$topic])->latest()->with('subjects')->get();  
        $q = ExamQuestion::where(['exam_id'=>$id])->get();
        return view('admin/test/qlist',array('question'=>$question,'id'=>$id,'qData'=>$q));
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

        return view('admin/test/edit',array('instructions'=>$instructions,'exam'=>$exam,'boards'=>$boards,'subject'=>$subject));
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
        'time'=>'required',
        'title'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'instruction'=>'required',
        'id'=>'required',
        ]);

        $id = request('id');
        $data = array();
          $image = $request->file('image');
         if (!empty($image)) {
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/exam'), $imageName);

         $data = array(
            'type' =>2,
            'session_time' =>request('time'),
            'title' =>request('title'),
            'image' =>$imageName,
            'instruction' =>request('instruction'),
        );
        }
        else{
            $data = array(
            'type' =>2,
            'session_time' =>request('time'),
            'title' =>request('title'),
            'instruction' =>request('instruction'),
        );
        }

        Exams::where(['id'=>$id])->update($data);
       return redirect()->route('test.index')
            ->with('success','Mock Test Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Exams::where('id',$id)->delete();
         return redirect()->route('test.index')
        ->with('success','Mock Test Delete successfully.');
    }
}
