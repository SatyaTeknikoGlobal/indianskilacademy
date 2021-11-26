@extends('admin/layout')



@section('dashboard')

active

@endsection

@section('content')

    <!--Start Dashboard Content-->

     <div class="row mt-3">

       <div class="col-12 col-lg-6 col-xl-3">

         <div class="card gradient-deepblue">

           <div class="card-body">

              <h5 class="text-white mb-0">{{$total_user ?? ''}} <span class="float-right"><i class="fa fa-users"></i></span></h5>

                <div class="progress my-3" style="height:3px;">

                   <div class="progress-bar" style="width:55%"></div>

                </div>

              <p class="mb-0 text-white small-font">Total Users <span class="float-right">+4.2% <i class="zmdi zmdi-long-arrow-up"></i></span></p>

            </div>

         </div> 

       </div>

       <div class="col-12 col-lg-6 col-xl-3">

         <div class="card gradient-orange">

           <div class="card-body">

              <h5 class="text-white mb-0">8323 <span class="float-right"><i class="fa fa-usd"></i></span></h5>

                <div class="progress my-3" style="height:3px;">

                   <div class="progress-bar" style="width:55%"></div>

                </div>

              <p class="mb-0 text-white small-font">Total Revenue <span class="float-right">+1.2% <i class="zmdi zmdi-long-arrow-up"></i></span></p>

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

              <p class="mb-0 text-white small-font">Faculties <span class="float-right">+5.2% <i class="zmdi zmdi-long-arrow-up"></i></span></p>

            </div>

         </div>

       </div>

       <div class="col-12 col-lg-6 col-xl-3">

         <div class="card gradient-ibiza">

            <div class="card-body">

              <h5 class="text-white mb-0">{{$boards ?? ''}} <span class="float-right"><i class="fa fa-envira"></i></span></h5>

                <div class="progress my-3" style="height:3px;">

                   <div class="progress-bar" style="width:55%"></div>

                </div>

              <p class="mb-0 text-white small-font">Courses <span class="float-right">+2.2% <i class="zmdi zmdi-long-arrow-up"></i></span></p>

            </div>

         </div>

       </div>

     </div><!--End Row-->

     <div class="row">

     <div class="col-12 col-lg-8 col-xl-8">

      <div class="card">

     <div class="card-header">Revenue

       <div class="card-action">

       <div class="dropdown">

       <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">

        <i class="icon-options"></i>

       </a>

        <div class="dropdown-menu dropdown-menu-right">

        <a class="dropdown-item" href="javascript:void();">Action</a>

        <a class="dropdown-item" href="javascript:void();">Another action</a>

        <a class="dropdown-item" href="javascript:void();">Something else here</a>

        <div class="dropdown-divider"></div>

        <a class="dropdown-item" href="javascript:void();">Separated link</a>

         </div>

        </div>

       </div>

     </div>

     <div class="card-body">

        <ul class="list-inline">

        <li class="list-inline-item"><i class="fa fa-circle mr-2" style="color: #14abef"></i>Total Revenue</li>

        

      </ul>

      <div class="chart-container-1">

          <canvas id="chart1"></canvas>

      </div>

     </div>

     

     <div class="row m-0 row-group text-center border-top border-light-3">

       <div class="col-12 col-lg-6">

         <div class="p-3">

           <h5 class="mb-0">45.87K</h5>

         <small class="mb-0">Overall Revenue <span> <i class="fa fa-arrow-up"></i> 2.43%</span></small>

         </div>

       </div>

       <div class="col-12 col-lg-6">

         <div class="p-3">

           <h5 class="mb-0">23.8K</h5>

         <small class="mb-0">Monthly Revenue <span> <i class="fa fa-arrow-up"></i> 12.65%</span></small>

         </div>

       </div>

     </div>

     

    </div>

   </div>



     <div class="col-12 col-lg-4 col-xl-4">

        <div class="card">

           <div class="card-header">Users monthly

             <div class="card-action">

             <div class="dropdown">

             <a href="javascript:void();" class="dropdown-toggle dropdown-toggle-nocaret" data-toggle="dropdown">

              <i class="icon-options"></i>

             </a>

              <div class="dropdown-menu dropdown-menu-right">

              <a class="dropdown-item" href="javascript:void();">Action</a>

              <a class="dropdown-item" href="javascript:void();">Another action</a>

              <a class="dropdown-item" href="javascript:void();">Something else here</a>

              <div class="dropdown-divider"></div>

              <a class="dropdown-item" href="javascript:void();">Separated link</a>

               </div>

              </div>

             </div>

           </div>

           <div class="card-body">

              <div class="chart-container-2">

                 <canvas id="chart2"></canvas>

        </div>

           </div>

           <div class="table-responsive">

             <table class="table align-items-center">

               <tbody>

                 <tr>

                   <td><i class="fa fa-circle mr-2" style="color: #14abef"></i> Direct</td>

                   <td>56</td>

                   <td>+55%</td>

                 </tr>

                 <tr>

                   <td><i class="fa fa-circle mr-2" style="color: #02ba5a"></i>App</td>

                   <td>02</td>

                   <td>+25%</td>

                 </tr>

                 <tr>

                   <td><i class="fa fa-circle mr-2" style="color: #d13adf"></i>Website</td>

                   <td>02</td>

                   <td>+15%</td>

                 </tr>

                 <tr>

                   <td><i class="fa fa-circle mr-2" style="color: #fba540"></i>Other</td>

                   <td>05</td>

                   <td>+5%</td>

                 </tr>

               </tbody>

             </table>

           </div>

         </div>

     </div>

  </div><!--End Row-->

     

      <!--End Dashboard Content-->



@endsection