<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use Illuminate\Support\Facades\DB;
use App\Subject;
use App\Topic;

class SubcriptionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        
       // DB::enableQueryLog(); // Enable query log

       $data= DB::table('subscription_histories')
        ->select('subscription_histories.*','subscription_types.title as type_title','users.name','users.phone','users.email')
        ->leftjoin('subscription_types','subscription_histories.subs_sub_type_id','=','subscription_types.type_id')
        ->leftjoin('users','subscription_histories.user_id','=','users.id')
        ->get();


//dd(DB::getQueryLog()); // Show results of log




       return  view('admin/subcription/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create(Request $request)
    {


        //
        //DB::enableQueryLog(); // Enable query log

        $data = [];
        $course = isset($request->course) ? $request->course:'';
        $subject_id = isset($request->subject_id) ? $request->subject_id:'';
        $topic_id = isset($request->topic_id) ? $request->topic_id:'';
        $detail = [];
        // if(!empty($course)){
        $detail =DB::table('subscription_histories')
        ->select('subscription_histories.*','users.name','users.phone','users.email')
        // ->leftjoin('subscription_types','subscription_histories.subs_sub_type_id','=','subscription_types.type_id')
        ->leftjoin('users','subscription_histories.user_id','=','users.id')
        ->orderby('id','desc');

        if(!empty($course)){
           $detail->where('subscription_histories.board_id','=',$course);
        }


         if(!empty($subject_id) && $subject_id !=0){
           $detail->where('subscription_histories.subject_id','=',$subject_id);
        }

         if(!empty($topic_id)  && $topic_id !=0){
           $detail->where('subscription_histories.topic_id','=',$topic_id);
        }

        $detail = $detail->paginate(10);
    // }
        $data['boards'] = DB::table('boards')->get();
        $data['data'] = $detail;

        if(!empty($course)){
            $data['courses'] = Subject::where('board_id',$course)->get();
        }
        if(!empty($subject_id)){
            $data['batches'] = Topic::where('subject_id',$subject_id)->get();

        }



        return  view('admin/subcription/report',$data);
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        //
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
        //
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
        //
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        //
    }
}
