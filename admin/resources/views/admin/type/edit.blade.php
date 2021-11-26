@extends('admin/layout')



@section('subcription')

active

@endsection

@section('type')

active

@endsection

@section('content')

<script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Subcription</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Subcription</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('subcription_type.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Edit Subcription Type</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

          @foreach($data as $row)

          <form action="{{ url ('subcription_type_update')}}" method="post" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" value="{{$row->id}}">

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Title</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" value="{{$row->title}}">

            </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Select Courses</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="type_id" id="type_id">

                  @foreach($class as $r)

                  @if($r->id==$row->type_id)

                  <option value="{{$r->id}}" selected="">{{$r->board_name}}</option>

                  @else

                  <option value="{{$r->id}}">{{$r->board_name}}</option>

                  @endif

                  @endforeach

              </select>

            </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Type</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="type" id="type">

                  <option value="courses" selected="">Courses Wise Subcription</option>

              </select>

            </div>

          </div>

          <div class="form-group row">

            <label for="input-27" class="col-sm-2 col-form-label">Original Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control form-control-rounded" id="input-27" placeholder="" name="original_amount" placeholder="amount" value="{{$row->amount}}">

            </div>

          </div>

          <div class="form-group row">

            <label for="input-28" class="col-sm-2 col-form-label">Discount Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control form-control-rounded" id="input-28" placeholder="" name="amount" placeholder="amount" value="{{$row->new_amount}}">

            </div>

          </div>

          <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">Start Date</label>

          <div class="col-sm-10">

            <input type="date" name="start_date" value="{{$row->start_date}}" placeholder="Enter start_date" id="start_date" class="form-control form-control-rounded" />


          </div>

          </div>
            <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">End Date</label>

          <div class="col-sm-10">

            <input type="date" name="end_date" value="{{$row->end_date}}" placeholder="Enter end_date" id="end_date" class="form-control form-control-rounded" />


          </div>

          </div>

          <div class="form-group row">

          <label for="basic-textarea" class="col-sm-2 col-form-label">Description</label>

          <div class="col-sm-10">

          <textarea rows="4"  name="description" class="form-control form-control-rounded" id="description" value="{{$row->description}}">{{$row->description}}</textarea>

          </div>

          </div>

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status">

                   @if($row->status=='Y')

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

              CKEDITOR.replace( 'description' );

          </script>

@endsection