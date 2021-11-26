@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('app_sidebar')

active

@endsection

@section('content')

<?php 

$sidebarArr = config('custom.sidebars');

?>

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

        <h4 class="page-title">Manage App Side Bar</h4>

        <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">App Side Bar</a></li>

         </ol>

     </div>

     <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  url('app_sidebar') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

  <div class="card">

        <div class="card-header">Edit</div>

           <div class="card-body">

             @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="" method="post" enctype="multipart/form-data">

            @csrf


              <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Choose Side Bar</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="bar_id" id="bar_id" required="">
                <option value="" disabled selected> Choose Side Bar</option>

                <?php 
                if(!empty($sidebarArr)){
                  foreach ($sidebarArr as $key => $value) {
                   
                ?>
                <option value="{{$key}}" <?php if($key == $sidebar->bar_id) echo "selected"?>>{{$value}}</option>

                  <?php }}?>
              </select>

            </div>

          </div>





           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" value="{{$sidebar->title ?? ''}}">

            </div>

          </div>


    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">File Type *</label>

      <div class="col-sm-10">

        <select class="form-control" name="type" id="type">
          <option selected disabled value="">Choose File Type</option>
         
          <option value="youtube" <?php if($sidebar->type == 'youtube') echo "selected"?>>Youtube</option>
         
          <option value="pdf" <?php if($sidebar->type == 'pdf') echo "selected"?>>PDF</option>
          <option value="image" <?php if($sidebar->type == 'image') echo "selected"?>>Image</option>

        </select>

      </div>

    </div>




          

    <div class="form-group row" id="filetypelocalimage">
      <label for="input-26" class="col-sm-2 col-form-label">PDF *</label>
      <div class="col-sm-10">
        <input type="file" class="form-control-file" id="input-26" placeholder="" name="file" >
      </div>
    </div>






    <div class="form-group row" id="filetypevideo">
      <label for="input-26" class="col-sm-2 col-form-label">Enter Youtube ID *</label>
      <div class="col-sm-10">
        <input type="text" class="form-control" id="input-26" placeholder="Enter Youtube ID" name="file" placeholder="Enter Video ID" value="{{$sidebar->file ?? ''}}">
      </div>
    </div>


   <?php if($sidebar->type == 'pdf'){?>
      <a href="{{url('public/images/app_sidebar/'.$sidebar->file)}}" target="_blank"><img src="{{url('public/images/app_sidebar/pdf.png')}}" height="50px" width="50px"></a>
    <?php }?>

       <?php if($sidebar->type == 'image'){?>
      <a href="{{url('public/images/app_sidebar/'.$sidebar->file)}}" target="_blank"><img src="{{url('public/images/app_sidebar/'.$sidebar->file)}}" height="50px" width="50px"></a>
    <?php }?>


           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Link</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Link" name="link" value="{{$sidebar->link ?? ''}}">

            </div>

          </div>



           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Short Description</label>

            <div class="col-sm-10">

          <textarea name="description" class="form-control">{{$sidebar->description ?? ''}}</textarea>

            </div>

          </div>






          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status *</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status" required="">

                  <option value="1" <?php if($sidebar->status == '1') echo "selected"?>>Active</option>

                  <option value="0" <?php if($sidebar->status == '0') echo "selected"?>>Deactive</option>

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











    <script type="text/javascript">
 

  $( document ).ready(function() {


    var type = '<?php echo isset($sidebar->type) ? $sidebar->type :''?>';
    if(type == 'youtube'){
     $('#filetypelocalimage').hide();
    $('#filetypevideo').show();


    }
    if(type == 'pdf' || type == 'image'){
    $('#filetypevideo').hide();
     $('#filetypelocalimage').show();


    }



 });





  $("#type").change(function(){
   var type = $(this).val();

   if(type == 'pdf' || type == 'image'){
    $('#filetypevideo').hide();

    $('#filetypelocalimage').show();
  }
  else if(type == 'youtube'){
    $('#filetypelocalimage').hide();

    $('#filetypevideo').show();

  }



});

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

        setInterval(function(){ window.location.href="{{ url('app_sidebar')}}"}, 1500);

        </script>



        @endif

@endsection


