@extends('admin/layout')
@section('manage_content')
active
@endsection
@section('faq')
active
@endsection
@section('content')
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Manage Faq</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Faq</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('faq.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
	<div class="card">
        <div class="card-header">Edit Faq</div>
           <div class="card-body">
           	 @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
            <form action="{{ url ('faq_update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$data->id}}">
          <div class="form-group row">
                <div class="col-md-6">
                    <div class="lng_fst_com">
                        <label>Question</label>
                        <textarea id='question'  name='question' style='border: 1px solid black;'>{{$data->question}}</textarea><br>
                    </div>
                </div>
                <!--/.col-->
                <div class="col-md-6">
                    <div class="lng_fst_com">
                        <label>Answer</label>
                        <textarea id='answer' name='answer' style='border: 1px solid black;'  >{{$data->answer}}</textarea><br>
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
              CKEDITOR.replace( 'answer' );
              CKEDITOR.replace( 'question' );
          </script>
@endsection