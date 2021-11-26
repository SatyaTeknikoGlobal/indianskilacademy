<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Packages;
use App\SubcriptionType;

use Illuminate\Support\Facades\DB;

class SubcriptionPackagesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */     
    public function index()
    {
         $data= DB::table('subscription_packages')
        ->select('subscription_packages.*','subscription_types.title as type_title')
        ->join('subscription_types','subscription_packages.type_id','=','subscription_types.id')
        ->orderby('subscription_packages.id','DESC')
        ->get();
        return view('admin/packages/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = SubcriptionType::get();
        return view('admin/packages/add',array('type'=>$data));
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
        'title' =>'required',
        'start_date'=>'required',
        'type_id'=>'required',
        'original_amount'=>'required',
        'amount' => 'required',
        'end_date'=>'required',
        'status'=>'required',
        ]);
        Packages::create([
            'title'=>request('title'),
            'start_date'=>request('start_date'),
            'type_id'=>request('type_id'),
            'amount'=>request('original_amount'),
            'new_amount'=>request('amount'),
            'end_date'=>request('end_date'),
            'status'=>request('status'),
        ]);
        return redirect()->route('subcription_packages.index')
        ->with('success','Record Added successfully.');
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
         $data = SubcriptionType::get();
         $edit = Packages::where(['id'=>$id])->first();
        return view('admin/packages/edit',array('type'=>$data,'edit'=>$edit));
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
        'title' =>'required',
        'start_date'=>'required',
        'type_id'=>'required',
        'original_amount'=>'required',
        'amount' => 'required',
        'end_date'=>'required',
        'status'=>'required',
        'id'=>'required'
            ]);
          $id = $request->input('id');
          $data = array(
            'title'=>request('title'),
            'start_date'=>request('start_date'),
            'type_id'=>request('type_id'),
            'amount'=>request('original_amount'),
            'new_amount'=>request('amount'),
            'end_date'=>request('end_date'),
            'status'=>request('status'),
          );
          Packages::where(['id'=>$id])->update($data);
          return redirect()->route('subcription_packages.index')
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
        Packages::where('id',$id)->delete();
         return redirect()->route('subcription_packages.index')
        ->with('success','Record Delete successfully.');
    }
}
