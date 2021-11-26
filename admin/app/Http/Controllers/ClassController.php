<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Classes;
class ClassController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Classes::latest()->get();
        return view('admin/class/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/class/add');
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
        'class_name' =>'required',
        ]);
        Classes::create([
            'class_name'=>request('class_name'),
        ]);
        return redirect()->route('class.create')
        ->with('success','Class Added successfully.');
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
        $data = Classes::latest()->where('id',$id)->get();
        return view('admin/class/edit',array('data'=>$data));
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
        'class_name' =>'required',
        'id'=>'required',
        ]);
        $id = $request->input('id');
        $data = array(
            'class_name'=>request('class_name'),
        );
        Classes::where('id',$id)->update($data);
         return redirect()->route('class.index')
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
         Classes::where('id',$id)->delete();
         return redirect()->route('class.index')
        ->with('success','Record Delete successfully.');
    }
}
