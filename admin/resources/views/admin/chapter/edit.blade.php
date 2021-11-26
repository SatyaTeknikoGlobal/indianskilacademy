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

        <div class="card-header">Edit Subjects</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

          @foreach($data as $r)

            <form action="{{ url ('chapter_update')}}" method="post" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" value="{{$r->id}}">

            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Courses</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="boards_id" name="boards_id" required="" onchange="getSubject()">

                    <option readonly>Select Courses</option>

                    @foreach($board as $row)

                      @if($r->board_id==$row->id)

                      <option value="{{$row->id}}" selected="">{{$row->board_name}}</option>

                      @else

                       <option value="{{$row->id}}">{{$row->board_name}}</option>

                      @endif

                      @endforeach

                    </select>

                  </div>

            </div>

             <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Sub Courses</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="subject_id" name="subject_id">

                    <option readonly>Select Sub Courses</option>

                    @foreach($subject as $row)

                       @if($r->subject_id==$row->id)

                      <option value="{{$row->id}}" selected="">{{$row->title}}</option>

                      @else

                       <option value="{{$row->id}}">{{$row->title}}</option>

                      @endif

                      @endforeach

                    </select>

                  </div>

            </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Subject Name</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Subject Name" name="chapter_name" value="{{$r->chapter_name}}">

            </div>

          </div>

            <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">Description</label>

          <div class="col-sm-10">

          <textarea rows="4" name="description" class="form-control form-control-rounded" id="basic-textarea" value="{{$r->description}}">{{$r->description}}</textarea>

          </div>

          </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Image</label>

            <div class="col-sm-10">

            <input type="file" class="form-control-file" id="input-26" name="image">



              @if(isset($r->image) && is_file(public_path('images/chapter/' .$r->image)))

                <img src="{{URL::asset('images/chapter')}}/{{$r->image}}" alt="profile-image" class="profile" height="80px" width="80px">

                 @endif

            </div>

          </div>

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
   <script>

          function getSubject() {

             boards = $("#boards_id").val();

             $.ajax({

                type: "POST",

                url: "{{ url ('/getSubjectList') }}",

                data: {'board_id':boards,'_token':'{{ csrf_token() }}'},

                success:function(data){

                    var data = jQuery.parseJSON(data);

                    $('#subject_id').html(data.subjectList);

                }

             });

           }

        </script>
@endsection