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

      <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('topic.index') }}">List</a>

    </div>

  </div>

</div>

<!-- End Breadcrumb-->

<div class="card">

  <div class="card-header">Edit Batches</div>

  <div class="card-body">

   @if ($errors->any())

   @foreach ($errors->all() as $error)

   <div id="fadeout-msg" class="alert alert-danger">

    {{ $error }}

  </div>

  @endforeach

  @endif

  <form action="{{ url ('topic_update')}}" method="post" enctype="multipart/form-data">

    @csrf

    <input type="hidden" name="id" value="{{$data->id}}">








    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Batches Name*</label>

      <div class="col-sm-10">

        <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Batches Name" name="name" value="{{$data->name}}" required="">

      </div>

    </div>

   

    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Description*</label>

      <div class="col-sm-10">

        <textarea class="form-control" name="description" id="description">{{$data->description}}</textarea>

      </div>

    </div>




    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Image</label>

      <div class="col-sm-10">

        <input type="file" class="form-control-file form-control-rounded" id="input-26"  name="image">

      </div>

    </div>  



    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Subscription Amount</label>

      <div class="col-sm-10">

        <input type="number" class="form-control"   name="subscription_amount" value="{{$data->subscription_amount}}">

      </div>

    </div>  



    <div class="form-group row">

      <label for="input-26" class="col-sm-2 col-form-label">Subscription Duration(in Days)</label>

      <div class="col-sm-10">

        <input type="text" class="form-control"   name="duration" value="{{$data->duration}}" placeholder="Enter Subscription Duratio  in Days">

      </div>

    </div>  





    <div class="form-group row">

      <label for="exampleInputEmail1" class="col-sm-2 col-form-label">IS PAID *</label>

      <div class="col-sm-10">

        <select class="form-control form-control-rounded" name="status" id="status" required="">

          @if($data->is_paid=='Y')

          <option value="Y" selected="">Yes</option>

          <option value="N">No</option>

          @else

          <option value="Y">Yes</option>

          <option value="N" selected="">No</option>

          @endif

        </select>

      </div>

    </div>

    <div class="form-group row">

      <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Is Offer *</label>

      <div class="col-sm-10">

        <select class="form-control form-control-rounded" name="is_offer" id="is_offer" >
          <option value="" selected disabled>Choose offer</option>
          <option value="Y" <?php if($data->is_offer == 'Y') echo "selected"?>>Yes</option>

          <option value="N" <?php if($data->is_offer == 'N') echo "selected"?>>No</option>


        </select>

      </div>

    </div>


    <div class="form-group row">

      <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Video ID*</label>

      <div class="col-sm-10">

        <input type="text" name="hls" placeholder="Enter Ofer Video ID" value="{{$data->hls}}" class="form-control">

      </div>

    </div>
    <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Batch Status*</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="batch_status" id="batch_status" required="">
                 <option value="" selected disabled>Availability</option>
          <option value="1" <?php if($data->batch_status == '1') echo "selected"?>>Open for Enrollment</option>

          <option value="2" <?php if($data->batch_status == '2') echo "selected"?>>Closed for Enrollment</option>
                

              </select>

            </div>

          </div>

 <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Batch Duration  (Date Interval)</label>

            <div class="col-sm-10">

            <input type="text" class="form-control" value="{{$data->batch_duration}}"   name="batch_duration" placeholder="12-05-2021 to 21-12-2021">

            </div>

          </div>  

  <!--  <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Start Date</label>

            <div class="col-sm-10">

            <input type="date" class="form-control"   name="start_date" value="{{$data->start_date}}">

            </div>

          </div>  


          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">End Date</label>

            <div class="col-sm-10">

            <input type="date" class="form-control"   name="end_date" value="{{$data->end_date}}">

            </div>

          </div>   -->

          

    <div class="form-group row" id="offer_banner">

      <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Banner Image</label>

      <div class="col-sm-10">

        <input type="file" name="offer_banner">


      </div>

    </div>




        <?php if(!empty($data->offer_banner)){

          ?>

          <a href="{{url('/public/images/topic/'.$data->offer_banner)}}" target="_blank"><img src="{{url('/public/images/topic/'.$data->offer_banner)}}" height="50px" width="50px"></a>
        <?php }?>


   <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Nofify Text</label>

            <div class="col-sm-10">

            <input type="text" class="form-control"   name="notify_text" value="{{$data->notify_text}}">

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

<script>
  CKEDITOR.replace( 'description' );

</script>





<script type="text/javascript">
  $( document ).ready(function() {
   $('#offer_banner').hide();
 });


  $('#is_offer').change(function() {
    var is_off =  $(this).val();

    if(is_off == 'Y'){
     $('#offer_banner').show();

   }if(is_off == 'N'){

     $('#offer_banner').hide();
   }
 });

</script>
@endsection
