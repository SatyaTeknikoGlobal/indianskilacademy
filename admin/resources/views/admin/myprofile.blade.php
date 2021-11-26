@extends('admin/layout')

@section('dashboard')
active
@endsection
@section('content')
   
   <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

        <h4 class="page-title">Manage My Profile</h4>

        <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">My Profile</a></li>

         </ol>

     </div>

     <div class="col-sm-3">

       <div class="btn-group float-sm-right">



      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

  <div class="card">

        <div class="card-header">Edit Profile</div>

           <div class="card-body">

             @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('dashboard.update', Auth()->user()->id)}}" method="post" enctype="multipart/form-data">

            @csrf

            @method('PUT')

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Name</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Name" name="name" value="{{ Auth::user()->name}}" required="">

            </div>

          </div>

          

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Email</label>

               <div class="col-sm-10">

                <input type="email" class="form-control form-control-rounded" id="input-26" placeholder="Enter Email" name="email" required="" value="{{ Auth::user()->email}}">

            </div>

          </div>


            <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Phone</label>

               <div class="col-sm-10">

                <input type="number" class="form-control form-control-rounded" id="input-26" placeholder="Enter phone" name="phone" required="" value="{{ Auth::user()->phone}}">

            </div>

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
         <div class="card">

        <div class="card-header">Change Password</div>

           <div class="card-body">

            <form action="{{ route('myprofile.update', Auth::user()->id)}}" method="post" enctype="multipart/form-data">

            @csrf

            @method('PUT')

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Password</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Password" name="password" required="" >
            </div>

          </div>
          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Re-Password</label>

               <div class="col-sm-10">

                <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Confirm Password" name="password_confirmation" required="">

            </div>
          </div>         
        </div>
           <div class="form-group row">

            <label class="col-sm-2 col-form-label"></label>

            <div class="col-sm-10">

            <button type="submit" class="btn btn-dark btn-round px-5"><i class="icon-lock"></i> Submit</button>

            </div>

          </div>

          </form>

    <!--      </div>

         </div>

     </div>
 </div> -->
 
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