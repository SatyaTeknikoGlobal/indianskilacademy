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

        <h4 class="page-title">Manage Category</h4>

        <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Category</a></li>

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

        <div class="card-header">Edit Category</div>

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

            <label for="input-26" class="col-sm-2 col-form-label">Category Name *</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Category Name" name="board_name" value="{{$row->board_name}}" required="">

            </div>

          </div>



          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Priority</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Priority" name="priority" value="{{$row->priority}}" >

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