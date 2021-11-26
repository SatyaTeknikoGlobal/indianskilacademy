@extends('admin/layout')

@section('examination')

active

@endsection

@section('quiz')

active

@endsection

@section('content')

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Exam</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Quiz</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('quiz.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Add Quiz</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('quiz.store')}}" method="post" enctype="multipart/form-data">

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




<?php /*
           <div class="col">

              <div class="form-group">

              <label for="validationCustom09" id="select_class">Select SubCourses</label>

                <select class="form-control valid" name="subject_id"  onchange="getChapter()" id="subject_list" required="" aria-invalid="false">   

                  </select>

             </div>

            </div>

          </div>

          <div class="row">
             <div class="col-md-6">

               <div class="form-group ">

                  <label class="col-form-label">Select Program*</label>

                  <select class="form-control valid" id="topic_id" name="topic_id" required="">

                    <option readonly value="0">Select Program</option>

                    </select>

                </div>

            </div>
*/
?>

             <div class="col">

               <div class="form-group row">

                  <label class="col-form-label">Time(In Minutes)</label>

                  <input type="number" name="time" class="form-control" placeholder="Enter Time in Minutes">
                </div>

            </div>
          </div>





          <hr>


           <div class="row">

            <div class="col-md-3"></div>

            <div class="col-md-6">

             

            </div>

            <div class="col-md-3"></div>

          </div>
            <div class="row">

              <div class="col">

                 <label class="col-form-label">Title *</label>

                  <input type="text" name="title" class="form-control" required="">

              </div>

              <div class="col">

                  

                    <label class="col-form-label">Select Instruction *</label>

                    <select class="form-control valid" id="input-6" name="instruction" required="" aria-invalid="false" required="">

                        <option>Select Instruction</option>

                        @foreach($instructions as $row)

                          <option value="{{$row->id}}" selected="">{{$row->title}}</option>

                        @endforeach

                    </select>

              </div>

            </div>

            <div class="row">

              <div class="col">

                 <label class="col-form-label">Start Date *</label>

                  <input type="date" name="start_date" class="form-control" >

              </div>

              <div class="col">

                  <label class="col-form-label">End Date *</label>

                  <input type="date" name="end_date" class="form-control" >

              </div>

            </div>

            <div class="row">

              <div class="col">

                 <label class="col-form-label">Start Time *</label>

                  <input type="time" name="start_time" class="form-control" >

              </div>

              <div class="col">

                  <label class="col-form-label">End Time *</label>

                  <input type="time" name="end_time" class="form-control" >

              </div>

            </div>

            
             <div class="row">

              <div class="col">

                 <label class="col-form-label">Marks Per Question*</label>

                  <input type="text" name="marks" placeholder="Marks Per Question" class="form-control">

              </div>

            </div>


             <div class="row">

              <div class="col">

                 <label class="col-form-label">Negetive Mark Per Question*</label>

                  <input type="text" name="negetive_marks" placeholder="Negetive Mark Per Question" class="form-control">

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

function getChapter() {

              sub_id  = $("#subject_list").val();

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

            </script>



          @endif



</script>

@endsection