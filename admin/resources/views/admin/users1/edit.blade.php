	@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('users')

active

@endsection

@section('app_users')

active

@endsection

@section('content')



<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Users</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">User</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('app_users.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Edit Users</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ url ('app_users_update')}}" method="post">

            @csrf

            <input type="hidden" name="id" value="{{$data->id}}">

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Name*</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Name" name="name" value="{{$data->name}}" required="">

            </div>

          </div>

          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Email*</label>

            <div class="col-sm-10">

            <input type="email" class="form-control form-control-rounded" id="input-26" placeholder="Enter Email" name="email" value="{{$data->email}}" required="">

            </div>

          </div>

          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Phone</label>

            <div class="col-sm-10">

            <input type="number" class="form-control form-control-rounded" id="input-26" placeholder="Enter Phone" name="phone" value="{{$data->phone}}">

            </div>

          </div>

          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">DOB*</label>

            <div class="col-sm-10">

            <input type="date" class="form-control form-control-rounded" id="input-26" placeholder="Enter Phone" name="dob" value="{{$data->date_of_birth}}" required="">

            </div>

          </div>

          <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Courses*</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="boards_id" name="board_id" required="">

                    <option readonly>Select Courses</option>

                    @foreach($board as $row)

                      @if($row->id == $data->board_id)

                      <option value="{{$row->id}}" selected="">{{$row->board_name}}</option>

                      @else

                        <option value="{{$row->id}}">{{$row->board_name}}</option>

                      @endif

                      @endforeach

                    </select>

                  </div>

            </div>
          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status*</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status" required="">

                  <option value="1" selected="">Active</option>

                  <option value="0">Deactive</option>

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

@endsection