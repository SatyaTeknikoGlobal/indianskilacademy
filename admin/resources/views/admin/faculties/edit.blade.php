@extends('admin/layout')

@section('faculties')
active
@endsection
@section('faculties')
active
@endsection
@section('content')

<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Manage Faculties</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Faculties</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('faculties.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
	<div class="card">
        <div class="card-header">Edit Faculties</div>
           <div class="card-body">
           	 @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
          @foreach($data as $row)
            <form action="{{ url('faculties_update')}}" method="post" enctype="multipart/form-data">
            @csrf
            <input type="hidden" name="id" value="{{$row->id}}">
           <div class="form-group row">
            <label for="input-26" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Name" name="name" value="{{$row->name}}">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
            <input type="email" class="form-control form-control-rounded" id="input-27" placeholder="Enter Email Address" name="email" value="{{$row->email}}">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-28" class="col-sm-2 col-form-label">Mobile</label>
            <div class="col-sm-10">
            <input type="number" class="form-control form-control-rounded" id="input-28" placeholder="Enter Mobile Number" name="phone" value="{{$row->phone}}">
            </div>
          </div>
           <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">DOB</label>
            <div class="col-sm-10">
            <input type="date" class="form-control form-control-rounded" id="input-27" placeholder="Enter DOB" name="dob" value="{{$row->dob}}">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-29" class="col-sm-2 col-form-label">About Teacher (PDF)</label>
            <div class="col-sm-10">
            <input type="file" class="form-control-file-rounded" id="input-29" name="image">
             <br>
             <br>

           
              @if(isset($row->image) && is_file(public_path('images/faculties/' .$row->image)))
               <a href="{{URL::asset('images/faculties/'.$row->image)}}" target="_blank"><img src="{{URL::asset('images/subject/pdficon.png')}}" alt="profile-image" class="profile" height="50px" width="50px"></a>
               <!--  <img src="{{URL::asset('images/faculties')}}/{{$row->image}}" alt="profile-image" class="profile" height="80px" width="80px"> -->
                 @endif
            </div>
          </div>
          <div class="form-group row">
            <label for="input-30" class="col-sm-2 col-form-label">Last Education</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-30" placeholder="Enter last Education" name="education" value="{{$row->education}}">
            </div>
          </div>
           <div class="form-group row">
            <label for="input-30" class="col-sm-2 col-form-label">Speciality</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-30" placeholder="Enter Speciality" name="speciality"  value="{{$row->speciality}}">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">Total Exp.</label>
            <div class="col-sm-10">
            <input type="number" class="form-control form-control-rounded" id="input-27" placeholder="Enter DOB" name="total_exp" value="{{$row->total_exp}}">
            </div>
          </div>  
           <div class="form-group row">
            <label for="input-30" class="col-sm-2 col-form-label">College Name</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-30" placeholder="Enter College/University Name" name="college_name" value="{{$row->college_name}}">
            </div>
          </div>
             <div class="form-group row">
            <label for="input-30" class="col-sm-2 col-form-label">Location</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-30" placeholder="Enter College Location" name="college_location" value="{{$row->college_location}}">
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
@endsection