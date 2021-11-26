@extends('admin/layout')

@section('subcription')

    active

@endsection

@section('histories')

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

                    <li class="breadcrumb-item active"><a href="javaScript:void();">Subcription</a></li>

                </ol>

            </div>

        </div>

        <!-- End Breadcrumb-->

        <div class="row">

            <div class="col-lg-12">

                <div class="card">

                    <div class="card-header"><i class="fa fa-users"></i> Subcription Histroy </div>

                    <div class="card-body">

                        <div class="table-responsive">

                            <table id="default-datatable" class="table table-bordered">

                                <thead>

                                <tr>

                                    <th>#</th>

                                    <th>User</th>

                                    <th>Course/Topic Name</th>

                                    <th>Sub From</th>

                                    <th>Amount</th>

                                    <th>Paid Amount</th>

                                    <th>Purchage Date</th>

                                </tr>

                                </thead>

                                <tbody>

                                @php

                                    $i=1

                                @endphp

                                @foreach($data as $row)

                                    <tr>

                                        <td>{{$i++}}</td>

                                        <td>

                                        <b>Name :-</b>   {{$row->name}}  <br>
                                        <b>Phone :-</b>  {{$row->phone}}  <br>
                                        <b>Email :-</b>  {{$row->email}}  <br>

                                        </td>

                                        <td>

                                        {{$row->type_title}}

                                        </td>

                                        <td>
                                            @if($row->txn_id==0)
                                              ADMIN

                                            @else
                                              APP
                                            @endif
                                        </td>

                                        <td>{{$row->amount}}</td>

                                        <td>{{$row->new_amount}}</td>

                                        <td>

                                       
                                       {{date('d M Y',strtotime($row->created_at)) }} 

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