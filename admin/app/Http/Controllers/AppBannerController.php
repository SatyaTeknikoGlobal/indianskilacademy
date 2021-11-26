<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\AppBanner;

class AppBannerController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = AppBanner::get();
        return view('admin/appbanner/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
          return view('admin/appbanner/add');
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
        //'timer_end_on'=>'required',
        'image' => 'required|image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        ]);
        // $imageName = time().'.'.request()->image->getClientOriginalExtension();
        // request()->image->move(public_path('images/app_banner'), $imageName);

        $imageName = time().'.'.$request->image->extension();  
     
        $request->image->move(public_path('images/app_banner'), $imageName);
        
        AppBanner::create([
            'timer_text'=>request('name'),
            'timer_end_on'=>request('timer_end_on'),
            'banner'=>$imageName,
            'status'=>request('status'),   
            'link'=>request('link'),   
        ]);
        return redirect()->route('app_banner.index')
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
        $data = AppBanner::where(['id'=>$id])->first();
        return view('admin/appbanner/edit',array('data'=>$data));
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
        //'timer_end_on'=>'required',
        'image' => 'image|mimes:jpeg,png,jpg,gif,svg|max:8192',
        'status'=>'required',
        ]);
         $image = $request->file('image');
        $data =array();
        if (!empty($image)) {

        $imageName = time().'.'.$request->image->extension();  
     
        $request->image->move(public_path('images/app_banner'), $imageName);


        //Store Image In Folder
        // $imageName = time().'.'.request()->image->getClientOriginalExtension();
        // request()->$imageName->move(public_path('images/app_banner'), $imageName);

        $data = array(
            'timer_text'=>request('name'),
            'timer_end_on'=>request('timer_end_on'),
            'banner'=>$imageName,
            'status'=>request('status'),
            'link'=>request('link'),   

        );
        }
        else{
             $data = array(
                 'timer_text'=>request('name'),
                 'timer_end_on'=>request('timer_end_on'),
                 'status'=>request('status'),  
                    'link'=>request('link'),   

                ); 
        }

        AppBanner::where(['id'=>$id])->update($data);
        return redirect()->route('app_banner.index')
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
        AppBanner::find($id)->delete();
         return redirect()->route('app_banner.index')
        ->with('success','Banners Deleted successfully.');
    }
}
