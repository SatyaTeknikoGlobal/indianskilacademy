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

class WeeklyController extends Controller
{

    public function index()
    {
        $data= DB::table('weekmonthpdf')->where('status',1)->get();
        $users = DB::table('users')->get();

        return view('admin/weekly/list',array('data'=>$data,'users'=>$users));
    }


    public function create()
    {
        //$class = Classes::latest()->select('id','class_name')->get();
        $users = DB::table('users')->get();
        return view('admin/weekly/add',array('users'=>$users));
    }


    public function store(Request $request)
    {
        $request->validate([

            'title' =>'required',
            'pdf' =>'required',
            'date' =>'required',
            'status' =>'required',

        ]);


        $imageName ='';

        if($request->file('pdf')){
            $imageName = time().'.'.request()->pdf->getClientOriginalExtension();
            request()->pdf->move(public_path('images/monthlypdf'), $imageName);
        }

        DB::table('weekmonthpdf')->insert([
            'title'=>request('title'),
            'date'=>request('date'),
            'pdf'=>$imageName,
            'status'=>request('status'),
        ]);
        return redirect()->route('monthweekpdf.index')
        ->with('success','PDF Added successfully.');
    }


    public function show($id)
    {
        //
    }
    public function edit($id)
    {
        $users = DB::table('users')->get();
        $data = DB::table('weekmonthpdf')->where(['id'=>$id])->first();
        return view('admin/weekly/edit',array('users'=>$users,'data'=>$data));
    }


    public function update(Request $request)
    {


        // print_r($request->toArray());

        // die;
        $request->validate([
            'title' =>'required',
            //'pdf' =>'required',
            'date' =>'required',
            'status' =>'required',

        ]);
        $id = request('id');
        $data = array();
        if($request->file('pdf')){
            $imageName = time().'.'.request()->pdf->getClientOriginalExtension();
            request()->pdf->move(public_path('images/monthlypdf'), $imageName);
            $data = array(
              'title'=>request('title'),
            'date'=>request('date'),
            'pdf'=>$imageName,
            'status'=>request('status'),


         );
        }else{
            $data = array(
              'title'=>request('title'),
            'date'=>request('date'),
            'status'=>request('status'),
         );
        }




        $test = DB::table('weekmonthpdf')->where(['id'=>$id])->update($data);

        return redirect()->route('monthweekpdf.index')
        ->with('success','PDF Updated successfully.');

    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
      DB::table('weekmonthpdf')->where('id',$id)->delete();
      return redirect()->route('monthweekpdf.index')
      ->with('success','Record Delete successfully.');
  }
}
