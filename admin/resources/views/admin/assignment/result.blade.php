@extends('admin/layout')

@section('users')

    active

@endsection

@section('app_users')

    active

@endsection

@section('content')

    <div class="container-fluid">

        <!-- Breadcrumb-->

        <div class="row pt-2 pb-2">

            <div class="col-sm-9">

                <h4 class="page-title">Assignment Result</h4>

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

                    <li class="breadcrumb-item active"><a href="javaScript:void();">Assignment Result</a></li>

                </ol>

            </div>

        </div>

        <!-- End Breadcrumb-->

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-header"><i class="fa fa-users"></i> Users List </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="default" class="table table-bordered">

                                <thead>

                                <tr>

                                    <th>#</th>

                                    <th>User Name</th>
                                    <th>PDF</th>
                                  

                                </tr>

                                </thead>

                                <tbody>

                             
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

<script src="https://code.jquery.com/jquery-3.5.1.js" integrity="sha256-QWo7LDvxbWT2tbbQ97B53yJnYU3WhH/C8ycbRAkjPDc="
  crossorigin="anonymous"></script>
     <script>
        $(document).ready(function() {
        var oTable = $('#default').DataTable({
        processing: true,
        serverSide: true,
          ajax: {
              url: '{{ route("get_assignment_users",["assignment_id"=>$assignment_id])}}',
              data: function (d) {
               
              }
          },
         
          columns: [
              {data: 'id', name: 'id'},

              { "data": 'name', "render":function (data, type, full, meta){
                    return  full['name']+'<br>'+full['email'];
                    } },



               { "data": 'pdf', "render":function (data, type, full, meta){
                    return  '<a target="_blank" href='+full['pdf']+ '>View</a>';
               } },

          ],
        });

        
       });
    </script>
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