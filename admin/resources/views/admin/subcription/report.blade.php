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

      <h4 class="page-title">Manage Users Subscription</h4>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

        <li class="breadcrumb-item active"><a href="javaScript:void();">Subcription</a></li>

      </ol>

    </div>

  </div>

  <!-- End Breadcrumb-->
  <?php
  $course = isset($_GET['course']) ? $_GET['course'] :'';
  $subject_id = isset($_GET['subject_id']) ? $_GET['subject_id'] :'';
  $topic_id = isset($_GET['topic_id']) ? $_GET['topic_id'] :'';

  ?>



  <form action="" method="get">
    <div class="row">
      <div class="col-lg-12">
        <div class="form-group">
          <label>Select Course</label>
          <table>
            <tr>
              <td>
                <select class="form-control" name="course" id="board_id" onchange="getSubject()">
                  <option value="" selected>Select Category</option>
                  <?php foreach($boards as $board){?>
                    <option value="{{$board->id}}" <?php if($board->id == $course) echo "selected"?>>{{$board->board_name}}</option>
                  <?php }?>
                </select>
              </td>

              <td>
                <select class="form-control" name="subject_id" onchange="getChapter()" id="subject_id">
                    <option readonly value="0">Select Courses</option>
                    <?php if(!empty($courses)){
                      foreach($courses as $course){
                      ?>

                      <option value="{{$course->id}}" <?php if($subject_id == $course->id) echo "selected";?>>{{$course->title}}</option>

                    <?php }}?>
                    
                </select>
              </td>



          <td>
                <select class="form-control" name="topic_id" id="topic_id">
                <option readonly value="0">Select Batch</option>
                   <?php if(!empty($batches)){
                      foreach($batches as $batch){
                      ?>

                      <option value="{{$batch->id}}" <?php if($topic_id == $batch->id) echo "selected";?>>{{$batch->name}}</option>

                    <?php }}?>
                </select>
              </td>






              <label>&nbsp;&nbsp;&nbsp;&nbsp;</label>
              <td style="padding : 1px 18px">
                <span><button class="btn btn-success" type="submit">Submit</button></span>
                &nbsp;
                <a class="btn btn-danger" href="{{url('subcription/create')}}" type="reset">Reset</a>
              </td>
            </tr>
          </table>
          </div>
        </div>

      </div>
    </form>


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

                    <th>Name</th>

                    <th>Phone</th>

                    <th>Email</th>

                    <th>Couse Details</th>

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
                  <?php if(count($data) > 0 && !empty($data)){?>
                  @foreach($data as $row)

                  <tr>

                    <td>{{$i++}}</td>

                    <td>

                     {{$row->name}}


                   </td>
                   <td>{{$row->phone}}
                   </td>
                   <td>{{$row->email}}</td>
                   <td>
                    <?php 
                    $boards = \App\Board::where('id',$row->board_id)->first();
                    $subjects = \App\Subject::where('id',$row->subject_id)->first();
                    $topics = \App\Topic::where('id',$row->topic_id)->first();



                    ?>

                    <p>{{$boards->board_name ?? ''}}</p>
                    <p>{{$subjects->title ?? ''}}</p>
                    <p>{{$topics->name ?? ''}}</p>



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

             <?php }?>
             </tbody>

           </table>

          {{$data->appends(request()->input())->links()}}

         </div>

       </div>

    
 <!-- End Row-->

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

<script type="text/javascript">
    function getSubject() {

             boards = $("#board_id").val();

             $.ajax({

                type: "POST",

                url: "{{ url ('/getSubjectList') }}",

                data: {'board_id':boards,'_token':'{{ csrf_token() }}'},

                success:function(data){

                    var data = jQuery.parseJSON(data);

                    $('#subject_id').html(data.subjectList);
                      $('#chapter_id').html( '<option readonly value="0">Select Chapter</option>');
                     $('#topic_id').html('<option readonly value="0">Select Program</option>');

                }

             });

           }
          function getChapter() {

              sub_id  = $("#subject_id").val();

               $.ajax({

            type: "POST",

            url: "{{ url ('/getSubject') }}",

            data: {id:sub_id,'_token':'{{ csrf_token() }}'},

            success:function(data){

                $('#topic_id').html(data);
                  //$('#topic_id').html('<option readonly value="0">Select Program</option>');

            }

        });

          }
</script>



@endsection