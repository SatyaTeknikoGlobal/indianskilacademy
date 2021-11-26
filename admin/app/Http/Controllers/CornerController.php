<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Corner;

class CornerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Corner::latest()->get();
        return view('admin/corner/list', array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin/corner/add');
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
        'images' => 'required',
        ]);

        $images = $request->file('images');
        foreach ($images as $key => $row) {
            $imageName[$key] = time().'_'.$key.'.'.request()->images[$key]->getClientOriginalExtension();
            request()->images[$key]->move(public_path('images/gallery'), $imageName[$key]);
           Corner::create([
            'image'=>$imageName[$key],
            'created_at'=>date('Y-m-d 00:00:00')
           ]);
        }
         return redirect()->route('corner.index')
        ->with('success','Images Uploaded successfully.');
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
         Corner::where('id',$id)->delete();
         return redirect()->route('corner.index')
        ->with('success','Record Delete successfully.');
    }
}
