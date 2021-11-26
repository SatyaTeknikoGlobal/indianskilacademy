@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('testimonial')

active

@endsection

@section('content')



<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Monthly & Weekly PDF</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Monthly & Weekly PDF</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('monthweekpdf.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Add Monthly & Weekly PDF</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('monthweekpdf.store')}}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Title*</label>

                  <div class="col-sm-10">

                 <input type="text" name="title" value="" class="form-control">

                  </div>

            </div>
             <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">PDF</label>

                  <div class="col-sm-10">

                 <input type="file" name="pdf" value="" class="form-control">

                  </div>

            </div>




           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Date*</label>

            <div class="col-sm-10">

              <input type="date" name="date" class="form-control">

          </div>

          </div>


          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Status*</label>

            <div class="col-sm-10">

             <select class="form-control" name="status">
                <option value="1">Active</option>
                <option value="0" >InActive</option>
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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

        
@endsection