@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('topic')

active

@endsection

@section('content')

<div class="container-fluid">

  <!-- Breadcrumb-->

  <div class="row pt-2 pb-2">

    <div class="col-sm-9">

      <h4 class="page-title">Manage Batches</h4>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

        <li class="breadcrumb-item active"><a href="javaScript:void();">Batches</a></li>

    </ol>

</div>

<div class="col-sm-3">

 <div class="btn-group float-sm-right">
    @if (Auth::user()->is_admin==1 )
    <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('topic.create')}}">Add</a>
    @endif
</div>

</div>

</div>

<!-- End Breadcrumb-->

<div class="row">

    <div class="col-lg-12">

      <div class="card">

        <div class="card-header"><i class="fa fa-book"></i> Batches List </div>

        <div class="card-body">

          <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Category</th>

                        <th>Courses</th>

                        <th>Batches name</th>
                        <th>Duration(In Days)</th>
                        <th>Subscription Amount</th>

                        <th>Image</th>

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

                        <td>{{$row->board_name}}</td>

                        <td>{{$row->title}}</td>
                        <td>

                         {{$row->name}}
                     </td>

                     <td>
                        {{$row->duration}} Days
                    </td>
                    <td>
                        {{$row->subscription_amount}}
                    </td>


                    <td>
                      @if(isset($row->image) && is_file(public_path('images/topic/' .$row->image)))

                      <img src="{{URL::asset('images/topic')}}/{{$row->image}}" alt="image" class="profile" height="80px" width="80px">

                      @endif
                  </td>

                  <td>
                    @if (Auth::user()->is_admin==1 )

                    <a href="{{ route('topic.edit', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                    @endif
                    <a href="{{ route('topic.contents', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-file" aria-hidden="true"></i> Contents</a>

                    @if (Auth::user()->is_admin==1 )

                    <form action="{{ route('topic.destroy',$row->id) }}" method="POST" id="delete_record">

                      @csrf

                      @method('DELETE')

                      <button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('You Want to Delete this?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>

                  </form>
                  @endif
              </td>

          </tr>

          @endforeach

      </tbody>

  </table>

<!-- </div>

</div>

</div>

</div>
</div>

</div>
</div>
</div>
 -->
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