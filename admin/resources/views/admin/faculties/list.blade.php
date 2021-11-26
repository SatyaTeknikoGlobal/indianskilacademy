@extends('admin/layout')

@section('users')

active

@endsection

@section('faculties')

active

@endsection

@section('content')

    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Faculties</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Faculties</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('faculties.create')}}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><i class="zmdi zmdi-account "></i>  Faculties List </div>

            <div class="card-body">

              <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Name</th>

                        <th>Email</th>

                        <th>DOB</th>

                        <th>Education</th>

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

                        <td> <a href="{{ route('faculties.view', $row->id) }}">{{$row->name}}</a></td>

                        <td>

                          Email :- {{$row->email}}

                          <br>

                          Phone : -{{$row->phone}}

                        </td>

                        <td>{{$row->dob}}</td>

                        <td>{{$row->education}}</td>

                        <td>

                           <a href="{{ route('faculties.show', $row->id) }}" class="btn btn-info btn-sm flex"><i class="fa fa-pencil" aria-hidden="true"></i> Live Class</a>

                            <a href="{{ route('faculties.edit', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                             <!-- <a href="{{ route('faculties.view', $row->id) }}" type="submit" class="btn btn-warning btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</a> -->

                            <form action="{{ route('faculties.destroy',$row->id) }}" method="POST" id="delete_record">

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