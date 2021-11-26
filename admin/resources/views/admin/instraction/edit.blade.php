@extends('admin/layout')
@section('examination')
active
@endsection
@section('instraction')
active
@endsection
@section('content')
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Manage Instractions</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Instractions</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('instraction.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
	<div class="card">
        <div class="card-header">Edit Instraction</div>
           <div class="card-body">
           	 @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
            <form action="{{ url ('instraction_update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$data->id}}">
           <div class="form-group row">
            <label for="input-26" class="col-sm-2 col-form-label">Title</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" value="{{$data->title}}">
            </div>
          </div>
          <div class="form-group row">
                <div class="col-md-6">
                    <div class="lng_fst_com">
                        <label>English Content</label>
                        <textarea id='e_content'  name='e_content' style='border: 1px solid black;'>{{$data->e_content}}</textarea><br>
                    </div>
                </div>
                <!--/.col-->
                <div class="col-md-6">
                    <div class="lng_fst_com">
                        <label>Hindi Content</label>
                        <textarea id='h_content' name='h_content' style='border: 1px solid black;'  >{{$data->h_content}}</textarea><br>
                    </div>
                </div>
                <!--/.col-->
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
              // CKEDITOR.replace( 'h_content' );
              // CKEDITOR.replace( 'e_content' );
          </script>
@endsection