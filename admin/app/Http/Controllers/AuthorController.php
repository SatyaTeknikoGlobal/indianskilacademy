<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Author;


class AuthorController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

        $data = Author::get();
        return view('admin/author/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin/author/add');
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
        'about'=>'',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        ]);
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/author'), $imageName);
        Author::create([
            'name'=>request('name'),
            'about'=>request('about'),
            'profile'=>$imageName,
            'status'=>request('status'),   
        ]);
        return redirect()->route('author.index')
        ->with('success','Author Added successfully.');
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
        $data = Author::where(['id'=>$id])->get();
         return view('admin/author/edit',array('data'=>$data));
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
        'about'=>'',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        ]);
         $image = $request->file('image');
        $data =array();
        if (!empty($image)) {
        //Store Image In Folder
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/author'), $imageName);
        $data = array(
            'name'=>request('name'),
            'about'=>request('about'),
            'profile'=>$imageName,
            'status'=>request('status'),   
        );
        }
        else{
             $data = array(
                 'name'=>request('name'),
                 'about'=>request('about'),
                 'status'=>request('status'),  
                ); 
        }

        Author::where(['id'=>$id])->update($data);
        return redirect()->route('author.index')
        ->with('success','Author Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Author::find($id)->delete();
        return redirect()->route('author.index')
        ->with('success','Author Deleted successfully.');
    }
}
