@extends('admin/layout')
@section('books')
active
@endsection
@section('feedback')
active
@endsection
@section('content')
<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Manage Feedback Course</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Feedback Course</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('feedback.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
	<div class="card">
        <div class="card-header">Add Feedback Course</div>
           <div class="card-body">
           	 @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
            <form action="{{ route('feedback.store')}}" method="post" enctype="multipart/form-data">
            @csrf


           <div class="form-group row">
            <div class="col-12">
            <label for="input-26" class=" col-form-label">Course Name*</label>
            
            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Course Name" name="title" >
           
            </div>
           
          </div>

            <div class="form-group row">
            <div class="col-12">
             <label for="input-26" class=" col-form-label">Image</label>
            
            <input type="file" class="form-control-file-rounded" id="input-29" name="file_name">
           
            </div>
        </div>
            <div class="form-group row">

            <div class="col-12">
            <label for="input-26" class=" col-form-label">Status*</label>
              <select class="form-control form-control-rounded" name="status" id="status" >

                  <option value="1" selected="">Active</option>

                  <option value="0">Deactive</option>

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


            </div>


               </div>
         <script>
              CKEDITOR.replace( 'description' );
          
          </script>




@endsection