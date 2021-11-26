<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;
use App\Chapter;
use App\Subject;
use App\Classes;
use App\Boards;
use App\Content;
use Illuminate\Support\Facades\DB;

class TopicController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= DB::table('topics')
        ->select('topics.*','chapters.chapter_name','subjects.title','boards.board_name')
        ->leftjoin('chapters','topics.chapter_id','=','chapters.id')
        ->leftjoin('boards','topics.course_id','=','boards.id')
        ->leftjoin('subjects','topics.subject_id','=','subjects.id')
        ->where('topics.is_delete',0)
        ->get();
        return view('admin/topic/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        //$class = Classes::latest()->select('id','class_name')->get();
        $board = Boards::latest()->select('id','board_name')->get();
        return view('admin/topic/add',array('board'=>$board));
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
        //'chapter_id' =>'required',
            'course_id' =>'required',
            'subject_id' =>'required',
            'name' =>'required',
            'name_hindi' =>'',
            'status'=>'required',
           /* 'start_date'=>'required',*/
            'subscription_amount'=>'required',
            'duration'=>'required',
            /*'end_date'=>'required',*/
            'batch_status'=>'required',
            'batch_duration'=>'required',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);

        $offerimageName = '';
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/topic'), $imageName);

        if(!empty($request->offer_banner)){
          $offerimageName = time().'.'.request()->offer_banner->getClientOriginalExtension();
          request()->offer_banner->move(public_path('images/topic'), $offerimageName);
      }


      $lastid = Topic::create([
            //'chapter_id'=>request('chapter_id'),
        'course_id'=>request('course_id'),
        'subject_id'=>request('subject_id'),
        'name'=>request('name'),
        'name_hindi'=>request('name_hindi'),
        'is_paid'=>request('status'),
        'description'=>request('description'),
        'duration'=>request('duration'),
        'subscription_amount'=>request('subscription_amount'),
        'start_date'=>request('start_date'),
        'end_date'=>request('end_date'),
        'image'=>$imageName,
        'offer_banner'=>$offerimageName,
        'is_offer'=>request('is_offer'),
        'batch_status'=>request('batch_status'),
        'batch_duration'=>request('batch_duration'),
        'hls'=>request('hls'),
        'notify_text'=>request('notify_text'),

    ])->id;

      DB::table('chat_group')->insert(array('program_id'=>$lastid));



      return redirect()->route('topic.index')
      ->with('success','Topic Added successfully.');
  }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        //
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $chapter = Chapter::latest()->select('id','chapter_name')->get();
        $data = Topic::where(['id'=>$id])->first();
        return view('admin/topic/edit',array('chapter'=>$chapter,'data'=>$data));
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
        //'chapter_id' =>'required',
            'name' =>'required',
            'name_hindi' =>'',
            'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'status'=>'required',
            'id'=>'required',
            'duration'=>'required',
            /*'start_date'=>'required',
            'end_date'=>'required',*/
             'batch_status'=>'required',
            'batch_duration'=>'required',
            'subscription_amount'=>'required',
            'duration'=>'required',

            
        ]);
        $id = request('id');
        $data = array();
        $image = $request->file('image');

        $topic = Topic::where(['id'=>$id])->first();



        $offerimageName =isset($topic->offer_banner) ? $topic->offer_banner :'';

        if(!empty($request->offer_banner)){
          $offerimageName = time().'.'.request()->offer_banner->getClientOriginalExtension();
          request()->offer_banner->move(public_path('images/topic'), $offerimageName);
      }




      if (!empty($image)) {
        //Store Image In Folder
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/topic'), $imageName);
        $data = array(
            //'chapter_id'=>request('chapter_id'),
            'name'=>request('name'),
            'name_hindi'=>request('name_hindi'),
            'is_paid'=>request('status'),
            "image"=>$imageName,
            "offer_banner"=>$offerimageName,
            "is_offer"=>request('is_offer'),
            'description'=>request('description'),
            'duration'=>request('duration'),
            'subscription_amount'=>request('subscription_amount'),
            'hls'=>request('hls'),
            'start_date'=>request('start_date'),
            'end_date'=>request('end_date'),
             'batch_status'=>request('batch_status'),
            'batch_duration'=>request('batch_duration'),
        'notify_text'=>request('notify_text'),



        );

    }
    else {
      $data = array(
            //'chapter_id'=>request('chapter_id'),
        'name'=>request('name'),
        'name_hindi'=>request('name_hindi'),
        'is_paid'=>request('status'),
        'description'=>request('description'),
        'duration'=>request('duration'),
        "offer_banner"=>$offerimageName,
        "is_offer"=>request('is_offer'),
        'subscription_amount'=>request('subscription_amount'),
        'hls'=>request('hls'),
        'start_date'=>request('start_date'),
        'end_date'=>request('end_date'),
        'batch_status'=>request('batch_status'),
          'batch_duration'=>request('batch_duration'),
        'notify_text'=>request('notify_text'),

        

    );
  }
       // print_r($data);die();
  Topic::where(['id'=>$id])->update($data);
  return redirect()->route('topic.index')
  ->with('success','Topic Updated successfully.');

}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Topic::where('id',$id)->update(['is_delete'=>1]);
      return redirect()->route('topic.index')
      ->with('success','Record Delete successfully.');
  }


  public function contents(Request $request){
    $data =[];

    $topic_id = isset($request->topic_id) ? $request->topic_id :'';

    $notes = Content::where('topic_id',$topic_id)->where('type','notes')->get();
    $video = Content::where('topic_id',$topic_id)->where('type','video')->get();


    $topic = Topic::where('id',$topic_id)->first();
    $subject = '';
    if(!empty($topic->subject_id)){
        $subject = Subject::where('id',$topic->subject_id)->first();

    }




    $data['video'] = $video;
    $data['topic_id'] = $topic_id;
    $data['topic_name'] = $topic->name ?? '';
    $data['subject_name'] = $subject->title ?? '';
    $data['notes'] = $notes;

    return view('admin/topic/notevideolist',$data);
}















}
