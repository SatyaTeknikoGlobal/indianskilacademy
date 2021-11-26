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

        <div class="card-header">Edit Packages Type</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ url ('subcription_packages_update')}}" method="post" enctype="multipart/form-data">

              <input type="hidden" name="id" value="{{$edit->id}}">

            @csrf

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title"  value="{{$edit->title}}">

            </div>

          </div>

           <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Select Class</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="type_id" id="type_id">

                  @foreach($type as $row)

                  @if($edit->type_id==$row->id)

                  <option value="{{$row->id}}" selected="">{{$row->title}}</option>

                  @else

                   <option value="{{$row->id}}">{{$row->title}}</option>

                  @endif

                  @endforeach

              </select>

            </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Type</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="sub_type" id="type">

                  <option value="class" selected="">Class Wise Subcription</option>

              </select>

            </div>

          </div>

          <div class="form-group row">

            <label for="input-27" class="col-sm-2 col-form-label">Original Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control form-control-rounded" id="input-27" placeholder="" name="original_amount" placeholder="amount" value="{{$edit->amount}}">

            </div>

          </div>

          <div class="form-group row">

            <label for="input-28" class="col-sm-2 col-form-label">Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control form-control-rounded" id="input-28" placeholder="" name="amount" placeholder="amount" value="{{$edit->new_amount}}">

            </div>

          </div>

          <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">Days</label>

          <div class="col-sm-10">

            <input type="number" name="days" value="{{$edit->days}}" placeholder="Enter Days" id="days" class="form-control form-control-rounded" />


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

@endsection