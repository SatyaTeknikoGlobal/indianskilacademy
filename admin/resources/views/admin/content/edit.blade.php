@extends('admin/layout')

@section('manage_content')

active

@endsection

@section('contents')

active

@endsection

@section('content')



<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Content</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Content</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  url('/new/content') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Edit Content</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            @foreach($data as $r)

            <form action="{{ route('content.update' , $r->id)}}" method="post" enctype="multipart/form-data">

            @csrf

            @method('PUT')
           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" value="{{$r->title}}">

            </div>

          </div>


  
            <?php if($r->hls_type == 'youtube'){?>
          <div class="form-group row" id="hls_div">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Youtube ID*</label>

               <div class="col-sm-10">
                <input class="form-control form-control-rounded" type="text" name="hls" id="hls_input" required="true" value="{{$r->hls}}">

            </div>

          </div>
        <?php }else{?>
       <div class="form-group row" id= "file_upload_div" >

            <label for="file_upload" class="col-sm-2 col-form-label">File Upload*</label>

            <div class="col-sm-10">

            <input type="file" class="form-control-file" id="file_upload_input" name="hls">

            </div>

          </div>
         <?php }?>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status">

                 @if($r->status=='Y')
                  <option value="Y" selected="">Active</option>

                  <option value="N">Deactive</option>

                  @else

                  <option value="Y">Active</option>

                  <option value="N"  selected="">Deactive</option>

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

          @endforeach

         </div>

         </div>

          <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

   @if ($message = Session::get('success'))

        <script>

        Swal.fire({

            icon: 'success',

            title: '{{ $message }}',

            showConfirmButton: false,

            timer: 2500

          });

    </script>

@endif


 <script type="text/javascript">
  
           // $('#hls_type').change(function(){
           //    var hls_type = $('#hls_type').val();
           //    if(hls_type != 'youtube'){
           //      $('#file_upload_div').css('display','');
           //      $('#file_upload_input').attr('required',true);
           //      $('#hls_div').css('display','none');
           //      $('#hls_input').removeAttr('required');
           //    }else{
           //      $('#file_upload_div').css('display','none');
           //      $('#file_upload_input').removeAttr('required');
           //      $('#hls_div').css('display','');
           //      $('#hls_input').attr('required',true);
           //    }
           // });
        </script>
@endsection