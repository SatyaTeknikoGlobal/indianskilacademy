@extends('admin/layout')



@section('app_setting')

active

@endsection

@section('board')

active

@endsection

@section('content')



<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

        <h4 class="page-title">Manage Courses</h4>

        <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Courses</a></li>

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

        <div class="card-header">Edit Courses</div>

           <div class="card-body">

             @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

          @foreach($data as $row)

            <form action="{{ url ('/course_update')}}" method="post" enctype="multipart/form-data">

            @csrf

            <input type="hidden" name="id" value="{{$row->id}}">

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Courses Name *</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Courses Name" name="board_name" value="{{$row->board_name}}" required="">

            </div>

          </div>
              <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Hindi Name </label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Hindi Courses Name" name="board_name_hindi" value="{{$row->board_name_hindi}}">

            </div>

          </div>


          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Image *</label>

            <div class="col-sm-10">

            <input type="file" class="form-control-file" id="input-26" name="image">

            <br>
             @if(isset($row->image) && is_file(public_path('images/course/' .$row->image)))

                <img src="{{URL::asset('images/course')}}/{{$row->image}}" alt="profile-image" class="profile" height="80px" width="80px">

                 @endif

          </div>

          </div>
          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status *</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status" required="">

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

            <label for="input-26" class="col-sm-2 col-form-label">Subscription Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control" value="{{$row->subscription_amount}}"   name="subscription_amount">

            </div>

          </div>  



          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Subscription Duration(in Days)</label>

            <div class="col-sm-10">

            <input type="text" class="form-control" value="{{$row->duration}}"   name="duration" placeholder="Enter Subscription Duratio  in Days">

            </div>

          </div>  






          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Is Paid *</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="is_paid" id="is_paid" required="">

                @if($row->status=='Y')

                  <option value="Y" selected="">Yes</option>

                  <option value="N">No</option>

                  @else
                    <option value="Y">Yes</option>

                  <option value="N" selected="">No</option>

                  @endif

              </select>

            </div>

          </div>


          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Type</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="type" id="type" required="">

                  <option value="private"<?php if($row->type == 'private')echo 'selected'?>>Private</option>

                  <option value="public"<?php if($row->type == 'public')echo 'selected'?>>Public</option>

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



    @endsection