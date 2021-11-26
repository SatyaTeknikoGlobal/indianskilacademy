<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\SubcriptionType;
use App\Boards;


class SubcriptionTypeController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        // $data = SubcriptionType::latest()->get();
        return view('admin/type/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Boards::get();
        return view('admin/type/add',array('class'=>$data));
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
        'type'=>'required',
        'type_id'=>'required',
        'type_id'=>'required',
        'amount'=>'required',
        'original_amount'=>'required',
        'end_date'=>'required',
        'description'=>'required',
        'status'=>'required',
        ]);
        SubcriptionType::create([
            'title'=>request('title'),
            'type'=>request('type'),
            'type_id'=>request('type_id'),
            'start_date'=>request('start_date'),
            'end_date'=>request('end_date'),
            'amount'=>request('original_amount'),
            'new_amount'=>request('amount'),
            'description'=>request('description'),
            'status'=>request('status'),
        ]);
        return redirect()->route('subcription_type.create')
        ->with('success','Record Added successfully.');
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
        $data = SubcriptionType::where(['id'=>$id])->latest()->get();
        $data2 = Boards::get();
        return view('admin/type/edit',array('data'=>$data,'class'=>$data2));
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
            'type'=>'required',
            'type_id'=>'required',
            'amount'=>'required',
            'original_amount'=>'required',
            'description'=>'required',
            'status'=>'required',
            'id'=>'required'
            ]);
          $id = $request->input('id');
          $data = array(
            'title'=>request('title'),
            'type'=>request('type'),
            'start_date'=>request('start_date'),
            'end_date'=>request('end_date'),
            'amount'=>request('original_amount'),
            'new_amount'=>request('amount'),
            'description'=>request('description'),
            'status'=>request('status'), 
          );
          SubcriptionType::where(['id'=>$id])->update($data);
          return redirect()->route('subcription_type.index')
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
         SubcriptionType::where('id',$id)->delete();
         return redirect()->route('subcription_type.index')
        ->with('success','Record Delete successfully.');
    }
}
