<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Instructions;
use Illuminate\Support\Str;

class InstractionController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Instructions::latest()->get();
        return view('admin/instraction/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin/instraction/add');
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
        'title' =>'required',
        'e_content'=>'required',
        'h_content'=>'required',
        ]);
        Instructions::create([
            'title'=>request('title'),
            'e_content'=>request('e_content'),
            'slug'=>Str::slug($request->title),
            'h_content'=>request('h_content'),
        ]);
        return redirect()->route('instraction.index')
        ->with('success','Instraction Added successfully.');
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
        $data = Instructions::where(['id'=>$id])->first();
        return view('admin/instraction/edit',array('data'=>$data));
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
        'title' =>'required',
        'e_content'=>'required',
        'h_content'=>'required',
        'id'=>'required'
        ]);
        $id = $request->input('id');
        $data =array(
            'title'=>request('title'),
            'e_content'=>request('e_content'),
            'slug'=>Str::slug($request->title),
            'h_content'=>request('h_content'),
        );
        Instructions::where(['id'=>$id])->update($data);
        return redirect()->route('instraction.index')
        ->with('success','Instraction Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Instructions::where('id',$id)->delete();
         return redirect()->route('instraction.index')
        ->with('success','Instractions Delete successfully.');
    }
}
