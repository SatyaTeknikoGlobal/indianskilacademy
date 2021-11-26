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
		    <h4 class="page-title">Manage PartnerApp</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">PartnerApp</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('partner.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
	<div class="card">
        <div class="card-header">Edit PartnerApp</div>
           <div class="card-body">
           	 @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
          @foreach($data as $row)
            <form action="{{ url ('partner_update')}}" method="post" enctype="multipart/form-data">
              <input type="hidden" name="id" value="{{$row->id}}">
            @csrf
           <div class="form-group row">
            <label for="input-26" class="col-sm-2 col-form-label">App Name</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter App Name" name="app_name" value="{{$row->app_name}}">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-27" class="col-sm-2 col-form-label">App Url</label>
            <div class="col-sm-10">
            <input type="text" class="form-control form-control-rounded" id="input-27" placeholder="Enter App Url" name="app_url" value="{{$row->app_url}}">
            </div>
          </div>
          <div class="form-group row">
            <label for="input-29" class="col-sm-2 col-form-label">Image</label>
            <div class="col-sm-10">
            <input type="file" class="form-control-file-rounded" id="input-29" name="image">
             <br>
             <br>

              @if(isset($row->image) && is_file(public_path('images/partner/' .$row->image)))
                <img src="{{URL::asset('images/partner')}}/{{$row->image}}" alt="profile-image" class="profile" height="80px" width="80px">
                 @endif
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
          @endforeach
         </div>
         </div>
@endsection