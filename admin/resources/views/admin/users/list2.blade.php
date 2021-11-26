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

                            <table id="" class="table table-bordered">

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
                                    <?php if(!empty($users)){
                                        foreach($users as $user){

                                            //echo count($users);
                                        ?>
                                        <tr>
                                            <td>{{$user->id}}</td>
                                            <td><a href="{{ url('app_users/'.$user->id) }}">{{$user->name}}</a> 
                                                <br>
                                                {{$user->email}}
                                            </td>
                                            <td>{{$user->phone}}</td>

                                            <td>{{$user->phone}}</td>
                                            <td>{{$user->state_id}}</td>
                                            <td>{{$user->phone}}</td>


                                            <td>
                                                <a href='{{ url ("app_users/".$user->id.'/edit') }} class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                                            </td>

                                        </tr>



                                    <?php }}?>
                             
                                </tbody>


                                 {{ $users->links() }}


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
     <script src="//cdn.datatables.net/1.10.24/js/jquery.dataTables.min.js"></script>

<script type="text/javascript">
   // var table = $('#default').DataTable({
   // ordering: false,
   // processing: true,
   // serverSide: true,
   // ajax: '{{ route("get_users")}}',
   // columns: [
   // { data: 'id', name: 'id' },
   // { data: 'name', name: 'name' ,searchable: false, orderable: false},
   // { data: 'phone', name: 'phone'},
   



   // { data: 'state', name: 'state' },
   // { data: 'city', name: 'city' },
   // { data: 'status', name: 'status' },
//    { data: 'action', searchable: false, orderable: false }

//    ],
// });

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