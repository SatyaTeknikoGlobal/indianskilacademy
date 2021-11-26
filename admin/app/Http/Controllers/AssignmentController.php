<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boards;
use App\Assignment;
use App\User;
use App\AppUser;
use DB;
use Auth;
use Yajra\DataTables\DataTables;
use Validator;

class AssignmentController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Assignment::latest()->get();
        $board = Boards::where('status','Y')->get();
        return view('admin/assignment/list',array('data'=>$data,'board'=>$board));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $board = Boards::where('status','Y')->get();
        return view('admin/assignment/add',array('board'=>$board));
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
        'course_id' =>'required',
        'subcourse_id'=>'required',
        'topic_id'=>'required',
        'status'=>'required',
        'title'=>'required',
        'pdf'=>'required|mimes:pdf',
    ]);

       $imageName = time().'.'.request()->pdf->getClientOriginalExtension();
       request()->pdf->move(public_path('assignment/admin'), $imageName);
       Assignment::create([
        'course_id'=>request('course_id'),
        'subcourse_id'=>request('subcourse_id'),
        'topic_id'=>request('topic_id'),
        'status'=>request('status'),
        'title' => request('title'),
        'pdf'=>$imageName,
        'date'=>date('Y-m-d'),
        
    ]);
       return redirect()->route('assignment.create')
       ->with('success','Assignment Added successfully.');
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
        $data = Boards::latest()->where('id',$id)->get();
        return view('admin/board/edit',array('data'=>$data));
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
      //   $request->validate([
      //       'board_name' =>'required',
      //      // 'board_name_hindi' =>'',

      //       'status'=>'required',
      //       'id'=>'required',
      //       'is_paid'=>'required',
      //       'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
      //   ]);
      //   $id = $request->input('id');

      //   $image = $request->file('image');
      //   if (!empty($image)) {
      //   //Store Image In Folder
      //       $imageName = time().'.'.request()->image->getClientOriginalExtension();
      //       request()->image->move(public_path('images/course'), $imageName);
      //       $data = array(
      //           'board_name'=>request('board_name'),
      //           'board_name_hindi'=>request('board_name_hindi'),
      //           'status'=>request('status'),
      //           'is_paid'=>request('is_paid'),
      //           'image'=>$imageName,
      //           'type' => request('type'),
      //           'subscription_amount' => request('subscription_amount'),
      //           'duration' => request('duration'),
      //           'created_by' =>isset(Auth::user()->email) ? Auth::user()->email :"admin@gmail.com",
      //       );
      //   }
      //   else{
      //     $data = array(
      //       'board_name'=>request('board_name'),
      //       'board_name_hindi'=>request('board_name_hindi'),
      //       'is_paid'=>request('is_paid'),
      //       'status'=>request('status'),
      //       'type' => request('type'),
      //       'subscription_amount' => request('subscription_amount'),
      //       'duration' => request('duration'),
      //       'created_by' =>isset(Auth::user()->email) ? Auth::user()->email :"admin@gmail.com",

      //   );
      // }
      // Boards::where('id',$id)->update($data);
      // return redirect()->route('course.index')
      // ->with('success','Record Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
       Assignment::where('id',$id)->delete();
       return redirect()->route('assignment.index')
       ->with('success','Record Delete successfully.');
   }


   public function result(Request $request){
    $assignment_id = isset($request->id) ? $request->id :'';
        // if(!empty($assignment_id)){

        //         $assignment_results = DB::table('assignment_result')->where('assignment_id',$assignment_id)->get();
        //         // if(!empty($assignment_results)){
        //         //     foreach($assignment_results as $res){

        //         //     }
        //         // }



        // }

    return view('admin/assignment/result',array('assignment_id'=>$assignment_id));



}

public function get_assignment_users(Request $request){
    $assignment_id = isset($request->assignment_id) ? $request->assignment_id :'';

    $data= DB::table('assignment_result')->where('assignment_id',$assignment_id)->get();
    if(!empty($data)){
        foreach($data as $d){
            $user = AppUser::where('id',$d->user_id)->first();
            $d->name = $user->name;
            $d->email = $user->email;

            $d->pdf = env('APP_URL').('public/assignment/'.$d->pdf);

        }
    }


    return Datatables::of($data)
    ->filter(function ($instance) use ($request) {

    })
    ->make(true);
}


}
