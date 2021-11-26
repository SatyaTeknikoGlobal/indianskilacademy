
<!DOCTYPE html>

<html lang="en">

<head>

  <meta charset="utf-8"/>

  <meta http-equiv="X-UA-Compatible" content="IE=edge"/>

  <meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no"/>

  <meta name="description" content=""/>

  <meta name="author" content=""/>

  <title>ISA - Admin Login</title>

  <!-- loader-->

  <link href="{{URL::asset('assets/css/pace.min.css')}}" rel="stylesheet"/>

  <script src="{{URL::asset('assets/js/pace.min.js')}}"></script>

 
  <!--favicon-->

  <link rel="icon" href="{{URL::asset('assets/images/logo.png')}}" type="image/x-icon"/>

  <!-- Bootstrap core CSS-->

  <link href="{{URL::asset('assets/css/bootstrap.min.css')}}" rel="stylesheet"/>

  <!-- animate CSS-->

  <link href="{{URL::asset('assets/css/animate.css')}}" rel="stylesheet" type="text/css"/>

  <!-- Icons CSS-->

  <link href="{{URL::asset('assets/css/icons.css')}}" rel="stylesheet" type="text/css"/>

  <!-- Custom Style-->

  <link href="{{URL::asset('assets/css/app-style.css')}}" rel="stylesheet"/>

</head>



<body>



<!-- Start wrapper-->

 <div id="wrapper">



 <div class="height-100v d-flex align-items-center justify-content-center">

  <div class="card card-authentication1 mb-0">

    <div class="card-body">

     <div class="card-content p-2">

      <div class="text-center">

        <img src="{{URL::asset('assets/images/logo.png')}}" alt="logo icon" style="height: 150px; width: 100%;">

      </div>

      <div class="card-title text-uppercase text-center py-3">Admin Login</div>

          @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

        <form action="{{ route('login.store') }}" method="POST">

        @csrf

        <div class="form-group">

        <label for="exampleInputUsername">Email</label>

         <div class="position-relative has-icon-right">

          <input type="text" id="exampleInputUsername" class="form-control input-shadow" placeholder="Enter Email" name="email">

          <div class="form-control-position">

            <i class="icon-user"></i>

          </div>

         </div>

        </div>

        <div class="form-group">

        <label for="exampleInputPassword">Password</label>

         <div class="position-relative has-icon-right">

          <input type="password" id="exampleInputPassword" class="form-control input-shadow" placeholder="Enter Password" name="password">

          <div class="form-control-position">

            <i class="icon-lock"></i>

          </div>

         </div>

        </div>

      <div class="form-row">

       <div class="form-group col-6">

         <div class="icheck-material-primary">

                <input type="checkbox" id="user-checkbox" checked="" />

                <label for="user-checkbox">Remember me</label>

        </div>

       </div>

      </div>

       <button type="submit" class="btn btn-primary btn-block">LogIn</button>

       </form>

       </div>

      </div>

       </div>

   </div>

    

     <!--Start Back To Top Button-->

    <a href="javaScript:void();" class="back-to-top"><i class="fa fa-angle-double-up"></i> </a>

    <!--End Back To Top Button-->

  

  

  

  </div><!--wrapper-->

  

  <!-- Bootstrap core JavaScript-->

  <script src="{{URL::asset('assets/js/jquery.min.js')}}"></script>

  <script src="{{URL::asset('assets/js/popper.min.js')}}"></script>

  <script src="{{URL::asset('assets/js/bootstrap.min.js')}}"></script>

  

  <!-- sidebar-menu js -->

  <script src="{{URL::asset('assets/js/sidebar-menu.js')}}"></script>

  

  <!-- Custom scripts -->

  <script src="{{URL::asset('assets/js/app-script.js')}}"></script>

  

</body>

</html>

