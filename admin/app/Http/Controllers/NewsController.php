<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Boards;
use App\User;
use App\News;


use DB;
use Validator;
use Auth;

class NewsController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = News::latest()->get();
        return view('admin/news/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/news/add');
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
        // 'board_name' =>'required|unique:boards',
        // 'board_name_hindi' =>'required|unique:boards',
        // 'status'=>'required',
        // 'is_paid'=>'required',
        // 'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
     ]);
     if(!empty($request->file('image'))){
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
         request()->image->move(public_path('images/news'), $imageName);
     }else{
        $imageName = request('image');
    }




    News::create([
        'title'=>request('title'),
        'description'=>request('description'),
        'status'=>request('status'),
        'image'=>$imageName,
        'type'=> request('type'),
        'short_description'=> request('short_description'),
        'tags'=> request('tags'),
        'date'=> request('date'),
        'link'=> request('link'),
        'created_by' => Auth::user()->email,
    ]);


    return redirect()->route('news.create')
    ->with('success','News Added successfully.');
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
        $data = News::latest()->where('id',$id)->get();
        return view('admin/news/edit',array('data'=>$data));
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
            // 'board_name' =>'required',
            // 'board_name_hindi' =>'',

            // 'status'=>'required',
            // 'id'=>'required',
            // 'is_paid'=>'required',
            // 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        ]);
        $id = $request->input('id');

        $image = $request->file('image');


        if (!empty($image)) {
            
            $imageName = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/news'), $imageName);
            $data = array(
                'title'=>request('title'),
                'description'=>request('description'),
                'status'=>request('status'),
                'image'=>$imageName,
                'type'=>request('type'),
                'tags'=>request('tags'),
        'link'=> request('link'),

                'short_description'=> request('short_description'),
                'created_by' => Auth::user()->email,
            );
        }

        else{
          $data = array(
            'title'=>request('title'),
            'description'=>request('description'),
            'status'=>request('status'),
            'type'=>request('type'),
            'link'=> request('link'),
            'short_description'=> request('short_description'),
            'tags'=>request('tags'),
            'created_by' => Auth::user()->email,
        );
      }



      News::where('id',$id)->update($data);
      //dd(DB::getQueryLog()); // Show results of log

      return redirect()->route('news.index')
      ->with('success','Record Updated successfully.');
  }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
     News::where('id',$id)->delete();
     DB::table('news_bookmark')->where('news_id',$id)->delete();
     return redirect()->route('news.index')
     ->with('success','Record Delete successfully.');
 }

//    public function allocate(Request $request){
//     $data = [];

//     $method = $request->method();
//     if($method == 'post' || $method == 'POST'){
//         $rules = [];
//         $rules['board_id'] = 'required';
//         $rules['user_id'] = 'required';
//         $this->validate($request, $rules);
//         $board_id = isset($request->board_id) ? $request->board_id : '';
//         $user_id = isset($request->user_id) ? $request->user_id : '';
//         if(!empty($user_id)){
//             for ($i=0; $i < count($user_id) ; $i++) { 
//                 $dbArray = [];
//                 $dbArray['user_id'] = $user_id[$i]; 
//                 $dbArray['board_id'] = $board_id;

//                 $exist = DB::table('allocate_users')->where('user_id',$user_id[$i])->where('board_id',$board_id)->first();
//                 // print_r($exist);
//                 // die;
//                 if(empty($exist)){
//                     $res = DB::table('allocate_users')->insert($dbArray);
//                 }else{
//                     $res = DB::table('allocate_users')->where('id',$exist->id)->update($dbArray);
//                 }


//             }
//             if($res){
//              return redirect()->to('/allocate-user/'.$board_id)->with('success', 'Allocated successfully');

//          }else{
//             return redirect()->to('/allocate-user/'.$board_id)->with('error', 'Something went Wrong');
//         }

//     }

// }


// $user = DB::table('users')->get();
// $board_id = isset($request->id) ? $request->id : 0;
// $boards = DB::table('boards')->where('id',$board_id)->first();

// $sub_history = DB::table('subscription_histories')->get();
// $allocates =  DB::table('allocate_users')->get();
// $data['allocates'] = $allocates;
// $data['sub_history'] = $sub_history;
// $data['users'] = $user;
// $data['boards'] = $boards;
// return view('admin/board/allocate_user',$data);
// }

// public function allocate_delete(Request $request){

//     $id =isset($request->id) ? $request->id : 0;
//     $board_id =isset($request->board_id) ? $request->board_id : 0;

//     if(!empty($id)){

//         $exist = DB::table('allocate_users')->where('id',$id)->first();
//         $res = DB::table('allocate_users')->where('id',$id)->delete();
//         if($res){
//            return redirect()->route('course.allocate', $board_id)
//            ->with('success','Deleted successfully.');
//        }else{
//           return redirect()->route('course.allocate', $board_id)
//           ->with('error','Something went Wrong.');
//       }
//   }   

// }

}
