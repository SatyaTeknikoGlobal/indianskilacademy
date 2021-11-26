<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faculties;
use App\LiveClass;
use App\User;
use App\Classes;
use App\Boards;
use App\Subject;

class FacultiesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Faculties::latest()->get();
        return view('admin/faculties/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/faculties/add');
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
        'name' =>'required',
        'email'=>'required|email',
        'phone' => 'required|digits:10',
        //'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg,pdf|max:8192',
        'image' => 'required|mimes:pdf|max:10000',
       // 'education'=>'required',
        'password'=>'required',
      //  'speciality'=>'required',
       // 'dob'=>'required',
//'total_exp'=>'required',
       /// 'college_name'=>'required',
       // 'college_location'=>'required',
    ]);
     $imageName = time().'.'.request()->image->getClientOriginalExtension();
     request()->image->move(public_path('images/faculties'), $imageName);
    /* $about= '';
     if(!empty($request->about)){
         $about = time().'.'.request()->about->getClientOriginalExtension();
         request()->about->move(public_path('images/faculties/about/'), $about);
     }
*/
     $faculties = Faculties::create([
        'name'=>request('name'),
        'email'=>request('email'),
        'image'=>$imageName,
        'phone'=>request('phone'),
        'education'=>request('education'),
        'speciality'=>request('speciality'),
        'dob'=>request('dob'),
        'total_exp'=>request('total_exp'),
        /*'about'=>$about,*/
        'password'=>bcrypt(request('password')),
        'college_name'=>request('college_name'),
        'college_location'=>request('college_location'),   
    ]);
     User::create([
         'name'=>request('name'),
         'email'=>request('email'),
         'password'=>bcrypt(request('password')),
         'phone'=>request('phone'),
         'is_admin'=>2,
         'faculties_id'=>$faculties->id,
     ]);
     return redirect()->route('faculties.index')
     ->with('success','Faculties Added successfully.');
 }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {


        return view('admin/faculties/show',array('id'=>$id));
    }

    public function view($id)
    {

        $class = Classes::get();
        $boards = Boards::where(['status'=>'Y'])->get();
        $data = Faculties::latest()->where('id',$id)->get();
        $live = LiveClass::latest()->where('faculties_id',$id);
        return view('admin/faculties/view',array('data'=>$data,'live'=>$live,'class'=>$class,'boards'=>$boards));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
        $data = Faculties::latest()->where('id',$id)->get();
        return view('admin/faculties/edit',array('data'=>$data));
    }

    /**
     * Update the specified resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function allocate_live_class(Request $request)
    {
        $request->validate([
            'title' =>'required',
            'description'=>'required',
            'start_date'=>'required',
            'start_time'=>'required',
            'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
            'end_time'=>'required',
            'id'=>'required'
        ]);
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/liveclasses'), $imageName);
        LiveClass::create([
            'title'=>request('title'),
            'description'=>request('description'),
            'phone'=>request('phone'),
            'image'=>$imageName,
            'start_date'=>request('start_date'),
            'start_time'=>request('start_time'),
            'end_time'=>request('end_time'),
            'faculties_id'=>request('id'),   
        ]);
        return redirect()->route('faculties.index')
        ->with('success','Live Class Allocate successfully.');
    }
    public function update(Request $request)
    {
        $request->validate([
            'name' =>'required',
            'email'=>'required|email|unique:users',
            'phone' => 'required|digits:10',
           /* 'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',*/
            'image' => 'required|mimes:pdf|max:10000',
            //'education'=>'required',
            //'dob'=>'required',
            //'total_exp'=>'required',
           // 'speciality'=>'required',
           // 'college_name'=>'required',
            //'college_location'=>'required',
            'id'=>'required'
        ]);
        $image = $request->file('image');
        $id = $request->input('id');
        $faculties = Faculties::where('id',$id)->first();
        /*$about= isset($faculties->about) ? $faculties->about :'';
        if(!empty($request->about)){
         $about = time().'.'.request()->about->getClientOriginalExtension();
         request()->about->move(public_path('images/faculties/about/'), $about);
     }*/





     if (!empty($image)) {
        //Store Image In Folder
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/faculties'), $imageName);
        $data = array(
            'name'=>request('name'),
            'email'=>request('email'),
            'image'=>$imageName,
            /*'about'=>$about,*/
            'phone'=>request('phone'),
            'education'=>request('education'),
            'speciality'=>request('speciality'),
            'dob'=>request('dob'),
            'total_exp'=>request('total_exp'),
            'college_name'=>request('college_name'),
            'college_location'=>request('college_location'),   
        );
        Faculties::where('id',$id)->update($data);
        return redirect()->route('faculties.index')
        ->with('success','Record Updated successfully.');

    }
    else{
     $data = array(
        'name'=>request('name'),
        'email'=>request('email'),
        'phone'=>request('phone'),
        /*'about'=>$about,*/
        'education'=>request('education'),
        'speciality'=>request('speciality'),
        'college_name'=>request('college_name'),
        'college_location'=>request('college_location'),   
    );
     Faculties::where('id',$id)->update($data);
     User::where('id',$id)->update([
        'name'=>request('name'),
        'email'=>request('email'),
        'password'=>bcrypt(request('phone')),
    ]);
     return redirect()->route('faculties.index')
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
     Faculties::where('id',$id)->delete();
     return redirect()->route('faculties.index')
     ->with('success','Record Delete successfully.');
 }
}
