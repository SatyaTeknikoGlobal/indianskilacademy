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

        <div class="card-header">Add News</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('news.store')}}" method="post" enctype="multipart/form-data">

            @csrf

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title *</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" >

            </div>

          </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Tags *</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Tags" name="tags" >

            </div>

          </div>

          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Date</label>

            <div class="col-sm-10">

            <input type="date" class="form-control form-control-rounded" id="input-26" placeholder="Enter Date" name="date" value="{{date('Y-m-d')}}">

            </div>

          </div>




            <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Short Description</label>

      <div class="col-sm-10">

        <textarea name="short_description" id="description" class="form-control" placeholder="Enter Short Description">{{$row->short_description ?? ''}}</textarea>

      </div>

    </div>





           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Long Description</label>

            <div class="col-sm-10">

          

                <textarea id='description'  name='description' style='border: 1px solid black;'>
                    

                </textarea>

            </div>

          </div>

  <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">File Type *</label>

      <div class="col-sm-10">

        <select class="form-control" name="type" id="type">
          <option selected disabled value="">Choose File Type</option>
          <!-- <option value="vimeo" >Vimeo</option> -->
          <option value="youtube" >Youtube</option>
          <!-- <option value="local" >Local Video</option> -->
          <option value="image">Image</option>
        </select>

      </div>

    </div>





    <div class="form-group row" id="filetypelocalimage">
      <label for="input-26" class="col-sm-2 col-form-label">Image *</label>
      <div class="col-sm-10">
        <input type="file" class="form-control-file" id="input-26" placeholder="" name="image" >
      </div>
    </div>


    <div class="form-group row" id="filetypevideo">
      <label for="input-26" class="col-sm-2 col-form-label">Enter Youtube ID *</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="input-26" placeholder="Enter Youtube ID" name="image" placeholder="Enter Video ID">
      </div>
    </div>







<script type="text/javascript">



  $( document ).ready(function() {
   $('#filetypevideo').hide();
   $('#filetypelocalimage').hide();


 });





  $("#type").change(function(){
   var type = $(this).val();
   //alert(type);
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

 <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Link/Source *</label>

               <div class="col-sm-10">
                <input type="text" class="form-control-file" id="input-26" placeholder="Enter Link" name="link" >

            </div>

          </div>


          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status *</label>

               <div class="col-sm-10">

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



 <script>
             // CKEDITOR.replace( 'description' );
          </script>

         
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

   @if ($message = Session::get('success'))

        <script>

        Swal.fire({

            icon: 'success',

            title: '{{ $message }}',

            showConfirmButton: false,

            timer: 2500

          });

        setInterval(function(){ window.location.href="{{ route('news.index')}}"}, 1500);

        </script>



        @endif

@endsection