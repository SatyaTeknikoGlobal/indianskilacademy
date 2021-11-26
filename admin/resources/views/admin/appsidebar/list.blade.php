@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('app_sidebar')

active

@endsection

@section('content')


<?php 

$sidebarArr = config('custom.sidebars');

?>



    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage App Side Bar</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">App Side Bar</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  url('app_sidebar/add') }}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><i class="fa fa-clipboard"></i>Details</div>

            <div class="card-body">

              <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>
                        <th>Title</th>
                        <th>Name</th>
                        <th>File</th>
                        <th>Link</th>
                        <th>Status</th>

                        <th>Created At</th>
                        <th>Updated At</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @php

                    $i=1

                    @endphp

                    @foreach($app_sidebars as $row)

                    <tr>

                        <td>{{$i++}}</td>

                        <td>
                          {{$row->title}}

                        </td>

                         <td>
                           <?php 
                if(!empty($sidebarArr)){
                  foreach ($sidebarArr as $key => $value) {
                    if($row->bar_id == $key){
                      echo $value;
                    }
                  }}
                   
                ?>
              

                        </td>

                        <td>
                          
                       @if(isset($row->file) && is_file(public_path('images/app_sidebar/' .$row->file)))
                       @if($row->type == 'pdf')
                     <a href="{{url('public/images/app_sidebar/'.$row->file)}}" target="_blank"><img src="{{url('public/images/app_sidebar/pdf.png')}}" height="50px" width="50px"></a>
                       @endif
                        @if($row->type == 'image')
                      <a href="{{url('public/images/app_sidebar/'.$row->file)}}" target="_blank"><img src="{{url('public/images/app_sidebar/'.$row->file)}}" height="50px" width="50px"></a>
                       @endif
                      


                       @endif
                        </td>

                            <td>
                          {{$row->link}}

                        </td>




                        <td>

                          @if($row->status!='0')

                            <span class="badge badge-success">Active</span>

                            @else

                            <span class="badge badge-danger">Deactive</span>

                         
                            @endif
                          </td>
                           
                          <td>
                            {{date('d M Y',strtotime($row->created_at)) }} 
                          </td>
                          <td>
                            
                            {{date('d M Y',strtotime($row->updated_at)) }}
                          </td>
                        <td>


                            <a href="{{ route('app_sidebar.edit', $row->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                            <form action="{{ route('app_sidebar.delete',$row->id) }}" method="POST" id="delete_record">

                              @csrf


                            <button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('You Want to Delete this?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>


                             </form>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

            </div>

            </div>

          </div>

        </div>

      </div><!-- End Row-->

<!--start overlay-->

		  <div class="overlay toggle-menu"></div>

		<!--end overlay-->

    </div>

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