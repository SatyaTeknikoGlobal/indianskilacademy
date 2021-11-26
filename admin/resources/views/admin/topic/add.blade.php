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

        <div class="card-header">Add Batches</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('topic.store')}}" method="post" enctype="multipart/form-data">

            @csrf

            <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Category*</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="boards_id" name="course_id"  onchange="getSubject()" required="">

                    <option readonly>Select Category</option>

                    @foreach($board as $row)

                      <option value="{{$row->id}}">{{$row->board_name}}</option>

                      @endforeach

                    </select>

                  </div>

            </div>

             <div class="form-group row">

                  <label for="basic-select" class="col-sm-2 col-form-label">Select Coursec*</label>

                  <div class="col-sm-10">

                  <select class="form-control form-control-rounded" id="subject_list" name="subject_id" onchange="getChapter()" required="">

                    <option readonly>Select Coursec</option>

                   

                    </select>

                  </div>

            </div>


           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Batches Name*</label>

            <div class="col-sm-10">

            <input type="text" class="form-control form-control-rounded" id="input-26" placeholder="Enter Batches Name" name="name" required="">

            </div>

          </div>

            

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Description*</label>

            <div class="col-sm-10">

              <textarea class="form-control" name="description" id="description"></textarea>

          </div>

          </div>


          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Image *</label>

            <div class="col-sm-10">

            <input type="file" class="form-control-file form-control-rounded" id="input-26"  name="image" required="">

            </div>

          </div>

           <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Subscription Amount</label>

            <div class="col-sm-10">

            <input type="number" class="form-control"   name="subscription_amount">

            </div>

          </div>  



          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Subscription Duration(in Days)</label>

            <div class="col-sm-10">

            <input type="text" class="form-control"   name="duration" placeholder="Enter Subscription Duratio  in Days">

            </div>

          </div>  




         <!--  <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Start Date</label>

            <div class="col-sm-10">

            <input type="date" class="form-control"   name="start_date" >

            </div>

          </div>  


          <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">End Date</label>

            <div class="col-sm-10">

            <input type="date" class="form-control"   name="end_date" >

            </div>

          </div>   -->

          

          <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">IS PAID*</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="status" id="status" required="">

                  <option value="Y" selected="">Yes</option>

                  <option value="N">No</option>

              </select>

            </div>

          </div>
<div class="form-group row">

      <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Video ID*</label>

      <div class="col-sm-10">

        <input type="text" name="hls" placeholder="Enter Ofer Video ID" class="form-control">

      </div>

    </div>



     <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Nofify Text</label>

            <div class="col-sm-10">

            <input type="text" class="form-control"   name="notify_text" >

            </div>

          </div>  
 <div class="form-group row">

              <label for="exampleInputEmail1" class="col-sm-2 col-form-label">Availability*</label>

               <div class="col-sm-10">

              <select class="form-control form-control-rounded" name="batch_status" id="batch_status" required="">

                  <option value="1" selected="">Open for Enrollment</option>

                  <option value="2">Closed for Enrollment</option>

              </select>

            </div>

          </div>

 <div class="form-group row">

            <label for="input-26" class="col-sm-2 col-form-label">Batch Duration  (Date Interval)</label>

            <div class="col-sm-10">

            <input type="text" class="form-control"   name="batch_duration" placeholder="12-05-2021 to 21-12-2021">

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
        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

        <script>

          function getSubject() {

             boards = $("#boards_id").val();


             $.ajax({

                type: "POST",

                url: "{{ url ('/getSubjectList') }}",

                data: {'board_id':boards,'_token':'{{ csrf_token() }}'},

                success:function(data){

                    var data = jQuery.parseJSON(data);

                    $('#subject_list').html(data.subjectList);

                }

             });

           }

           function getChapter() {

              subject_id  = $("#subject_list").val();

               $.ajax({

                  type: "POST",

                  url: "{{ url ('/getSubject') }}",

                  data: {id:subject_id,'_token':'{{ csrf_token() }}'},

                  success:function(data){

                      $('#chapter_list').html(data);

                  }

               });

            }

        </script>
        <script>
              CKEDITOR.replace( 'description' );
            
          </script>
@endsection