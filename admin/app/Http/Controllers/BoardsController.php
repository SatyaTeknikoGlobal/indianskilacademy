<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boards;
use App\User;
use DB;
use Auth;
use Validator;

class BoardsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Boards::where('is_delete',0)->get();
        return view('admin/board/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/board/add');
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
        'board_name' =>'required|unique:boards',
       // 'board_name_hindi' =>'required|unique:boards',
        'status'=>'required',
        //'is_paid'=>'required',
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
    ]);

       // $imageName = time().'.'.request()->image->getClientOriginalExtension();
       // request()->image->move(public_path('images/course'), $imageName);
     Boards::create([
        'board_name'=>request('board_name'),
        'board_name_hindi'=>request('board_name_hindi'),
        'status'=>request('status'),
        'is_paid'=>'N',
        'priority'=>request('priority'),
        'type' => 'course',

        'subscription_amount' => request('subscription_amount'),
        'duration' => request('duration'),
        'image'=>'',
        //'created_by' => Auth::user()->email,
    ]);
     return redirect()->route('course.create')
     ->with('success','Category Added successfully.');
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
        $request->validate([
            'board_name' =>'required',
           // 'board_name_hindi' =>'',

            'status'=>'required',
            'id'=>'required',
           // 'is_paid'=>'required',
           // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);
        $id = $request->input('id');

        $image = $request->file('image');
        if (!empty($image)) {
        //Store Image In Folder
            // $imageName = time().'.'.request()->image->getClientOriginalExtension();
            // request()->image->move(public_path('images/course'), $imageName);
            $data = array(
                'board_name'=>request('board_name'),
                'board_name_hindi'=>request('board_name_hindi'),
                'status'=>request('status'),
                // 'is_paid'=>request('is_paid'),
                'is_paid'=>'N',
                'priority'=>request('priority'),
                
                'image'=>'',
                'type' => 'course',
                'subscription_amount' => request('subscription_amount'),
                'duration' => request('duration'),
                'created_by' =>isset(Auth::user()->email) ? Auth::user()->email :"admin@gmail.com",
            );
        }
        else{
          $data = array(
            'board_name'=>request('board_name'),
            'board_name_hindi'=>request('board_name_hindi'),
            //'is_paid'=>request('is_paid'),
            'is_paid'=>'N',

            'status'=>request('status'),
            'type' => 'course',
            'priority'=>request('priority'),

            'subscription_amount' => request('subscription_amount'),
            'duration' => request('duration'),
            'created_by' =>isset(Auth::user()->email) ? Auth::user()->email :"admin@gmail.com",

        );
      }
      Boards::where('id',$id)->update($data);
      return redirect()->route('course.index')
      ->with('success','Category Updated successfully.');
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     Boards::where('id',$id)->update(['is_delete'=>1]);
     return redirect()->route('course.index')
     ->with('success','Category Delete successfully.');
 }

 public function allocate(Request $request){
    $data = [];

    $method = $request->method();
    if($method == 'post' || $method == 'POST'){
        $rules = [];
        $rules['board_id'] = 'required';
        $rules['user_id'] = 'required';
        $this->validate($request, $rules);
        $board_id = isset($request->board_id) ? $request->board_id : '';
        $user_id = isset($request->user_id) ? $request->user_id : '';
        if(!empty($user_id)){
            for ($i=0; $i < count($user_id) ; $i++) { 
                $dbArray = [];
                $dbArray['user_id'] = $user_id[$i]; 
                $dbArray['board_id'] = $board_id;

                $exist = DB::table('allocate_users')->where('user_id',$user_id[$i])->where('board_id',$board_id)->first();
                // print_r($exist);
                // die;
                if(empty($exist)){
                    $res = DB::table('allocate_users')->insert($dbArray);
                }else{
                    $res = DB::table('allocate_users')->where('id',$exist->id)->update($dbArray);
                }


            }
            if($res){
               return redirect()->to('/allocate-user/'.$board_id)->with('success', 'Allocated successfully');

           }else{
            return redirect()->to('/allocate-user/'.$board_id)->with('error', 'Something went Wrong');
        }
        
    }

}


$user = DB::table('users')->get();
$board_id = isset($request->id) ? $request->id : 0;
$boards = DB::table('boards')->where('id',$board_id)->first();

$sub_history = DB::table('subscription_histories')->get();
$allocates =  DB::table('allocate_users')->get();
$data['allocates'] = $allocates;
$data['sub_history'] = $sub_history;
$data['users'] = $user;
$data['boards'] = $boards;
return view('admin/board/allocate_user',$data);
}

public function allocate_delete(Request $request){

    $id =isset($request->id) ? $request->id : 0;
    $board_id =isset($request->board_id) ? $request->board_id : 0;

    if(!empty($id)){

        $exist = DB::table('allocate_users')->where('id',$id)->first();
        $res = DB::table('allocate_users')->where('id',$id)->delete();
        if($res){
         return redirect()->route('course.allocate', $board_id)
         ->with('success','Deleted successfully.');
     }else{
      return redirect()->route('course.allocate', $board_id)
      ->with('error','Something went Wrong.');
  }
}   

}

}
