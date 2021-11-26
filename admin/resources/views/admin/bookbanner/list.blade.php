@extends('admin/layout')

@section('books')

active

@endsection

@section('book_banner')

active

@endsection

@section('content')

    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Banners</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Banners</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('book_banner.create')}}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><i class="fa fa-book"></i> Book Banners List </div>

            <div class="card-body">

              <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Name</th>

                        <th>Image</th>

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

                    @foreach($data as $row)

                    <tr>

                        <td>{{$i++}}</td>

                        <td>{{$row->name}}</td>

                        <td>   

                          @if(isset($row->banner) && is_file(public_path('images/bookbanner/' .$row->banner)))

                          <img src="{{URL::asset('images/bookbanner')}}/{{$row->banner}}" alt="banner-image" class="banetr" height="80px" width="80px">

                            @endif

                          </td> 

                           <td>



                          @if($row->status!='N')



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

                            <a href="{{ route('book_banner.edit', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                            <form action="{{ route('book_banner.destroy',$row->id) }}" method="POST" id="delete_record">

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