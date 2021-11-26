<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Book;
use App\BookCategory;
use App\Author;
use App\Publisher;
use Yajra\DataTables\DataTables;


class BookController extends Controller
{/**

         * Display a listing of the resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function getBooks(Request $request)
    {

     $search_data = $request->search['value'];
     if(!empty($search_data)) {
         $data = Book::with(['authors'])
         ->where('book_name', 'LIKE', "%{$search_data}%")
         ->orwhere('type', 'LIKE', "%{$search_data}%")
         ->orwhere('language', 'LIKE', "%{$search_data}%")
         ->orwhere('sale_price', 'LIKE', "%{$search_data}%")
         ->get();
     }
     else{
        $data = Book::with(['authors'])
        ->get();
    }
    return Datatables::of($data)
    ->filter(function ($instance) use ($request) {

    })
    ->make(true);
}
public function index()
{
    $data = Book::where('status',1)->get();
    return view('admin/books/list',array('data'=>$data));
}

    /**
     * Show the form for creating a new resource.
     *
     * @return \Illuminate\Http\Response
     */
    public function create()
    {   
       $category=BookCategory::where('status',1)->get();

       return view('admin/books/add',array('category'=>$category));
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
            'book_name' =>'required',
            'category' =>'required',
            'status' =>'required',
            'file_name' => 'required',
            'author_name' => 'required',
            'image' => 'required',
        ]);
        $imageName = time().'audio.'.request()->file_name->getClientOriginalExtension();
        request()->file_name->move(public_path('images/books'), $imageName);

        $image = time().'image.'.request()->image->getClientOriginalExtension();
        request()->image->move(public_path('images/books'), $image);



        Book::create([
            'book_name'=>request('book_name'),
            'category'=>request('category'),
            'file_name'=>$imageName,
            'image'=>$image,
            'status'=>request('status'),
            'author_name'=>request('author_name'),
            'description'=>request('description'),

        ]);
        return redirect()->route('book.index')
        ->with('success','Book Added successfully.');
    }

    /**
     * Display the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function show($id)
    {
        $data = Book::where(['id'=>$id])->with(['authors','publishers','categories'])->first();
        return view('admin/books/show',array('data'=>$data));
    }

    /**
     * Show the form for editing the specified resource.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function edit($id)
    {
       $category=BookCategory::where('status',1)->get();
       $author=Author::where('status','Y')->get();
       $publisher=Publisher::where('status','Y')->get();
       $data = Book::where(['id'=>$id])->first();
       return view('admin/books/edit',array('category'=>$category,'author'=>$author,'publisher'=>$publisher,'data'=>$data));
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
            'book_name' =>'required',
            'category' =>'required',
            'status' =>'required',
            'image' =>'required',
            'author_name' => 'required',
        ]);


        $exist = Book::where('id',$id)->first();

        $image = $exist->image ??"";
        if(!empty($request->image)){
           $image = time().'image.'.request()->image->getClientOriginalExtension();
           request()->image->move(public_path('images/books'), $image);
       }

       if (!empty($request->file_name)) {
        $imageName = time().'file.'.request()->file_name->getClientOriginalExtension();
        request()->file_name->move(public_path('images/books'), $imageName);
        $data = array(
            'book_name'=>request('book_name'),
            'category'=>request('category'),
            'file_name'=>$imageName,
            'image'=>$image,
            'status'=>request('status'),
            'author_name'=>request('author_name'),
            'description'=>request('description'),
        );
    }
    else{
      $data = array(
        'book_name'=>request('book_name'),
        'category'=>request('category'),
        'status'=>request('status'),
        'image'=>$image,

        'author_name'=>request('author_name'),
        'description'=>request('description'),
    );

  }
  Book::where(['id'=>$id])->update($data);
  return redirect()->route('book.index')
  ->with('success','Book Updated successfully.');
}

    /**
     * Remove the specified resource from storage.
     *
     * @param  int  $id
     * @return \Illuminate\Http\Response
     */
    public function destroy($id)
    {
        Book::find($id)->delete();
        return redirect()->route('book.index')
        ->with('success','Book deleted successfully.');
    }
}
