<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Notes;
use App\Content;
use App\StorageModel;
use App\StoragePushModel;
use Aws\S3\S3Client;

class NotesController extends Controller
{
    /**
     * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function index()
    {

       $data = Notes::latest()->get();
        return view('admin/notes/list',array('data'=>$data));
    }

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {
        $data = Content::where(['type'=>'video'])->latest()->get();
        return view('admin/notes/add',array('data'=>$data));
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
            'notes' => 'required|mimes:pdf',
            'topic_id'=>'required',
        ]);

        $content_name = time().'.'.request()->notes->getClientOriginalExtension();
        request()->notes->move(public_path('content/notes'), $content_name);
        $insert_content = Notes::updateOrCreate([
            'topic_id'=>request('topic_id')],
        [
            'notes'=>$content_name
        ]);
        $insert_storage = StorageModel::create([
            'content_id' =>$insert_content->id,
            'version' =>'High',
            'content' =>$content_name,
            'status' =>'N',
          ]);
//        StoragePushModel::create([
//            "storage_id"=>$insert_storage->id,
//            "content_name"=>$content_name,
//            "status"=>'Y'
//        ]);
        return redirect()->route('topic.index')
            ->with('success','Notes Added successfully.');
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
        $data = Content::where(['type'=>'video'])->latest()->get();
        $notes = Notes::where(['id'=>$id])->first();
        return view('admin/notes/edit',array('data'=>$data,'notes'=>$notes));
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
            'notes' => 'required|mimes:pdf',
            'content_id'=>'required',
            'notes'=>'nullable|mimes:pdf',
            'id'=>'required'
        ]);
        $id = request('id');
        $notes = request('notes');
        if (!empty($notes)){
            $content_name = time().'.'.request()->notes->getClientOriginalExtension();
            request()->notes->move(public_path('content/notes'), $content_name);
            $udata = array(
                'content_id'=>request('content_id'),
                'notes'=>$content_name
            );
        }
        else{
            $udata = array(
                'content_id'=>request('content_id'),
            );
        }

        $data = Notes::where(['id'=>$id])->update($udata);
        $insert_content = Notes::where(['id'=>$id])->first();
        $insert_storage = StorageModel::where(['content_id'=>$insert_content->id])->update([
            'content_id' =>$insert_content->id,
            'version' =>'High',
            'content' =>$insert_content->notes,
            'status' =>'N',
        ]);

//        StoragePushModel::create([
//            "storage_id"=>$insert_storage->id,
//            "content_name"=>$content_name,
//            "status"=>'Y'
//        ]);
        return redirect()->route('topic.index')
            ->with('success','Notes Updated successfully.');
    }

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Notes::where('id',$id)->delete();
         return redirect()->route('notes.index')
        ->with('success','Record Delete successfully.');
    }
}
