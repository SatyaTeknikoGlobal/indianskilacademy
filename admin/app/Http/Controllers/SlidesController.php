<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Slides;
use App\Content;

class SlidesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
       $data = Slides::latest()->get();
        return view('admin/slides/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Content::where(['type'=>'video'])->latest()->get();
        return view('admin/slides/add',array('data'=>$data));
    }

    /**
     * Store a newly created resource in storage.
     *
     * @param  \Illuminate\Http\Request  $request
     * @return \Illuminate\Http\Response
     */
    public function store(Request $request)
    {
        $this->validate($request, [
        'name' => 'required',
        'image'=>'required',
        'content_id'=>'required',
        ]);
        if($request->hasFile('image'))
        {
        foreach ($request->file('image') as $file) {
                $name = $file->getClientOriginalName();
                $file->move(public_path('images/slides'),$name);   
        Slides::create([
        'content_id' => $request->content_id,
        'name'=>$request->name,
        'slides' => $name,
        ]);
        }
        }
          return redirect()->route('content.index')
        ->with('success','Slides Added successfully.');
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
        Slides::where('id',$id)->delete();
         return redirect()->route('slides.index')
        ->with('success','Record Delete successfully.');
    }
}
