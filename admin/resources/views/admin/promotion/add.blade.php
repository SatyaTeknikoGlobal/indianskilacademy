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

  <div class="card-header">Add Promotional Video</div>

  <div class="card-body">

   @if ($errors->any())

   @foreach ($errors->all() as $error)

   <div id="fadeout-msg" class="alert alert-danger">

    {{ $error }}

  </div>

  @endforeach

  @endif

  <form action="{{ route('promotionalvideo.store')}}" method="post" enctype="multipart/form-data">

    @csrf




     <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label"> Name*</label>

      <div class="col-sm-10">

       <select name="cat_id" class="form-control">
         <option>Select Category</option>
         <?php if(!empty($categories)){
          foreach($categories as $cat=>$value){
          ?>
          <option value="{{$cat}}">{{$value}}</option>
        <?php }}?>
       </select>
      </div>

    </div>





    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label"> Name*</label>

      <div class="col-sm-10">

        <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter  Name" name="name" value="" >

      </div>

    </div>

    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Video ID*</label>

      <div class="col-sm-10">

        <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Youtube Video ID" name="video_id" value="">

      </div>

    </div>


    
          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status *</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status">

                  <option value="1">Active</option>

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
<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

<script>

  function getSubject() {

   boards = $("#boards_id").val();


   $.ajax({

    type: "POST",

    url: "{{ url ('/getSubjectList') }}",

    data: {'board_id':boards,'_token':'{{ csrf_token() }}'},

    success:function(data){

      var data = jQuery.parseJSON(data);

      $('#subject_list').html(data.subjectList);

    }

  });

 }

 function getChapter() {

  subject_id  = $("#subject_list").val();

  $.ajax({

    type: "POST",

    url: "{{ url ('/getSubject') }}",

    data: {id:subject_id,'_token':'{{ csrf_token() }}'},

    success:function(data){

      $('#chapter_list').html(data);

    }

  });

}

</script>
<script>
  CKEDITOR.replace( 'description' );

</script>
@endsection