@extends('admin/layout')

@section('users')
active
@endsection
@section('subadmin')
active
@endsection
@section('content')

<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Manage SubAdmins</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Subadmin</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('subadmin.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
	<div class="card">
        <div class="card-header">Add SubAdmin</div>
           <div class="card-body">
           	 @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
            <form action="{{ route('subadmin.store')}}" method="post">
            @csrf
           <div class="form-group row">
            <label for="input-26" class="col-sm-2 col-form-label">Name</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Name" name="name">
            </div>
          </div>
            <div class="form-group row">
            <label for="input-26" class="col-sm-2 col-form-label">Role *</label>
            <div class="col-sm-10">
             <select class="form-control form-control-rounded" name="is_admin" id="is_admin" required="">

                  <option value="0" selected="">Subadmin</option>

                  <option value="3">Accountant</option>

              </select>
            </div>
          </div>
          <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">Email</label>
            <div class="col-sm-10">
            <input type="email" class="form-control form-control-rounded" id="input-27" placeholder="Enter Email Address" name="email">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-28" class="col-sm-2 col-form-label">Mobile</label>
            <div class="col-sm-10">
            <input type="number" class="form-control form-control-rounded" id="input-28" placeholder="Enter Mobile Number" name="phone">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-29" class="col-sm-2 col-form-label">Password</label>
            <div class="col-sm-10">
            <input type="password" class="form-control form-control-rounded" id="input-29" placeholder="Enter Password" name="password">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-30" class="col-sm-2 col-form-label">Confirm Password</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-30" placeholder="Confirm Password" name="password_confirmation">
            </div>
          </div>
          <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
            <div class="icheck-material-dark">
            <input type="checkbox" id="user-checkbox6" checked="">
            <label for="user-checkbox6">Remember me</label>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
   @if ($message = Session::get('success'))
        <script>
        Swal.fire({
            icon: 'success',
            title: '{{ $message }}',
            showConfirmButton: false,
            timer: 2500
          });
        setInterval(function(){ window.location.href="{{ route('subadmin.index')}}"}, 1500);
        </script>

        @endif
@endsection