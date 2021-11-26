@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('topic')

active

@endsection

@section('content')



<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Subject & Topic</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Subject & Topic</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('subtopic.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Edit Subject & Topic</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ url ('subtopic_update')}}" method="post" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" value="{{$subtopic->id}}">
            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Subject Name</label>

                  <div class="col-sm-10">
                    <input type="text" name="subject_name" class="form-control" value="{{$subtopic->subject_name}}">
                

                  </div>

            </div>

             <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Topic Name</label>

                  <div class="col-sm-10">

                    <input type="text" name="topic_name" class="form-control" value="{{$subtopic->topic_name}}">

                  </div>

            </div>


        <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Status</label>

                  <div class="col-sm-10">

                   <select class="form-control" name="status">
                      <option value="1" <?php if($subtopic->status == 1) echo "selected"?>>Active</option>
                      <option value="0"  <?php if($subtopic->status == 0) echo "selected"?>>InActive</option>
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
            
          </script>
@endsection
