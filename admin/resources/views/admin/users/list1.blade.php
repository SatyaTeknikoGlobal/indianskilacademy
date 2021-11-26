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

                <h4 class="page-title">Manage Users</h4>

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

                    <li class="breadcrumb-item active"><a href="javaScript:void();">User</a></li>

                </ol>

            </div>

            <!-- <div class="col-sm-3">

                <div class="btn-group float-sm-right">

                    <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('app_users.create') }}">Add</a>

                </div>

            </div> -->

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

                                    <th>User</th>

                                    <th>Phone</th>

                                    <th>Courses</th>

                                    <th>Location</th>

                                    <th>Referral </th>

                                    <th>Action</th>

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
              url: '{{ route("get_users")}}',
              data: function (d) {

              }
          },
         
          columns: [
              {data: 'id', name: 'id'},
              { "data": 'name', "render":function (data, type, full, meta){
                    return   '<a href="{{ url("app_users") }}/'+full['id']+'">'+full['name']+'</a><br>'+full['email']+'';
                    } },
               { "data": 'phone', "render":function (data, type, full, meta){
                    return  '<b>Phone :-</b>'+full['phone']+'<br>'+'<b>DOB :-</b>'+full['date_of_birth']+'';
               } },
                 { "data": 'board_name', "render":function (data, type, full, meta){
                    return  'Course :-'+full['board_name'];
                } },
               { "data": 'state_name', "render":function (data, type, full, meta){
                    return  'State :-'+full['state_name']+'</a><br>'+'City :-'+full['city_name']+'';
                } },
                 { "data": 'state_name', "render":function (data, type, full, meta){
                    if (full['wallet']==null) {
                        full['wallet']=0.00;
                    }
                    return  'Referral By :-'+full['referredBy']+'<br>'+'Wallet :-'+full['wallet']+'';
                } },
                { "data": null, "render":function (data, type, full, meta){
                        return '<a href="{{ url ("app_users") }}/'+full["id"]+'/edit" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a><a href="{{ url("reset_device")}}/'+full["id"]+'" class="btn btn-info btn-sm"><i class="fa fa-lock" aria-hidden="true"></i> Reset Device</a><form action="{{ url("app_users") }}/'+full["id"]+'" method="POST">@csrf @method("DELETE")<button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm("You Want to Delete this?")"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></form></td>';
                    } },
          ],
        });

        $('#search-form').on('submit', function(e) {
            oTable.draw();
            e.preventDefault();
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