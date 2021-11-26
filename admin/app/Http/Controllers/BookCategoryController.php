<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\BookCategory;

class BookCategoryController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = BookCategory::where(['status'=>1])->get();
        return view('admin/category/list', array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = BookCategory::where(['status'=>1])->get();
        return view('admin/category/add', array('data'=>$data));

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
            'category_name' =>'required',
            'status'=>'required'
        ]);
        BookCategory::create([
            'category_name'=>request('category_name'),
            'status'=>request('status'),

        ]);
        return redirect()->route('book_category.index')
        ->with('success','Category Added successfully.');


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
        $category = BookCategory::where(['id'=>$id])->first();
        $data = BookCategory::where(['status'=>1])->get();
        return view('admin/category/edit', array('data'=>$data,'category'=>$category));
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
            'category_name' =>'required',
            'status'=>'required',
        ]);
        $data = array(
            'category_name'=>request('category_name'),
            'status'=>request('status'),
        );  

        BookCategory::where(['id'=>$id])->update($data);
        return redirect()->route('book_category.index')
        ->with('success','Category Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        BookCategory::find($id)->delete();
        return redirect()->route('book_category.index')
        ->with('success','Category Deleted successfully.');

    }
}
