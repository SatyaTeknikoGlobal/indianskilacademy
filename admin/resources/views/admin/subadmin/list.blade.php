@extends('admin/layout')

@section('users')

active

@endsection

@section('subadmin')

active

@endsection

@section('content')

    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage SubAdmins</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Subadmin</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('subadmin.create') }}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><i class="fa fa-users"></i> SubAdmin List </div>

            <div class="card-body">

              <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Name</th>

                        <th>Role</th>

                        <th>Email</th>

                        <th>Phone</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @php

                    $i=1

                    @endphp

                    @foreach($data as $row)

                    <tr>

                        <td>{{$i++}}</td>

                        <td>{{$row->name}}</td>

                        <td>
                            @if($row->is_admin==0)

                              Subadmin

                            @else

                              Accountant

                            @endif

                        </td>


                        <td>{{$row->email}}</td>

                        <td>{{$row->phone}}</td>

                        <td>

                            <a href="{{ route('subadmin.edit', $row->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                            <div class="modal fade" id="exampleModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel{{$row->id}}" aria-hidden="true">
                                                  <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel{{$row->id}}">Change Password</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                      <div class="modal-body">
                                                       <form action="{{ route('myprofile.update', $row->id)}}" method="post" enctype="multipart/form-data">

                                                        <input type="hidden" name="subadmin" value="subadmin">
                                                        @csrf

                                                        @method('PUT')

                                                       <div class="row">

                                                        <label for="input-26" class="col-sm-2 col-form-label">Password</label>

                                                        <div class="col-sm-10">

                                                        <input type="text" class="form-control"  placeholder="Enter Password" name="password" required="" >
                                                        </div>

                                                      </div>
                                                      <div class="row">

                                                          <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Re-Password</label>

                                                           <div class="col-sm-10">

                                                            <input type="text" class="form-control" placeholder="Enter Confirm Password" name="password_confirmation" required="">

                                                        </div>
                                                      </div>         
                                                
                                                       <div class="row">

                                                        <label class="col-sm-2 col-form-label"></label>

                                                        <div class="col-sm-10">

                                                        <button type="submit" class="btn btn-dark btn-round px-5"><i class="icon-lock"></i> Submit</button>

                                                        </div>

                                                      </div>

                                                      </form>
                                                          </div>
                                                      </div>
                                                    </div>
                                                  </div>
                                                </div>
                                        <button href="javascript:void(0);" type="submit" class="btn btn-warning btn-sm" data-toggle="modal" data-target="#exampleModal{{$row->id}}"><i class="fa fa-lock" aria-hidden="true"></i> Change Password</button>
                            <form action="{{ route('subadmin.destroy',$row->id) }}" method="POST" id="delete_record">

                              @csrf

                              @method('DELETE')

                            <button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('You Want to Delete this?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>

                             </form>

                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>

           

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