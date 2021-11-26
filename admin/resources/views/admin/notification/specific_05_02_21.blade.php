@extends('admin/layout')

@section('notification')

active

@endsection

@section('specific')

active

@endsection

@section('content')

 <!--multi select-->
  <link href="{{URL::asset('assets/plugins/jquery-multi-select/multi-select.css')}}" rel="stylesheet" type="text/css">

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Courses</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Specific User</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('course.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Send Notification</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('notification.store')}}" method="post" enctype="multipart/form-data">

            @csrf

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Users *</label>

            <div class="col-sm-10">

             <select class="form-control multiple-select" multiple="multiple" name="users[]">

                        @foreach($users as $row)
                          <option value="{{$row->id}}">{{$row->name}}</option>
                          @endforeach
                         
                      </select>
            </div>

          </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title *</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="" name="title" required="" value="AGRI COACHING">

            </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Message *</label>

               <div class="col-sm-10">

                <textarea class="form-control form-control-rounded" rows="5" name="message"></textarea>

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

        setInterval(function(){ window.location.href="{{ route('course.index')}}"}, 1500);

        </script>

   

        @endif

@endsection