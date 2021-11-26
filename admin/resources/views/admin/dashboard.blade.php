@extends('admin/layout')


@section('dashboard')

active

@endsection

@section('content')


<!--Start Dashboard Content-->


<h2 style="text-transform: capitalize;">Dashboard</h2>
<div class="container">
  <div class="row">

   <div class="col-12 col-lg-6 col-xl-3">

     <div class="card gradient-deepblue">

       <div class="card-body">

        <h5 class="text-white mb-0">{{count($total_user ?? '')}} <span class="float-right"><i class="fa fa-users"></i></span></h5>

        <div class="progress my-3" style="height:3px;">

         <div class="progress-bar" style="width:55%"></div>

       </div>

       <a href="{{url('/app_users')}}"> <p class="mb-0 text-white small-font">Total Users <span class="float-right"></span></p></a>

     </div>

   </div> 

 </div>



 <div class="col-12 col-lg-6 col-xl-3">

   <div class="card gradient-ohhappiness">

    <div class="card-body">

      <h5 class="text-white mb-0">{{$faculties ?? ''}} <span class="float-right"><i class="fa fa-eye"></i></span></h5>

      <div class="progress my-3" style="height:3px;">

       <div class="progress-bar" style="width:55%"></div>

     </div>

     <a href="{{url('/faculties')}}"><p class="mb-0 text-white small-font">Faculties <span class="float-right"></span></p></a>

   </div>

 </div>

</div>

<div class="col-12 col-lg-6 col-xl-3">

 <div class="card gradient-ibiza">

  <div class="card-body">

    <h5 class="text-white mb-0">{{count($boards)}} <span class="float-right"><i class="fa fa-envira"></i></span></h5>

    <div class="progress my-3" style="height:3px;">

     <div class="progress-bar" style="width:55%"></div>

   </div>

   <a href="{{url('/course')}}"><p class="mb-0 text-white small-font">Courses <span class="float-right"></span></p> </a>

 </div>


</div>

</div>


<div class="col-12 col-lg-6 col-xl-3">

 <div class="card gradient-orange">

   <div class="card-body">
    <h5 class="text-white mb-0"><?=$free_user?> <span class="float-right"><i class="fa fa-usd"></i></span></h5>

    <div class="progress my-3" style="height:3px;">

     <div class="progress-bar" style="width:55%"></div>

   </div>

   <p class="mb-0 text-white small-font">Free Users<span class="float-right"></span></p>

 </div>

</div>

</div>





</div>
</div>
</div>



@endsection