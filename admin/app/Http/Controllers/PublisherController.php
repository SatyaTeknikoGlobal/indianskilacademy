<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Publisher;



class PublisherController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Publisher::get();
        return view('admin/publisher/list', array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/publisher/add');
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
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        ]);
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/publisher'), $imageName);
        Publisher::create([
            'name'=>request('name'),
            'image'=>$imageName,
            'status'=>request('status'),   
        ]);
        return redirect()->route('publisher.index')
        ->with('success','Publisher Added successfully.');
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
        $data = Publisher::where(['id'=>$id])->first();
        return view('admin/publisher/edit',array('data'=>$data));
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
      $request->validate([
        'name' =>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        ]);
         $image = $request->file('image');
        $data =array();
        if (!empty($image)) {
        //Store Image In Folder
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/publisher'), $imageName);
        $data = array(
            'name'=>request('name'),
            'image'=>$imageName,
            'status'=>request('status'),   
        );
        }
        else{
             $data = array(
                 'name'=>request('name'),
                 'status'=>request('status'),  
                ); 
        }

        Publisher::where(['id'=>$id])->update($data);
        return redirect()->route('publisher.index')
        ->with('success','Publisher Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         Publisher::find($id)->delete();
        return redirect()->route('publisher.index')
        ->with('success','Publisher Deleted successfully.');
    }
}
