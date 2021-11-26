@extends('admin/layout')

@section('examination')

active

@endsection

@section('test')

active

@endsection

@section('content')

   <!-- <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script> -->

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Exam</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Mock Test</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('test.index') }}">List</a>

      </div>

     </div>

     </div>
   

	<div class="card">

        <div class="card-header">Add Mock Test</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('test.store')}}" method="post" enctype="multipart/form-data">

            @csrf

          <div class="row">

            <div class="col">

              <div class="form-group">

              <label for="validationCustom08">Select Courses *</label>

                  <select class="form-control valid" name="board_id" required="" id="board_id" aria-invalid="false"  onchange="getSubject()" required="">
                      <option selected="" disabled=""> Select Courses</option>
                        @foreach($boards as $row)

                          <option value="{{$row->id}}">{{$row->board_name}}</option>

                        @endforeach

                      </select>

                </div>

              </div>

            <div class="col">

              <div class="form-group">

              <label for="validationCustom09" id="select_class">Select Subject *</label>

                <select class="form-control valid" name="subject_id" id="subject_list" required="" aria-invalid="false" required="" onchange="getChapter()">   

                  </select>

             </div>

            </div>

          </div>
            <div class="row">

            <div class="col">

              <div class="form-group">

              <label for="validationCustom08">Select Chapter *</label>

                  <select class="form-control valid" name="chapter" id="chapter_list" onchange="getTopic();" required="" aria-invalid="false">

                         

                      </select>

                </div>

              </div>

            <div class="col">

              <div class="form-group">

              <label for="validationCustom09" id="select_class">Select Topic *</label>
                     <select class="form-control valid" name="topic" required="" id="topic_list" aria-invalid="false">

                        

                      </select>
             </div>
            </div>
          </div>
         <!-- End Breadcrumb-->
          <hr>
           <div class="row">
            <div class="col">
                <label class="col-form-label">Title *</label>
                  <input type="text" name="title" class="form-control" required="">
            </div>

            <div class="col">
                   <label class="col-form-label">Image *</label>

                  <input type="file" name="image" class="form-control-file" required="">
                
            </div>

          </div>
            <div class="row">

              <div class="col">

                  <label class="col-form-label">Time</label>

                  <input type="number" name="time" class="form-control" required="" placeholder="Enter Time in mins">
               
              </div>

              <div class="col">

                  

                    <label class="col-form-label">Select Instruction *</label>

                    <select class="form-control valid" id="input-6" name="instruction" required="" aria-invalid="false" required="">

                        <option>Select Instruction</option>

                        @foreach($instructions as $row)

                          <option value="{{$row->id}}">{{$row->title}}</option>

                        @endforeach

                    </select>

              </div>

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



           function getSubject() {

             boards = $("#board_id").val();

             class_id = $("#class_id").val();

             $.ajax({

                type: "POST",

                url: "{{ url ('/getSubjectList') }}",

                data: {'board_id':boards,'class_id':class_id,'_token':'{{ csrf_token() }}'},

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

            function getTopic() {

              chapter_id  = $("#chapter_list").val();

               $.ajax({

                  type: "POST",

                  url: "{{ url ('/getTopic') }}",

                  data: {id:chapter_id,'_token':'{{ csrf_token() }}'},

                  success:function(data){

                      $('#topic_list').html(data);

                  }

               });

            }

</script>

@endsection