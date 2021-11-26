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

		    <h4 class="page-title">Manage Subject & Topic </h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Subject & Topic </a></li>

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

        <div class="card-header">Add Subject & Topic</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('subtopic.store')}}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Subject Name</label>

                  <div class="col-sm-10">
                    <input type="text" name="subject_name" class="form-control" value="">
                

                  </div>

            </div>

             <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Topic Name</label>

                  <div class="col-sm-10">

                    <input type="text" name="topic_name" class="form-control" value="">

                  </div>

            </div>


        <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Status</label>

                  <div class="col-sm-10">

                   <select class="form-control" name="status">
                      <option value="1">Active</option>
                      <option value="0">InActive</option>
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
              CKEDITOR.replace( 'description' );
            
          </script>
@endsection