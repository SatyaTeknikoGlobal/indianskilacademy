<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\LiveClass;
use App\Faculties;
use App\Boards;
use App\Topic;
use App\Subject;
class LiveClassesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = LiveClass::latest()->get();
        return view('admin/liveclass/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
    	$topics = Topic::where(['is_delete'=>0])->get();
        $data = Faculties::latest()->get();
        return view('admin/liveclass/add',array('data'=>$data,'topics'=>$topics));
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
        'title' =>'required',
        'description'=>'',
        'start_date'=>'required',
        'start_time'=>'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'end_time'=>'required',
        'meeting_id'=>'',
        'passcode'=>'',
        'course_id'=>'required',
        'faculties_id'=>'required'
        ]);
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/liveclasses'), $imageName);
        LiveClass::create([
            'title'=>request('title'),
            'description'=>request('description'),
            'image'=>$imageName,
            'start_date'=>request('start_date'),
            'start_time'=>request('start_time'),
            'end_time'=>request('end_time'),
            'channel_id'=>request('meeting_id'),
            'passcode'=>request('passcode'),
            'faculties_id'=>request('faculties_id'),   
            'course_id'=>request('course_id'),
        ]);
        return redirect()->route('live_classes.index')->with('success','LiveClass Added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        LiveClass::where(['id'=>$id])->update(['end_status'=>'Y']);
          return redirect()->route('live_classes.index')
        ->with('success','LiveClass End successfully.');
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
    	$courses = Boards::where(['status'=>'Y'])->get();

        $topics = Topic::where(['is_delete'=>0])->get();
        
        $edit = LiveClass::where(['id'=>$id])->get();
        $data = Faculties::latest()->get();
        return view('admin/liveclass/edit',array('data'=>$data,'edit'=>$edit,'topics'=>$topics));
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
        'title' =>'required',
        'description'=>'',
        'start_date'=>'required',
        'start_time'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'end_time'=>'required',
        'faculties_id'=>'required',
        'meeting_id'=>'',
        'passcode'=>'',
        'course_id'=>'required',
        'id'=>'required'
        ]);


        $id = $request->input('id');
        if (!empty($image)) {
        //Store Image In Folder
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/liveclasses'), $imageName);
        $data = array(
            'title'=>request('title'),
            'description'=>request('description'),
            'image'=>$imageName,
            'start_date'=>request('start_date'),
            'start_time'=>request('start_time'),
            'end_time'=>request('end_time'),
            'channel_id'=>request('meeting_id'),
            'passcode'=>request('passcode'),
            'faculties_id'=>request('faculties_id'), 
            'course_id'=>request('course_id'), 
        );
          LiveClass::where('id',$id)->update($data);
         return redirect()->route('live_classes.index')
        ->with('success','Record Updated successfully.');
    }
    else{
        $data = array(
            'title'=>request('title'),
            'description'=>request('description'),
            'start_date'=>request('start_date'),
            'start_time'=>request('start_time'),
            'end_time'=>request('end_time'),
            'channel_id'=>request('meeting_id'),
            'passcode'=>request('passcode'),
            'faculties_id'=>request('faculties_id'),  
            'course_id'=>request('course_id'),
        );
          LiveClass::where('id',$id)->update($data);
         return redirect()->route('live_classes.index')
        ->with('success','Record Updated successfully.');
    }
   
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         LiveClass::where('id',$id)->delete();
           return redirect()->route('live_classes.index')
        ->with('success','Record deleted successfully.');
    }
}
