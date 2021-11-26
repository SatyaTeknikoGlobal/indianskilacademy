<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chapter;
use App\Boards;
use App\Classes;
use App\Subject;
use Illuminate\Support\Facades\DB;

class SubTopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data= DB::table('sub_topic')->orderby('id','DESC')->get();
        
        return view('admin/subtopic/list',array('data'=>$data));
    }
    public function create()
    {
        $board = DB::table('sub_topic')->latest()->get();
        return view('admin/subtopic/add',array('board'=>$board));
    }

    public function store(Request $request)
    {
     $request->validate([
        'status'=>'required',
        'subject_name'=>'required',
        'topic_name'=>'required',
    ]);
     DB::table('sub_topic')->insert([
        'subject_name'=>request('subject_name'),
        'topic_name'=>request('topic_name'),
        'status'=>request('status'),
    ]);
     return redirect()->route('subtopic.create')
     ->with('success','Subject & Topic Added successfully.');
 }

 
 public function show($id)
 {
        //
 }

 public function edit($id)
 {
    $subtopic = DB::table('sub_topic')->where('id',$id)->first();
    return view('admin/subtopic/edit',array('subtopic'=>$subtopic));
}

public function update(Request $request)
{
 $request->validate([
   'status'=>'required',
   'subject_name'=>'required',
   'topic_name'=>'required',
   'id'=>'required'
]);
 $id = $request->input('id');
 $data = array(
     'subject_name'=>request('subject_name'),
     'topic_name'=>request('topic_name'),
     'status'=>request('status'),
 );
 DB::table('sub_topic')->where('id',$id)->update($data);
 return redirect()->route('subtopic.index')
 ->with('success','Record Updated successfully.');
}

public function destroy($id)
{
    DB::table('sub_topic')->where(['id'=>$id])->delete();
    return redirect()->route('subtopic.index')
    ->with('success','Record Deleted successfully.');
}
}
