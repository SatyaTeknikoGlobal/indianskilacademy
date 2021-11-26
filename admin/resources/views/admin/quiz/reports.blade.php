@extends('admin/layout')
@section('exams')
active
@endsection
@section('quiz')
active
@endsection
@section('content')




<div class="container-fluid">
 <div class="row pt-2 pb-2">
  <div class="col-sm-9">
    <h4 class="page-title">Reports</h4>
    <ol class="breadcrumb">
      <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
      <li class="breadcrumb-item active"><a href="javaScript:void();">Reports</a></li>
    </ol>

  </div>
  <div class="col-sm-3">
    <div class="btn-group float-sm-right">
            <?php
            if( !empty($results) && $results->count() > 0){
                ?>
                <form name="" method="post" action="" >
                    {{ csrf_field() }}
                    <input type="hidden" name="export_xls" value="1">

                    <?php
                    if(count(request()->input())){
                        foreach(request()->input() as $input_name=>$input_val){
                            ?>
                            <input type="hidden" name="{{$input_name}}" value="{{$input_val}}">
                            <input type="hidden" name="exam_id" value="{{$exam_id}}">
                            <?php
                        }
                    }
                    ?>

                    <button type="submit" class="btn btn-primary waves-effect waves-light" ><i class="fa fa-table"></i> Export XLS</button>
                </form>
                <?php
            }
            ?>
    </div>
  </div>
</div>
<div class="row">



  <div class="col-lg-12">



    <div class="card">



      <div class="card-header"><i class="fa fa-book"></i>Exam Reports</div>



      <div class="card-body">



        <div class="table-responsive">



          <table id="example" class="table table-bordered nowrap">



            <thead>
              <tr>
                <th>#</th>
                <th>Name</th>
                <th>Rank</th>
                <th>Marks</th>
                <th>Time</th>
              </tr>
            </thead>

            <tbody>
              <?php if(!empty($results) && count($results) > 0){
                $i = 0 ;
                foreach($results as $res){
                  $i++;
                  foreach($users as $user){
                    if($res->user_id == $user->id){
                      $name = $user->name;
                    }
                  }
                  ?>
                  <tr>
                    <td>{{$i}}</td>
                    <td>{{$name}}</td>
                    <td>{{$res->rank}}</td>
                    <td>{{$res->marks}}</td>
                    <td>{{$res->time}}</td>
                  </tr>
                <?php }}?>
              </tbody>
            </table>


            {{$results->links()}}
          </table>
        </div>



      </div>



    </div>



  </div>



</div><!-- End Row-->


<script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

@endsection
