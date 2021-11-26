@extends('admin/layout')


@section('dashboard')

active

@endsection

@section('content')

<?php 
$colourArr = config('custom.course_color');
// print_r($colourArr);
?>
<!--Start Dashboard Content-->


<h2 style="text-transform: capitalize;">Summary</h2>




<!-- New Section  -->
<style>
  ul, #myUL {
    list-style-type: none;
  }

  #myUL {
    margin: 0;
    padding: 0;
  }

  .caret {
    cursor: pointer;
    -webkit-user-select: none; /* Safari 3.1+ */
    -moz-user-select: none; /* Firefox 2+ */
    -ms-user-select: none; /* IE 10+ */
    user-select: none;
  }

  .caret::before {
    content: "\25B6";
    color: black;
    display: inline-block;
    margin-right: 6px;
  }

  .caret-down::before {
    -ms-transform: rotate(90deg); /* IE 9 */
    -webkit-transform: rotate(90deg); /* Safari */'
    transform: rotate(90deg);  
  }

  .nested {
    display: none;
  }

  .active {
    display: block;
  }
</style>




<div class="row">

  <div class="col-lg-12">

    <div class="card">

      <div class="card-header"><i class="fa fa-clipboard"></i>All Details</div>

      <div class="card-body">

        <div class="table-responsive">

          <table id="default-datatable" class="table table-bordered">

            <thead>

              <tr>
                <th>#ID</th>
                <th>Image</th>
                <th>Package</th>
                <th>Tests</th>
                <th>Videos</th>
                <th>PDF</th>
                <th>Students</th>
                <th>Amount</th>
                <th>Created By</th>

              </tr>

            </thead>

            <tbody>
              <?php if(!empty($subscription_types) && count($subscription_types) > 0){
                foreach($subscription_types as $board){

                  $path = url('/').'/public/images/course/';
                  $imgUrl = url('/').'/public/assets/images/logo.png';

                  $board_img =  isset($board->image) ? $board->image : '' ;
                  if(!empty($board_img)){
                    $imgUrl = $path.$board_img;
                  }
                  $description = isset($board->description) ? $board->description : '';
                  $start_date = isset($board->start_date) ? $board->start_date : '';
                  $end_date = isset($board->end_date) ? $board->end_date : '';



                  $start_date = strtotime($start_date);
                  $end_date = strtotime($end_date);
                  $datediff = $end_date - $start_date;

                  $days = round($datediff / (60 * 60 * 24));
                  $month = ($days / 30.5);

                  $month = ceil($month);

                  $created = date('Y-m-d',strtotime($board->board_created));
                  $board_amount = isset($board->amount) ? $board->amount : 0;
                  $board_new_amount = isset($board->new_amount) ? $board->new_amount : 0;
                  $total_use = DB::table('users')->where('board_id',$board->board_id)->count();

                  $test = DB::table('exams')->where('board_id',$board->board_id)->count();
                  $subjects = DB::table('subjects')->where('board_id',$board->board_id)->get();
                  if(!empty($subjects) && count($subjects)){
                    foreach($subjects as $sub){
                      if($sub->board_id == $board->board_id){
                        $pdf = DB::table('contents')->where('subject_id',$sub->id)->where('type','notes')->count();
                        $video = DB::table('contents')->where('subject_id',$sub->id)->where('type','videos')->count();
                      }
                    }
                  }



                  ?>
                  <tr>
                    <td>{{$board->board_id}}</td>
                    <td><img src="{{$imgUrl}}" width="70" height="70"></td>
                    <td>

                      <ul id="myUL">

                        <li><span class="caret">{{$board->board_name}}</span>
                         <ul class="nested">
                          <?php
                          $subjects = DB::table('subjects')->where('board_id',$board->board_id)->get();
                          if(!empty($subjects) && count($subjects) > 0){
                            foreach($subjects as $sub){
                              $chapters = DB::table('chapters')->where('subject_id',$sub->id)->get();
                              ?>

                              <?php if(empty($chapters) || $chapters ==''){?>
                                <li>{{$sub->title}}


                                </li>&nbsp;&nbsp;&nbsp;<a onclick="sub_status({{$sub->id}})"><i class="fa fa-pencil"></i></a>
                                <select name="sub_status" id="sub_status{{$sub->id}}" style="display: none;" onchange="change_sub_status({{$sub->id}})">
                                  <option value="Y" <?php if($sub->status == 'Y')echo "selected"?>>Active</option>
                                  <option value="N" <?php if($sub->status == 'N')echo "selected"?>>InActive</option>
                                </select>


                              <?php }else{?>
                               <li><span class="caret">{{$sub->title}}
                               </span>&nbsp;&nbsp;&nbsp;<a onclick="sub_status({{$sub->id}})"><i class="fa fa-pencil"></i></a>

                               <select name="sub_status" id="sub_status{{$sub->id}}" style="display: none;" onchange="change_sub_status({{$sub->id}})">
                                <option value="Y" <?php if($sub->status == 'Y')echo "selected"?>>Active</option>
                                <option value="N" <?php if($sub->status == 'N')echo "selected"?>>InActive</option>
                              </select>

                              <ul class="nested">

                                <?php if(!empty($chapters) && count($chapters) > 0){foreach($chapters as $chap){
                                  $topics = DB::table('topics')->where('chapter_id',$chap->id)->get(); 
                                  ?>
                                  <?php if(empty($topics) || $topics ==''){?>
                                    <li>{{$chap->chapter_name}}</li>

                                    &nbsp;&nbsp;&nbsp;<a onclick="chap_status({{$chap->id}})"><i class="fa fa-pencil"></i></a>
                                <select name="chap_status" id="chap_status{{$chap->id}}" style="display: none;" onchange="change_chap_status({{$chap->id}})">
                                  <option value="Y" <?php if($chap->status == 'Y')echo "selected"?>>Active</option>
                                  <option value="N" <?php if($chap->status == 'N')echo "selected"?>>InActive</option>
                                </select>



                                  <?php }else{?>
                                    <li><span class="caret">{{$chap->chapter_name}}</span>
                                      &nbsp;&nbsp;&nbsp;<a onclick="chap_status({{$chap->id}})"><i class="fa fa-pencil"></i></a>
                                <select name="chap_status" id="chap_status{{$chap->id}}" style="display: none;" onchange="change_chap_status({{$chap->id}})">
                                  <option value="Y" <?php if($chap->status == 'Y')echo "selected"?>>Active</option>
                                  <option value="N" <?php if($chap->status == 'N')echo "selected"?>>InActive</option>
                                </select>



                                     <ul class="nested">
                                       <?php if(!empty($topics) && count($topics) > 0){foreach($topics as $topi){?>
                                        <li>{{$topi->name}}</li>
                                      <?php }}?>
                                    </ul>
                                  </li>


                                <?php }?>



                              <?php }}?>
                            </ul>
                          </li>
                        <?php }?>


                      <?php }}?>
                    </ul>
                  </li>

                </ul>



                <div style="display: flex">
                  <div><b>Validity: {{$days}} Days</b></div>&nbsp;
                  <div><b> Duration: {{$month}} Months</b></div>
                </div>

                <div style="display: flex">
                  <form method="post" action="" id="status_update{{$board->board_id}}">
                    {{ csrf_field() }}
                    <input type="hidden" id="board_id{{$board->board_id}}" name="board_id" value="{{$board->board_id}}">
                    <select name="status" id="status{{$board->board_id}}" onchange="update_status({{$board->board_id}})">
                     <option value="Y" <?php if($board->board_status == 'Y')echo "selected"?>>Active</option>
                     <option value="N" <?php if($board->board_status == 'N')echo "selected"?>>InActive</option>
                   </select>
                 </form>
                        <!--  &nbsp;&nbsp;&nbsp;
                         <button class="btn btn-success btn-sm">WEB HOME</button> -->
                       </div>

                     </td>
                     <td>{{$test}}</td>
                     <td title="No. of Videos">{{$video}}<a href="{{url('new/content/create/'.$board->board_id)}}" title="Add"><i class="fa fa-link" aria-hidden="true"></i></a></td>
                     <td title="No. of PDF">{{$pdf}}<a href="{{url('new/content/create/'.$board->board_id)}}" title="Add"><i class="fa fa-link" aria-hidden="true"></i></a></td>
                     <td title="No. of User">{{$total_use}}</td>
                     <td>₹<del>{{$board_amount}}</del><br>₹{{$board_new_amount}}</td>
                     <td><center>{{$board->created_by}}<br>{{$created}}</center></td>

                   </tr>

                 <?php }}?>
               </tbody>

             </table>

           </div>

         </div>

       </div>

     </div>

   </div><!-- End Row-->











   <!-- Change Sunject STatus -->
   <script type="text/javascript">

     $( document ).ready(function() {

      $('#sub_status'+sub_id).hide();
    });
     function sub_status(sub_id){
      $('#sub_status'+sub_id).toggle(100);
    }
    function change_sub_status(sub_id){

      var sub_id = sub_id;
      var sub_status = $('#sub_status'+sub_id).val();
      var _token = '{{ csrf_token() }}';
    // alert(board_id);
    // alert(status);
    $.ajax({
      url: "{{ route('board.update_sub_status') }}",
      type: "POST",
      data: {sub_status:sub_status,sub_id:sub_id},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.status == 'success'){
          alert(resp.msg);
        }else{
          alert('Not Updated');
        }
      }
    });

    }
  </script>
  <!-- End SUbject STatus -->



  <!-- Change Chapter STatus -->
   <script type="text/javascript">

     $( document ).ready(function() {

      $('#chap_status'+chap_id).hide();
    });
     function chap_status(chap_id){
      $('#chap_status'+chap_id).toggle(100);
    }
    function change_chap_status(chap_id){

      var chap_id = chap_id;
      var chap_status = $('#chap_status'+chap_id).val();
      var _token = '{{ csrf_token() }}';
    // alert(board_id);
    // alert(status);
    $.ajax({
      url: "{{ route('board.update_chap_status') }}",
      type: "POST",
      data: {chap_status:chap_status,chap_id:chap_id},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.status == 'success'){
          alert(resp.msg);
        }else{
          alert('Not Updated');
        }
      }
    });

    }
  </script>
  <!-- End Chapter STatus -->







  <script type="text/javascript">
    function update_status(id){
      var board_id = id;
      var status = $('#status'+id).val();
      var _token = '{{ csrf_token() }}';
    // alert(board_id);
    // alert(status);
    $.ajax({
      url: "{{ route('board.update_status') }}",
      type: "POST",
      data: {status:status,board_id:board_id},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){
        if(resp.status == 'success'){
          alert(resp.msg);
        }else{
          alert('Not Updated');
        }
      }
    });

  }
</script>



<script>
  var toggler = document.getElementsByClassName("caret");
  var i;

  for (i = 0; i < toggler.length; i++) {
    toggler[i].addEventListener("click", function() {
      this.parentElement.querySelector(".nested").classList.toggle("active");
      this.classList.toggle("caret-down");
    });
  }
</script>


<script type="text/javascript">
  $("#status").change(function(){
    // $('#status_update').submit();
    var _token = '{{ csrf_token() }}';
    var data = $('#status_update').serialize();
    alert(data);
    $.ajax({
      url: "{{ route('board.update_status') }}",
      type: "POST",
      data: {data:data},
      dataType:"JSON",
      headers:{'X-CSRF-TOKEN': _token},
      cache: false,
      success: function(resp){

      }
    });
  });
</script>







<!-- End New Section  -->

<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.5.1/jquery.min.js"></script>
<script>
  $( document ).ready(function() {
    setInterval(function(){
     $.get('https://app.agricoaching.in/public/api/active_users', function (data, textStatus, jqXHR) {
       $("#ActiveUser").html(+data+" <span class='float-right'><i class='fa fa-users'>");
     });
   }, 3000);  
  });
  

</script>


<!-- new -->





<!--End Row-->


<!--End Row-->



<!--End Dashboard Content-->



@endsection