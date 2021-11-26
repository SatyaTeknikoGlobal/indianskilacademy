@extends('admin/layout')
@section('manage_content')
    active
@endsection
@section('slides')
    active
@endsection
@section('content')

    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Manage Notes</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="javaScript:void();">Notes</a></li>
                </ol>
            </div>
            <div class="col-sm-3">
                <div class="btn-group float-sm-right">
                    <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('notes.index') }}">List</a>
                </div>
            </div>
        </div>
        <!-- End Breadcrumb-->
        <div class="card">
            <div class="card-header">Add Notes</div>
            <div class="card-body">
                @if ($errors->any())
                    @foreach ($errors->all() as $error)
                        <div id="fadeout-msg" class="alert alert-danger">
                            {{ $error }}
                        </div>
                    @endforeach
                @endif
                <form action="{{ route ('notes.store') }}" method="post" enctype="multipart/form-data">
                    @csrf
                    <div class="form-group row">
                        <label for="basic-select" class="col-sm-2 col-form-label">Select Content</label>
                        <div class="col-sm-10">
                            <select class="form-control form-control-rounded" id="large-select" name="content_id">
                                <option readonly>Select Content</option>
                                @foreach($data as $row)
                                    <option value="{{$row->id}}">{{$row->title}}</option>
                                @endforeach
                            </select>
                        </div>
                    </div>
                    <div class="form-group row">
                        <label for="input-26" class="col-sm-2 col-form-label">Notes</label>
                        <div class="col-sm-10">
                            <input type="file" class="form-control-file" id="input-26" name="notes">
                        </div>
                    </div>
                    <div class="form-group row">
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
    <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>
    @if ($message = Session::get('success'))
        <script>
            Swal.fire({
                icon: 'success',
                title: '{{ $message }}',
                showConfirmButton: false,
                timer: 2500
            });
            setInterval(function(){ window.location.href="{{ route('slides.index')}}"}, 1500);
        </script>

    @endif
@endsection