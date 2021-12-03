@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('subject')

active

@endsection

@section('content')



<div class="container-fluid">

  <!-- Breadcrumb-->

  <div class="row pt-2 pb-2">

    <div class="col-sm-9">

      <h4 class="page-title">Manage  Courses</h4>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

        <li class="breadcrumb-item active"><a href="javaScript:void();">Courses</a></li>

      </ol>

    </div>

    <div class="col-sm-3">

     <div class="btn-group float-sm-right">

      <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('subject.index') }}">List</a>

    </div>

  </div>

</div>

<!-- End Breadcrumb-->

<div class="card">

  <div class="card-header">Edit Courses</div>

  <div class="card-body">

   @if ($errors->any())

   @foreach ($errors->all() as $error)

   <div id="fadeout-msg" class="alert alert-danger">

    {{ $error }}

  </div>

  @endforeach

  @endif

  @foreach($data as $r)

  <form action="{{ url ('subject_update')}}" method="post" enctype="multipart/form-data">

    <input type="hidden" name="id" value="{{$r->id}}">

    @csrf

    <div class="form-group row">

      <label for="basic-select" class="col-sm-2 col-form-label">Select Category *</label>

      <div class="col-sm-10">

        <select class="form-control form-control-rounded" id="large-select" name="board_id" required="">

          <option readonly>Select Category</option>

          @foreach($board as $row)

          @if($row->id==$r->board_id)

          <option value="{{$row->id}}" selected="">{{$row->board_name}}</option>

          @else

          <option value="{{$row->id}}">{{$row->board_name}}</option>

          @endif

          @endforeach

        </select>

      </div>

    </div>

    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Title *</label>

      <div class="col-sm-10">

        <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Title" name="title" value="{{$r->title}}" required="">

      </div>

    </div>

    

    <div class="form-group row">

      <label for="basic-textarea" class="col-sm-2 col-form-label">Description</label>

      <div class="col-sm-10">

        <textarea rows="4" name="description" class="form-control form-control-rounded" id="description" value="{{$r->description}}">{{$r->description}}</textarea>

      </div>

    </div>

    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Course Thumbnail *</label>

      <div class="col-sm-10">
<strong style="color:red;">(Image Size should be 16:9 ratio) </strong>
        <input type="file" class="form-control-file" id="input-26"  name="image">

        @if(isset($r->image) && is_file(public_path('images/subject/' .$r->image)))

        <img src="{{URL::asset('images/subject')}}/{{$r->image}}" alt="profile-image" class="profile" height="80px" width="80px">

        @endif

      </div>

    </div>



    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">About Course *</label>

      <div class="col-sm-10">

        <input type="file" class="form-control-file" id="input-26" name="about" >
        @if(isset($r->about) && is_file(public_path('images/subject/pdf/' .$r->about)))

        <a href="{{URL::asset('images/subject/pdf/'.$r->about)}}" target="_blank"><img src="{{URL::asset('images/subject/pdficon.png')}}" alt="profile-image" class="profile" height="50px" width="50px"></a>

        @endif

      </div>
      

    </div>


    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Contents *</label>

      <div class="col-sm-10">

        <input type="file" class="form-control-file" id="input-26" name="contents" >
        @if(isset($r->contents) && is_file(public_path('images/subject/pdf/' .$r->contents)))

        <a href="{{URL::asset('images/subject/pdf/'.$r->contents)}}" target="_blank"><img src="{{URL::asset('images/subject/pdficon.png')}}" alt="profile-image" class="profile" height="50px" width="50px"></a>

        @endif

      </div>
      

    </div>

    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Batch Schedule *</label>

      <div class="col-sm-10">

        <input type="file" class="form-control-file" id="input-26" name="batch_schedule" >
        @if(isset($r->batch_schedule) && is_file(public_path('images/subject/pdf/' .$r->batch_schedule)))

        <a href="{{URL::asset('images/subject/pdf/'.$r->batch_schedule)}}" target="_blank"><img src="{{URL::asset('images/subject/pdficon.png')}}" alt="profile-image" class="profile" height="50px" width="50px"></a>

        @endif
      </div>
      

    </div>


    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Priority</label>

      <div class="col-sm-10">

        <input type="text" class="form-control form-control-rounded" id="input-26" name="priority" value="{{$r->priority}}" >

      </div>

    </div>




            <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Average Rating</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" name="avg_rating" value="{{$r->avg_rating}}">

            </div>

          </div>



            <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Total Rating</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" value="{{$r->total_rating}}" name="total_rating" >

            </div>

          </div>





            <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Selling Price</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" value="{{$r->price}}" name="price" >

            </div>

          </div>


            <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Mrp</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" value="{{$r->mrp}}" id="input-26" name="mrp" >

            </div>

          </div>


            <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Tags</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" value="{{$r->tag}}" id="input-26" name="tag" >

            </div>

          </div>



              <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">More Info</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" value="{{$r->more_info}}" id="input-26" name="more_info" >

            </div>

          </div>


              <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Link</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" value="{{$r->link}}" id="input-26" name="link" >

            </div>

          </div>




    <div class="form-group row">

      <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Status *</label>

      <div class="col-sm-10">

        <select class="form-control form-control-rounded" name="status" id="status" required="">

         @if($r->status=='Y')

         <option value="Y" selected="">Active</option>

         <option value="N">Deactive</option>

         @else

         <option value="Y">Active</option>

         <option value="N"  selected="">Deactive</option>

         @endif

       </select>

     </div>

   </div>

   <div class="form-group row">

    <label class="col-sm-2 col-form-label"></label>

    <div class="col-sm-10">

      <button type="submit" class="btn btn-dark btn-round px-5"><i class="icon-lock"></i> Submit</button>

    </div>

  </div>

</form>

@endforeach

</div>

</div>
<script type="text/javascript">
 CKEDITOR.replace('description');
</script>

@endsection
