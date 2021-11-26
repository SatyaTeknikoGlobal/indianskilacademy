@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('testimonial')

active

@endsection

@section('content')



<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

        <h4 class="page-title">Manage Testimonial</h4>

        <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Testimonial</a></li>

         </ol>

     </div>

     <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('testimonials.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

  <div class="card">

        <div class="card-header">Edit Testimonial</div>

           <div class="card-body">

             @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ url('/testimonial_update')}}" method="post" enctype="multipart/form-data">

            @csrf


            <input type="hidden" name="id" class="form-control" value="{{$data->id}}">



            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">User Name*</label>

                  <div class="col-sm-10">

                 <input type="text" name="user_name" class="form-control" value="{{$data->user_name}}">
                  </div>

            </div>


               <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Image</label>

                  <div class="col-sm-10">

                 <input type="file" name="image" value="" class="form-control">

                  </div>

            </div>


                        <?php 
                        if(!empty($data->image)){?>
                            <img src="{{url('public/images/testimonial/'.$data->image)}}" height="50px" width="50px">
                       <?php  }

                    ?>



           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Text*</label>

            <div class="col-sm-10">

              <textarea class="form-control" name="text" id="description">{{$data->text}}</textarea>

          </div>

          </div>


           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Text*</label>

            <div class="col-sm-10">

             <select class="form-control" name="status">
                <option value="1" <?php if($data->status == 1) echo "selected"?>>Active</option>
                <option value="0" <?php if($data->status == 0) echo "selected"?>>InActive</option>
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
             // CKEDITOR.replace( 'description' );
            
          </script>
@endsection