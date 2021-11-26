@extends('admin/layout')



@section('subcription')

active

@endsection

@section('packages')

active

@endsection

@section('content')

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Packages</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Packages</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('subcription_packages.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Add Packages Type</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('subcription_packages.store')}}" method="post" enctype="multipart/form-data">

            @csrf

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title">

            </div>

          </div>

           <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Select Course</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="type_id" id="type_id">

                  @foreach($type as $row)

                  <option value="{{$row->id}}">{{$row->title}}</option>

                  @endforeach

              </select>

            </div>

          </div>

          <div class="form-group row">

            <label for="input-27" class="col-sm-2 col-form-label">Original Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control form-control-rounded" placeholder="Original Amount"  id="input-27" placeholder="" name="original_amount" placeholder="amount">

            </div>

          </div>

          <div class="form-group row">

            <label for="input-28" class="col-sm-2 col-form-label">Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control  form-control-rounded" id="input-28" placeholder="Enter Amount" placeholder="" name="amount" placeholder="amount">

            </div>

          </div>

         
          <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">Start Date</label>

          <div class="col-sm-10">

            <input type="date" name="start_date"  placeholder="Enter start_date" id="start_date" class="form-control form-control-rounded" />


          </div>

          </div>
            <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">End Date</label>

          <div class="col-sm-10">

            <input type="date" name="end_date"  placeholder="Enter end_date" id="end_date" class="form-control form-control-rounded" />


          </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status">

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

          <script>

              CKEDITOR.replace( 'description' );

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

        setInterval(function(){ window.location.href="{{ route('subcription_packages.index')}}"}, 1500);

        </script>

        @endif

@endsection