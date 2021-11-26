<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Chapter;
use App\Boards;
use App\Classes;
use App\Subject;
use Illuminate\Support\Facades\DB;

class ChapterController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
        $data= DB::table('chapters')
        ->select('chapters.*','boards.board_name','subjects.title as subject_name')
        ->join('boards','chapters.boards_id','=','boards.id')
        ->join('subjects','chapters.subject_id','=','subjects.id')
        ->orderby('chapters.id','DESC')->get();
       
        return view('admin/chapter/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $board = Boards::latest()->select('id','board_name')->get();
        return view('admin/chapter/add',array('board'=>$board));
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
        'boards_id' =>'required',
        'subject_id' =>'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'chapter_name' =>'required',
        'description' =>'',
        'status'=>'required',

        ]);
        $image = $request->file('image');
         if (!empty($image)) {  
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
      
            request()->image->move(public_path('images/chapter'), $imageName);
        }
        else{
            $imageName = '';
        }
        Chapter::create([
            'boards_id'=>request('boards_id'),
            'subject_id'=>request('subject_id'),
            'chapter_name'=>request('chapter_name'),
            'description'=>request('description'),
            'image'=>$imageName,
            'status'=>request('status'),

        ]);
        return redirect()->route('chapter.create')
        ->with('success','Chapter Added successfully.');
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
         $data= DB::table('chapters')
        ->select('chapters.*','boards.id as board_id','subjects.id as subject_id')
        ->leftjoin('boards','chapters.boards_id','=','boards.id')
        ->leftjoin('subjects','chapters.subject_id','=','subjects.id')
        ->where('chapters.id',$id)
        ->get();
        $board = Boards::latest()->select('id','board_name')->get();
        $subject = Subject::latest()->select('id','title')->get();
        return view('admin/chapter/edit',array('board'=>$board,'subject'=>$subject,'data'=>$data));
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
        'boards_id' =>'required',
        'subject_id' =>'required',
        'chapter_name' =>'required',
        'description' =>'',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        'id'=>'required'
        ]);
       $id = $request->input('id');
        $image = $request->file('image');
         if (!empty($image)) {
        //Store Image In Folder
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/chapter'), $imageName);
         $data = array(
            'boards_id'=>request('boards_id'),
            'subject_id'=>request('subject_id'),
            'image'=>$imageName,
            'chapter_name'=>request('chapter_name'),
            'description'=>request('description'),
            'status'=>request('status'),
         );
         Chapter::where('id',$id)->update($data);
           return redirect()->route('chapter.index')
        ->with('success','Record Updated successfully.');

    }
    else{
         $data = array(
            'boards_id'=>request('boards_id'),
            'subject_id'=>request('subject_id'),
            'chapter_name'=>request('chapter_name'),
            'description'=>request('description'),
            'status'=>request('status'),
         );
         Chapter::where('id',$id)->update($data);
           return redirect()->route('chapter.index')
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
        Chapter::where(['id'=>$id])->delete();
          return redirect()->route('chapter.index')
        ->with('success','Record Deleted successfully.');
    }
}
