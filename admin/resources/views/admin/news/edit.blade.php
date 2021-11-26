@extends('admin/layout')



@section('app_setting')

active

@endsection

@section('news')

active

@endsection

@section('content')


<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>


<div class="container-fluid">

  <!-- Breadcrumb-->

  <div class="row pt-2 pb-2">

    <div class="col-sm-9">

      <h4 class="page-title">Manage News & Feeds</h4>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

        <li class="breadcrumb-item active"><a href="javaScript:void();">News & Feeds</a></li>

      </ol>

    </div>

    <div class="col-sm-3">

     <div class="btn-group float-sm-right">

      <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('news.index') }}">List</a>

    </div>

  </div>

</div>

<!-- End Breadcrumb-->

<div class="card">

  <div class="card-header">Edit News</div>

  <div class="card-body">

   @if ($errors->any())

   @foreach ($errors->all() as $error)

   <div id="fadeout-msg" class="alert alert-danger">

    {{ $error }}

  </div>

  @endforeach

  @endif

  @foreach($data as $row)

  <form action="{{ url ('/news_update')}}" method="post" enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="id" value="{{$row->id}}">

    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Title *</label>

      <div class="col-sm-10">

        <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title"   value="{{$row->title}}">

      </div>

    </div>
    <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Tags *</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Tags" name="tags"   value="{{$row->tags}}">

            </div>

          </div>

 <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Date *</label>

            <div class="col-sm-10">

            <input type="date" class="form-control form-control-rounded" id="input-26" placeholder="Enter Date" name="date"   value="{{$row->date}}">

            </div>

          </div>

           <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Short Description</label>

      <div class="col-sm-10">

        <textarea name="short_description" id="description" class="form-control" placeholder="Enter Short Description">{{$row->short_description ?? ''}}</textarea>

      </div>

    </div>




    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Description</label>

      <div class="col-sm-10">

        <textarea name="description" id="description" class="form-control" placeholder="Enter Description">{{$row->description ?? ''}}</textarea>

      </div>

    </div>

    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">File Type *</label>

      <div class="col-sm-10">

        <select class="form-control" name="type" id="type">
          <option selected disabled value="">Choose File Type</option>
          <option value="youtube" <?php if($row->type == 'youtube') echo "selected";?>>Youtube</option>
         
          <option value="image" <?php if($row->type == 'image') echo "selected";?>>Image</option>
        </select>

      </div>
      <?php if($row->type == 'image'){?>
        <img src="{{url('public/images/news/'.$row->image)}}" height="50" width="50">
      <?php }?>



    </div>

   

    <div class="form-group row" id="filetypelocalimage">
      <label for="input-26" class="col-sm-2 col-form-label">File *</label>
      <div class="col-sm-10">
        <input type="file" class="form-control-file" id="imageinput" placeholder="" name="image" >
      </div>
    </div>


    <div class="form-group row" id="filetypevideo">
      <label for="input-26" class="col-sm-2 col-form-label">Enter Video ID *</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="videoinput" placeholder="Enter Video ID" name="image" placeholder="Enter Video ID">
      </div>
    </div>










<div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Link/Source *</label>

               <div class="col-sm-10">
                <input type="text" class="form-control-file" id="input-26" placeholder="Enter Link" name="link" value="<?=$row->link?>">

            </div>

          </div>










    <div class="form-group row">

      <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status *</label>

      <div class="col-sm-10">

        <select class="form-control form-control-rounded" name="status" id="status" >

          <option value="1" <?php if($row->status == 1) echo 'selected'?>>Active</option>

          <option value="0" <?php if($row->status == 0) echo 'selected'?>>Deactive</option>

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
  @endforeach

</div>

</div>

<script>
  //CKEDITOR.replace( 'description' );
</script>











<script type="text/javascript">



  $( document ).ready(function() {
   $('#filetypevideo').hide();
   $('#filetypelocalimage').hide();


 });





  $("#type").change(function(){
   var type = $(this).val();
   var input = '<?php echo $row->type?>';

   if(type == 'image'){
    $('#filetypevideo').hide();

    $('#filetypelocalimage').show();
  }
  else if(type == 'youtube'){
    $('#filetypelocalimage').hide();

    $('#filetypevideo').show();

  }



});
</script>


@endsection

