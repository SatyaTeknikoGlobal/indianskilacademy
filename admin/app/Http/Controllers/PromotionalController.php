<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;
use App\Chapter;
use App\Subject;
use App\Classes;
use App\Boards;
use Illuminate\Support\Facades\DB;

class PromotionalController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data= DB::table('promotional_video')
        ->orderby('id','DESC')
        ->get();
        return view('admin/promotion/list',array('data'=>$data));
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
        return view('admin/promotion/add',array('board'=>$board));
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
           // 'name' =>'required',
            'video_id' =>'required',
            'status'=>'required',
            'cat_id'=>'required',
            
        ]);
        $lastid = DB::table('promotional_video')->insert([
            //'chapter_id'=>request('chapter_id'),
            'name'=>request('name'),
            'video_id'=>request('video_id'),
            'cat_id'=>request('cat_id'),
            'status'=>request('status'),

        ]);




        return redirect()->route('promotionalvideo.index')
        ->with('success','Video Added successfully.');
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
        $data = DB::table('promotional_video')->where(['id'=>$id])->first();
        return view('admin/promotion/edit',array('chapter'=>$chapter,'data'=>$data));
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
           // 'name' =>'required',
            'video_id' =>'required',
            'status'=>'required',
            'id'=>'required',
            'cat_id'=>'required',
        ]);
        $id = request('id');
        $data = array();
        $image = $request->file('image');
          $data = array(
            'name'=>request('name'),
            'cat_id'=>request('cat_id'),
            
            'video_id'=>request('video_id'),
            'status'=>request('status'),
        );
    
      DB::table('promotional_video')->where(['id'=>$id])->update($data);
      return redirect()->route('promotionalvideo.index')
      ->with('success','Video Updated successfully.');

  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('promotional_video')->where('id',$id)->delete();
      return redirect()->route('promotionalvideo.index')
      ->with('success','Record Delete successfully.');
  }
}
