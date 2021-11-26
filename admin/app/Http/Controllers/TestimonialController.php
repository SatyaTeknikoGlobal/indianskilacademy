<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Topic;
use App\Chapter;
use App\Subject;
use App\Classes;
use App\Boards;
use App\User;
use App\Testimonial;
use Illuminate\Support\Facades\DB;

class TestimonialController extends Controller
{

    public function index()
    {
        $data= Testimonial::get();
        $users = DB::table('users')->get();

        return view('admin/testimonial/list',array('data'=>$data,'users'=>$users));
    }


    public function create()
    {
        //$class = Classes::latest()->select('id','class_name')->get();
        $users = DB::table('users')->get();
        return view('admin/testimonial/add',array('users'=>$users));
    }


    public function store(Request $request)
    {
        $request->validate([

            //'text' =>'required',
            //'user_name' =>'required',
           // 'status' =>'required',

        ]);


        

        if($request->file('image')){
            $imageName = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/testimonial'), $imageName);
            Testimonial::create([
                'text'=>request('text'),
                'user_name'=>request('user_name'),
                'image'=>$imageName,
                'status'=>request('status'),
            ]);
        }else{
            Testimonial::create([
                'text'=>request('text'),
                'user_name'=>request('user_name'),
                'status'=>request('status'),
            ]);
        }

        
        return redirect()->route('testimonials.index')
        ->with('success','Testimonial Added successfully.');
    }


    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $users = DB::table('users')->get();
        $data = Testimonial::where(['id'=>$id])->first();
        return view('admin/testimonial/edit',array('users'=>$users,'data'=>$data));
    }


    public function update(Request $request)
    {


        // print_r($request->toArray());

        // die;
        $request->validate([
            // 'text' =>'required',
            // 'user_name' =>'required',
            // 'status' =>'required',
        ]);
        $id = request('id');
        $data = array();
        if($request->file('image')){
            $imageName = time().'.'.request()->image->getClientOriginalExtension();
            request()->image->move(public_path('images/testimonial'), $imageName);
            $data = array(
               'text'=>request('text'),
               'user_name'=>request('user_name'),
               'image'=>$imageName,
               'status'=>request('status'),


           );
        }else{
            $data = array(
               'text'=>request('text'),
               'user_name'=>request('user_name'),
               'status'=>request('status'),
           );
        }




        $test = Testimonial::where(['id'=>$id])->update($data);

        return redirect()->route('testimonials.index')
        ->with('success','Testimonial Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      Testimonial::where('id',$id)->delete();
      return redirect()->route('testimonials.index')
      ->with('success','Record Delete successfully.');
  }
}
