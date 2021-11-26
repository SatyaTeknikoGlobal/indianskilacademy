@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('class')

active

@endsection

@section('content')

    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Corner's</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Corner's</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('corner.create')}}">Add</a>

      </div>

     </div>

     </div>

       <div class="row">
        <div class="col-12">
          <div class="card">
            <div class="card-header text-uppercase">Agri Coaching Corner's </div>
            <div class="card-body">
              <div class="row">
              
             @foreach($data as $row)
                <div class="col-md-6 col-lg-3 col-xl-3">
                  <a href="{{URL::asset('images/gallery')}}/{{$row->image}}" data-fancybox="images">
                  <img src="{{URL::asset('images/gallery')}}/{{$row->image}}" alt="lightbox" class="lightbox-thumb img-thumbnail">
                </a>
                </div>
                @endforeach
              
               
              </div>
            </div>
          </div>
        </div>
      </div><!--End Row-->

    <!-- End container-fluid-->

     <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

   @if ($message = Session::get('success'))

        <script>

        Swal.fire({

            icon: 'success',

            title: '{{ $message }}',

            showConfirmButton: false,

            timer: 2500

          });

    </script>

@endif

@endsection