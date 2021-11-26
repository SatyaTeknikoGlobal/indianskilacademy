<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\PartnerApp;

class PartnerAppController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = PartnerApp::latest()->get();
        return view('admin/partner/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
         return view('admin/partner/add');
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
        'app_name' =>'required',
        'app_url'=>'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        ]);
         $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/partner'), $imageName);
        PartnerApp::create([
            'app_name'=>request('app_name'),
            'app_url'=>request('app_url'),
            'image'=>$imageName,
            'status'=>request('status'),   
        ]);
        return redirect()->route('partner.create')
        ->with('success','PartnerApp Added successfully.');
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
        $data = PartnerApp::latest()->where('id',$id)->get();
        return view('admin/partner/edit',array('data'=>$data));
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
        'app_name' =>'required',
        'app_url'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        'id'=>'required',
        ]);
         $image = $request->file('image');
         $id = $request->input('id');
        if (!empty($image)) {
        //Store Image In Folder
        $imageName = time().'.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/partner'), $imageName);
        $data = array(
            'app_name'=>request('app_name'),
            'app_url'=>request('app_url'),
            'image'=>$imageName,
            'status'=>request('status'),   
        );
        PartnerApp::where('id',$id)->update($data);
         return redirect()->route('partner.index')
        ->with('success','Record Updated successfully.');

    }
    else{
         $data = array(
             'app_name'=>request('app_name'),
             'app_url'=>request('app_url'),
            'status'=>request('status'),  
            ); 
        PartnerApp::where('id',$id)->update($data);
         return redirect()->route('partner.index')
        ->with('success','Record Updated successfully.');
    }
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
         PartnerApp::where('id',$id)->delete();
         return redirect()->route('partner.index')
        ->with('success','Record Delete successfully.');
    }
}
