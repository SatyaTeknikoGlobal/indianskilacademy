<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\BookCategory;
use App\Author;
use App\Publisher;
use App\FeedbackCourse;
use App\FeedbackContent;
use Yajra\DataTables\DataTables;


class FeedbackController extends Controller
{/**

         * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
   
public function index()
{
    $data = FeedbackCourse::where('status',1)->get();
    return view('admin/feedback/list',array('data'=>$data));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
     $category = BookCategory::where('status',1)->get();

     return view('admin/feedback/add',array('category'=>$category));
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
            'status' =>'required',
            'file_name' => 'required',
        ]);
        $imageName = time().'.'.request()->file_name->getClientOriginalExtension();
        request()->file_name->move(public_path('images/feedbackcourse'), $imageName);
        FeedbackCourse::create([
            'title'=>request('title'),
            'file_name'=>$imageName,
            'status'=>request('status'),

        ]);
        return redirect()->route('feedback.index')
        ->with('success','FeedbackCourse Added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = FeedbackCourse::where(['id'=>$id])->first();
        return view('admin/feedback/show',array('data'=>$data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
     $data = FeedbackCourse::where(['id'=>$id])->first();
     return view('admin/feedback/edit',array('data'=>$data));
 }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function update(Request $request, $id)
    {


        $request->validate([
           'title' =>'required',
            'status' =>'required',
        ]);


        if (!empty($request->file_name)) {
        //Store Image In Folder
            $imageName = time().'.'.request()->file_name->getClientOriginalExtension();
            request()->file_name->move(public_path('images/feedbackcourse'), $imageName);
            $data = array(
                'title'=>request('title'),
                'file_name'=>$imageName,
                'status'=>request('status'),
            );
        }
        else{
          $data = array(
                'title'=>request('title'),

            'status'=>request('status'),
        );

      }
      FeedbackCourse::where(['id'=>$id])->update($data);
      return redirect()->route('feedback.index')
      ->with('success','FeedbackCourse Updated successfully.');
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        FeedbackCourse::find($id)->delete();
        return redirect()->route('feedback.index')
        ->with('success','FeedbackCourse deleted successfully.');
    }


    public function contents(Request $request){
        $id = isset($request->id) ? $request->id :'';
        $video = FeedbackContent::where(['course_id'=>$id,'type'=>'video'])->get();
        $notes = FeedbackContent::where(['course_id'=>$id,'type'=>'notes'])->get();
        $course = FeedbackCourse::where('id',$id)->first();

        return view('admin/feedback/contents',array('video'=>$video,'course'=>$course,'notes'=>$notes,'course_id'=>$id));



    }




    public function content_delete(Request $request)
    {
        $content_id = isset($request->content_id) ? $request->content_id :'';

        $content = FeedbackContent::where('id',$content_id)->first();
        FeedbackContent::find($content_id)->delete();
        return redirect()->route('feedback.contents',$content->course_id)
        ->with('success','FeedbackCourse COntent deleted successfully.');
    }


public function contents_save(Request $request){



   // die();
    $request->validate([
            'course_id'=>'required|not_in:0',
            'title' => 'required',
            'hls_type' => 'required',
            'hls' => 'required',
        ]);

        $course = FeedbackCourse::where('id',$request->course_id)->first();
        $hls_type = $request->input('hls_type');
        if($hls_type == 'youtube'){
            $type ='video';
        }else if($hls_type == 'local'){
            $type ='notes';
        }





         if($type == 'notes'){

            $file = $request->file('hls');
            $content_name = time() . 'contents.' . $file->getClientOriginalExtension();
            $request->file('hls')->move(public_path('feedbackcourse/notes'), $content_name);
         }else{
            $content_name = request('hls');
        }

        $insert_content = FeedbackContent::create([
            'title'=>$request->title,
            'course_id'=>$request->course_id,
            'file_name'=>$content_name,
            'type'=>$type,
            'status'=>1,
        ]);

         return redirect()->route('feedback.contents',$request->course_id)
        ->with('success','Content Added successfully.');





}






}
