@extends('admin/layout')
@section('app_setting')
active
@endsection
@section('partner')
active
@endsection
@section('content')

<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Manage Publisher</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Publisher</a></li>
         </ol>
     </div>
     <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('publisher.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
  <div class="card">
        <div class="card-header">Add Publisher</div>
           <div class="card-body">
             @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
              @endif
            <form action="{{ route('publisher.update',$data->id)}}" method="post" enctype="multipart/form-data">
            @csrf
            @method('PUT')
           <div class="form-group row">
            <label for="input-26" class="col-sm-2 col-form-label">Publisher Name</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Publisher Name" name="name" value="{{$data->name}}">
            </div>
          </div>
         
          <div class="form-group row">
            <label for="input-29" class="col-sm-2 col-form-label">Image</label>
            <div class="col-sm-10">
            <input type="file" class="form-control-file-rounded" id="input-29" name="image">
            <br>
            <br>

            @if(isset($data->image) && is_file(public_path('images/publisher/' .$data->image)))
            <img src="{{URL::asset('images/publisher')}}/{{$data->image}}" alt="profile-image" class="profile" height="80px" width="80px">
             @endif
            </div>
          </div>
           <div class="form-group row">
              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status</label>
               <div class="col-sm-10">
              <select class="form-control form-control-rounded" name="status" id="status" value="">
                  @if($data->status=='Y')
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
         </div>
         </div>
@endsection