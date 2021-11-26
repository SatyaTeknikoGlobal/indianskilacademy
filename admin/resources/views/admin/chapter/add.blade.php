@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('chapter')

active

@endsection

@section('content')



<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Subjects</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Subjects</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('chapter.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Add Subjects</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('chapter.store')}}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Courses *</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="boards_id" name="boards_id" onchange="getSubject()" required="">

                    <option readonly>Select Courses</option>

                    @foreach($board as $row)

                      <option value="{{$row->id}}">{{$row->board_name}}</option>

                      @endforeach

                    </select>

                  </div>

            </div>

             <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select SubCourses *</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="subject_list" name="subject_id" required="">

                    <option readonly>Select SubCourses</option>


                    </select>

                  </div>

            </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Subject Name *</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Subject Name" name="chapter_name" required="">

            </div>

          </div>

            <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">Description</label>

          <div class="col-sm-10">

          <textarea rows="4" name="description" class="form-control form-control-rounded" id="basic-textarea"></textarea>

          </div>

          </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Image *</label>

            <div class="col-sm-10">

            <input type="file" class="form-control-file" id="input-26" name="image" required="">

            </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status" required="">

                  <option value="Y" selected="">Active</option>

                  <option value="N">Deactive</option>

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

        </script>

   @if ($message = Session::get('success'))

        <script>

        Swal.fire({

            icon: 'success',

            title: '{{ $message }}',

            showConfirmButton: false,

            timer: 2500

          });

        setInterval(function(){ window.location.href="{{ route('chapter.index')}}"}, 1500);

        </script>



        @endif



@endsection