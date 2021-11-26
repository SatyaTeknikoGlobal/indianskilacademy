@extends('admin/layout')

@section('manage_content')

active

@endsection

@section('live_classes')

active

@endsection

@section('content')

    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Liveclasses</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Liveclasses</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('live_classes.create')}}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><i class="fa fa-book"></i> Liveclasses List </div>

            <div class="card-body">

              <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Title</th>

                        <th>Image</th>

                        <th>Start Date</th>

                        <th>Time</th>

                        <th>Status</th>

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

                        <td class="text-wrap">{{$row->title}}</td>

                        <td>

                            @if(isset($row->image) && is_file(public_path('images/liveclasses/' .$row->image)))

                             <img src="{{URL::asset('images/liveclasses')}}/{{$row->image}}" alt="profile-image" class="profile" height="80px" width="80px">

                           @endif

                        </td>

                        <!-- <td class="text-wrap">{!! nl2br(Str::limit($row->description,150)) !!}</td> -->

                        <td>{{$row->start_date}}</td>

                        <td>

                          <b>Start Time : - </b>{{$row->start_time}} <br>

                          <b>End Time : - </b>{{$row->end_time}} 

                        </td>

                        <td> 

                         @if($row->status!='N')

                            <span class="badge badge-success">Active</span>

                            @else

                            <span class="badge badge-danger">Deactive</span>

                            @endif

                          </td>

                        <td>

                          @if($row->end_status=='N')

                            <a href="{{ route('live_classes.show', $row->id) }}" class="btn btn-info btn-sm flex"><i class="fa fa-hourglass-end" aria-hidden="true"></i> Live End</a>

                          @else



                          @endif

                            <a href="{{ route('live_classes.edit', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                            <form action="{{ route('live_classes.destroy',$row->id) }}" method="POST" id="delete_record">

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