@extends('admin/layout')
@section('books')
active
@endsection
@section('author')
active
@endsection
@section('content')
    <div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
		    <h4 class="page-title">Manage Author</h4>
		    <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Author</a></li>
         </ol>
	   </div>
	   <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('author.create')}}">Add</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
      <div class="row">
        <div class="col-lg-12">
          <div class="card">
            <div class="card-header"><i class="fa fa-clipboard"></i> Author List </div>
            <div class="card-body">
              <div class="table-responsive">
              <table id="default-datatable" class="table table-bordered">
                <thead>
                    <tr>
                        <th>#</th>
                        <th>Author</th>
                        <th>Image</th>
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
                          @if(isset($row->profile) && is_file(public_path('images/author/' .$row->profile)))
                          <img src="{{URL::asset('images/author')}}/{{$row->profile}}" alt="profile-image" class="profile" height="80px" width="80px">
                            @endif
                          </td>

                           <td>
                            {{date('d M Y',strtotime($row->created_at)) }} 
                          </td>
                          <td>
                            
                            {{date('d M Y',strtotime($row->updated_at)) }}
                          </td>
                        <td>
                            <a href="{{ route('author.edit', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>
                            <form action="{{ route('author.destroy',$row->id) }}" method="POST" id="delete_record">
                              @csrf
                              @method('DELETE')
                            <button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>
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