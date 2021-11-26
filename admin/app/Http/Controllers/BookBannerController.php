<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookBanner;

class BookBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BookBanner::latest()->get();
        return view('admin/bookbanner/list', array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/bookbanner/add');
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
        'author_name'=>'required',
        ]);
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/bookbanner'), $imageName);
        BookBanner::create([
            'name'=>request('name'),
            'banner'=>$imageName,
            'status'=>request('status'),   
            'author_name'=>request('author_name'),   
            'description'=>request('description'),   
        ]);
        return redirect()->route('book_banner.index')
         ->with('success','Banners Added successfully.');
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
         $data = BookBanner::where(['id'=>$id])->first();
        return view('admin/bookbanner/edit',array('data'=>$data));
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
        request()->image->move(public_path('images/bookbanner'), $imageName);
        $data = array(
            'name'=>request('name'),
            'banner'=>$imageName,
            'status'=>request('status'),   
        );
        }
        else{
         $data = array(
             'name'=>request('name'),
             'status'=>request('status'),  
            ); 
        }
        BookBanner::where(['id'=>$id])->update($data);
        return redirect()->route('book_banner.index')
        ->with('success','Banners Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BookBanner::find($id)->delete();
         return redirect()->route('book_banner.index')
        ->with('success','Banners Deleted successfully.');
    }
}
