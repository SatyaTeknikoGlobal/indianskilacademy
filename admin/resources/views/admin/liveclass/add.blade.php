@extends('admin/layout')
@section('manage_content')
active
@endsection
@section('live_classes')
active
@endsection
@section('content')

<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Manage Live Classes</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Live Classes</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('live_classes.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
	<div class="card">
        <div class="card-header">Add LiveClasses</div>
           <div class="card-body">
           	 @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
            <form action="{{ route ('live_classes.store')}}" method="post" enctype="multipart/form-data">
            @csrf
           <div class="form-group row">
            <label for="input-26" class="col-sm-2 col-form-label">Title*</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" required="">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-29" class="col-sm-2 col-form-label">Image*</label>
            <div class="col-sm-10">
            <input type="file" class="form-control-file-rounded" id="input-29" name="image" required="">
            </div>
          </div>
          <div class="form-group row">
                  <label for="basic-select" class="col-sm-2 col-form-label">Select Batch*</label>
                  <div class="col-sm-10">
                  <select class="form-control form-control-rounded select2" required id="large-select" name="course_id">
                    <option readonly>Select Batch</option>
                    @foreach($topics as $row)
                    <?php 
                    $subject = \App\Subject::where('id',$row->subject_id)->first();
                    $category = \App\Boards::where('id',$row->course_id)->first();
                    ?>
                      <option value="{{$row->id}}">{{$category->board_name ?? '' }}-->>>>{{$subject->title ?? '' }}-->>>>{{$row->name}}</option>
                      @endforeach
                    </select>
                  </div>
            </div>




            <div class="form-group row">
                  <label for="basic-select" class="col-sm-2 col-form-label">Select Faculties*</label>
                  <div class="col-sm-10">
                  <select class="form-control form-control-rounded" required id="large-select" name="faculties_id">
                    <option readonly>Select Faculties</option>
                    @foreach($data as $row)
                      <option value="{{$row->id}}">{{$row->name}}</option>
                      @endforeach
                    </select>
                  </div>
            </div>
           <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">Meeting Id</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-27" name="meeting_id">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-27" name="passcode">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">Start Date*</label>
            <div class="col-sm-10">
            <input type="date" class="form-control form-control-rounded" id="input-27" name="start_date" required="">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-28" class="col-sm-2 col-form-label">Description</label>
            <div class="col-sm-10">
          <textarea rows="4" name="description" class="form-control form-control-rounded" id="basic-textarea"></textarea>
          </div>
          </div>
           <div class="form-group row">
            <label for="input-30" class="col-sm-2 col-form-label">Start Time</label>
            <div class="col-sm-10">
            <input type="time" class="form-control form-control-rounded" id="input-30" placeholder="Enter College/University Name" name="start_time">
            </div>
          </div>
             <div class="form-group row">
            <label for="input-30" class="col-sm-2 col-form-label">End Time</label>
            <div class="col-sm-10">
            <input type="time" class="form-control form-control-rounded" id="input-30" placeholder="Enter College Location" name="end_time">
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
@endsection