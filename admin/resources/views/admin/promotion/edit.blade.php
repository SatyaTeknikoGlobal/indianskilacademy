@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('promotionalvideo')

active

@endsection

@section('content')


<?php
$categories = config('custom.video_categories');
?>

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Promotional Video</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Promotional Video</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('promotionalvideo.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Edit Promotional Video</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ url ('promotionalvideo_update')}}" method="post" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" value="{{$data->id}}">

            
     <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label"> Name*</label>

      <div class="col-sm-10">

       <select name="cat_id" class="form-control">
         <option>Select Category</option>
         <?php if(!empty($categories)){
          foreach($categories as $cat=>$value){
          ?>
          <option value="{{$cat}}" <?php if($cat == $data->cat_id) echo "selected"?>>{{$value}}</option>
        <?php }}?>
       </select>
      </div>

    </div>






             <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label"> Name*</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter  Name" name="name" value="{{$data->name}}">

            </div>

          </div>

            <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Video ID*</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Youtube Video ID" name="video_id" value="{{$data->video_id}}">

            </div>

          </div>


          
          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status *</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status" >

                  <option value="1" <?php if($data->status == '1') echo "selected"?>>Active</option>

                  <option value="0" <?php if($data->status == '0') echo "selected"?>>Deactive</option>

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

<script>
              CKEDITOR.replace( 'description' );
            
          </script>
@endsection
