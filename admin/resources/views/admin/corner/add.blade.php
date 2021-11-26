@extends('admin/layout')
@section('app_setting')
active
@endsection
@section('corner')
active
@endsection
@section('content')

<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Manage Corners</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Corners</a></li>
         </ol>
     </div>
     <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('corner.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
  <div class="card">
        <div class="card-header">Add Corners</div>
           <div class="card-body">
             @if ($errors->any())
              @foreach ($errors->all() as $error)
              <div id="fadeout-msg" class="alert alert-danger">
                  {{ $error }}
              </div>
              @endforeach
          @endif
            <form action="{{ route ('corner.store') }}" method="post"  enctype="multipart/form-data">
            @csrf
                <div class="input-field">
                                <label class="active">Images</label>
                                <div class="input-images-1" style="padding-top: .5rem;"></div>
                            </div>
           <br/>
           <div class="form-group row">
            <label class="col-sm-2 col-form-label"></label>
            <div class="col-sm-10">
            <button type="submit" class="btn btn-dark btn-round px-5"><i class="icon-lock"></i> Submit</button>
            </div>
          </div>
          </form>
         </div>
         </div>
       </div>
   <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

  <script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
 
  <script>
    $(function () {
        $('.input-images-1').imageUploader();
    });
</script>
   @if ($message = Session::get('success'))
        <script>
        Swal.fire({
            icon: 'success',
            title: '{{ $message }}',
            showConfirmButton: false,
            timer: 2500
          });
       // setInterval(function(){ window.location.href="{{ route('class.index')}}"}, 1500);
        </script>

        @endif
    @endsection