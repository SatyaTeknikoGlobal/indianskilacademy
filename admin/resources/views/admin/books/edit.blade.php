@extends('admin/layout')
@section('books')
active
@endsection
@section('book')
active
@endsection
@section('content')
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<div class="container-fluid">
  <!-- Breadcrumb-->
  <div class="row pt-2 pb-2">
    <div class="col-sm-9">
      <h4 class="page-title">Manage Book</h4>
      <ol class="breadcrumb">
        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
        <li class="breadcrumb-item active"><a href="javaScript:void();">Book</a></li>
    </ol>
</div>
<div class="col-sm-3">
 <div class="btn-group float-sm-right">
    <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('book.index') }}">List</a>
</div>
</div>
</div>
<!-- End Breadcrumb-->
<div class="card">
    <div class="card-header">Edit Book</div>
    <div class="card-body">
       @if ($errors->any())
       @foreach ($errors->all() as $error)
       <div id="fadeout-msg" class="alert alert-danger">
          {{ $error }}
      </div>
      @endforeach
      @endif
      <form action="{{ route('book.update', $data->id)}}" method="post" enctype="multipart/form-data">
        @csrf
        @method('PUT')



        <div class="form-group row">
            <div class="col-6">
                <label for="input-26" class=" col-form-label">Book Name*</label>

                <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Book Name" name="book_name" value="{{$data->book_name}}">

            </div>
            <div class="col-6">
                <label for="input-26" class=" col-form-label">Category*</label>
                <select class="form-control form-control-rounded" aria-label="Default select example" name="category" >
                  <option selected>Open this select Category</option>
                  @foreach($category as $row)
                  @if($row->id==$data->category)
                  <option value="{{$row->id}}" selected="">{{$row->category_name}}</option>
                  @else
                  <option value="{{$row->id}}">{{$row->category_name}}</option>
                  @endif
                  @endforeach
              </select>
          </div>
      </div>

      <div class="form-group row">

          <label for="input-26" class="col-sm-2 col-form-label">Description*</label>

          <div class="col-sm-10">

            <textarea class="form-control" name="description" id="description">{{$data->description}}</textarea>

        </div>

    </div>


    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Author Name*</label>

      <div class="col-sm-10">

        <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Author Name" name="author_name" value="{{$data->author_name}}">


    </div>

</div>

<div class="form-group row">

<label for="input-26" class="col-sm-2 col-form-label">Image*</label>

<div class="col-sm-10">

    <input type="file" class="form-control form-control-rounded" id="input-26" name="image" >

    @if(isset($data->image) && is_file(public_path('images/books/' .$data->image)))

    <img src="{{url('/public/images/books/'.$data->image)}}" alt="image" class="profile" height="80px" width="80px">

    @endif

</div>

</div>






<div class="form-group row">
    <div class="col-6">
       <label for="input-26" class=" col-form-label">Audio File</label>

       <input type="file" class="form-control-file-rounded" id="input-29" name="file_name">

   </div>
   <div class="col-6">

       <label for="exampleInputEmail1" class=" col-form-label">Status</label>
       <select class="form-control form-control-rounded" name="status" id="status" required="">
          @if($data->status=='Y')
          <option value="Y" selected="">Active</option>
          <option value="N">No</option>
          @else
          <option value="Y">Yes</option>
          <option value="N" selected="">InActive</option>
          @endif
      </select>
  </div>


</div>

<div class="form-group row">
    <label class="col-sm-2 col-form-label"></label>
    <div class="col-sm-10">
        <button type="submit" class="btn btn-dark btn-round px-5"><i class="icon-lock"></i> Submit</button>
    </div>
</div>
</form>
</div>
</div>
<script>
  CKEDITOR.replace( 'description' );
  function getFilediv() {
      var type=  $("#type").val();
      if (type=='ebook') {
        $("#file_upload").show();
    }
    else{
        $("#file_upload").hide();
    }
}
</script>

@endsection