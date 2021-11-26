<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Faq;

class FaqController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = Faq::latest()->get();
        return view('admin/faq/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin/faq/add');
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
        'question'=>'required',
        'answer'=>'required',
        ]);
        Faq::create([
            'question'=>request('question'),
            'answer'=>request('answer'),
        ]);
        return redirect()->route('faq.index')
        ->with('success','Faq Added successfully.');
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
        $data = Faq::where(['id'=>$id])->first();
        return view('admin/faq/edit',array('data'=>$data));
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
        'question'=>'required',
        'answer'=>'required',
        'id'=>'required'
        ]);
        $id = $request->input('id');
        $data =array(
            'question'=>request('question'),
            'answer'=>request('answer'),
        );
        Faq::where(['id'=>$id])->update($data);
        return redirect()->route('faq.index')
        ->with('success','Faq Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Faq::where('id',$id)->delete();
         return redirect()->route('faq.index')
        ->with('success','faqs Delete successfully.');
    }
}
