<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\User;

class SubAdminController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {
        $data = User::latest()->whereIn('is_admin',[0,3])->get();
        return view('admin/subadmin/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        return view('admin/subadmin/add');
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
        'email'=>'required|email|unique:admin',
        'phone' => 'required|digits:10',
        'is_admin'=>'required',
        'password'=>'required|confirmed',
        ]);
        User::create([
            'name'=>request('name'),
            'email'=>request('email'),
            'phone'=>request('phone'),
            'password'=>bcrypt(request('password')),
            'is_admin'=>request('is_admin'),
        ]);
        return redirect()->route('subadmin.create')
        ->with('success','SubAdmin Added successfully.');
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
        $data = User::latest()->where('id',$id)->get();
        return view('admin/subadmin/edit',array('data'=>$data));
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
        'name' =>'required',
        'email'=>'required',
        'phone' => 'required|digits:10',
        'id'=>'required',
        ]);
        $id = $request->input('id');
        $data = array(
            'name'=>request('name'),
            'email'=>request('email'),
            'phone'=>request('phone'),
        );
        User::where('id',$id)->update($data);
         return redirect()->route('subadmin.index')
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
         User::where('id',$id)->delete();
         return redirect()->route('subadmin.index')
        ->with('success','Record Delete successfully.');
    }
}
