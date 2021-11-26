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

		    <h4 class="page-title">Manage Videos</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Videos</a></li>

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

        <div class="card-header">Add Videos</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('content.store')}}" method="post" enctype="multipart/form-data">

            @csrf
            
            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Courses *</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="board_id" name="board_id" onchange="getSubject()" required="">

                    <option value="0" readonly>Select Courses</option>

                    @foreach($board as $row)

                      <option value="{{$row->id}}" <?php if($board_id == $row->id)echo "selected"?>>{{$row->board_name}}</option>

                      @endforeach

                    </select>

                  </div>

            </div>

            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select SubCourses*</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" onchange="getChapter()" id="subject_id" name="subject_id" required="">

                    <option readonly value="0">Select SubCourses</option>

                   

                    </select>

                  </div>

            </div>

         

             <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Program*</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="topic_id" name="topic_id" required="">

                    <option readonly value="0">Select Program</option>

                    </select>

                  </div>

            </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title*</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" >

            </div>

          </div>


             <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Thumbnail*</label>

            <div class="col-sm-10">

            <input type="file" class="form-control-file" id="input-26" name="thumbnail" >

            </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Url Type</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="hls_type" id="hls_type" required="true">

                  <option value="youtube" >Youtube</option>
                  <option value="local" >Local</option>

              </select>

            </div>

          </div>

          <div class="form-group row" id="hls_div">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Youtube ID*</label>

               <div class="col-sm-10">
                <input class="form-control form-control-rounded" type="text" name="hls" id="hls_input" >

            </div>

          </div>

           <div class="form-group row" id= "file_upload_div" style="display: none;">

            <label for="file_upload" class="col-sm-2 col-form-label">File Upload*</label>

            <div class="col-sm-10">

            <input type="file" class="form-control-file" id="file_upload_input" name="hls">

            </div>

          </div>

           <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Type</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="type" id="type_input" >

                  <option value="video" >Videos</option>
                  <option value="notes" >Notes</option>

              </select>

            </div>

          </div>

          <div class="form-group row">

             <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Is Paid*</label>
               <div class="col-sm-10">
              <select class="form-control form-control-rounded" name="is_paid" id="status" required="">
                  <option value="Y" selected="">YES</option>
                  <option value="N">NO</option>
              </select>
           
            </div>

          </div>
          <!--  <div class="form-group row">

             <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Is Free*</label>
               <div class="col-sm-10">
              <select class="form-control form-control-rounded" name="is_free" id="status" required="">
                  <option value="Y" selected="">YES</option>
                  <option value="N">NO</option>
              </select>
           
            </div>

          </div> -->
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

   @if ($message = Session::get('success'))

        <script>

        Swal.fire({

            icon: 'success',

            title: '{{ $message }}',

            showConfirmButton: false,

            timer: 2500

          });

        setInterval(function(){ window.location.href="{{ route('content.index')}}"}, 1500);

        </script>



        @endif

        <script>

           function getSubject() {

             boards = $("#board_id").val();

             $.ajax({

                type: "POST",

                url: "{{ url ('/getSubjectList') }}",

                data: {'board_id':boards,'_token':'{{ csrf_token() }}'},

                success:function(data){

                    var data = jQuery.parseJSON(data);

                    $('#subject_id').html(data.subjectList);
                      $('#chapter_id').html( '<option readonly value="0">Select Chapter</option>');
                     $('#topic_id').html('<option readonly value="0">Select Program</option>');

                }

             });

           }
          function getChapter() {

              sub_id  = $("#subject_id").val();

               $.ajax({

            type: "POST",

            url: "{{ url ('/getSubject') }}",

            data: {id:sub_id,'_token':'{{ csrf_token() }}'},

            success:function(data){

                $('#topic_id').html(data);
                  //$('#topic_id').html('<option readonly value="0">Select Program</option>');

            }

        });

          }

          function getTopic() {

                chapter_id  = $("#chapter_id").val();

                $.ajax({

                    type: "POST",

                    url: "{{ url ('/getTopic') }}",

                    data: {id:chapter_id,'_token':'{{ csrf_token() }}'},

                    success:function(data){

                        $('#topic_id').html(data);

                    }

                });

            }

        </script>

        <script type="text/javascript">
          $(document).ready(function(){
           $('#hls_type').change(function(){
              var hls_type = $('#hls_type').val();
              if(hls_type != 'vimeo'){
                $('#file_upload_div').css('display','');
                $('#file_upload_input').attr('required',true);
                $('#hls_div').css('display','none');
                $('#hls_input').removeAttr('required');
              }else{
                $('#file_upload_div').css('display','none');
                $('#file_upload_input').removeAttr('required');
                $('#hls_div').css('display','');
                $('#hls_input').attr('required',true);
              }
           });
          });
        </script>

@endsection