@extends('admin/layout')

@section('manage_content')

active

@endsection

@section('contents')

active

@endsection

@section('content')

    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Contents</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Contents</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('content.create') }}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->
      <form method="post" action="{{url('/new/content')}}">
         {{ csrf_field() }}
      <div class="row">

        <div class="col-lg-12">

          <div class="card">
            <div style="display: flex;">
            <div class="card-header"><i class="fa fa-clipboard"></i> Contents List </div>
            <button type="submit" class="btn btn-danger" onclick="return confirm('Are You want to Delete')" style="float: right;">Delete</button >
            </div>

            <div class="card-body">

              <div class="table-responsive">
              <!-- //default-datatable -->
              <table id="" class="table table-bordered">

                <thead>

                    <tr>
                        <th></th>
                        <th>#</th>
                        <th>Course</th>
                        <th>Sub Courses</th>
                        <th>Program</th>
                
                        <th>No of videos</th>
                        <th>No of notes</th>
                        <th>Is Paid</th>
                        <th>Is Free</th>
                        <th>Status</th>
                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @php

                    $i=1

                    @endphp

                    @foreach($data as $key=> $row)
                    <?php
                    $topic_id = $row->topics_id; 
                    $topic_course = $row->topic_course; 
                    $topic_sub = $row->topic_sub; 
                    $topic_chap = $row->topic_chap; 
                    $course = DB::table('boards')->where('id',$topic_course)->first();
                    $subjects = DB::table('subjects')->where('id',$topic_sub)->first();
                    $chapters = DB::table('chapters')->where('id',$topic_chap)->first();
                    $topics = DB::table('topics')->where('id',$topic_id)->first();
                    

                    ?>
                    <tr>
                        <td><input type="checkbox" name="content_delete[]" value="{{$row->id}}"></td>
                        <td>{{$i++}}</td>

                        <td>{{ isset($course->board_name) ? $course->board_name : ''}}</td>
                        <td>{{ isset($subjects->title) ? $subjects->title : ''}}</td>
                        <td>{{ isset($topics->name) ? $topics->name : ''}}</td>

                        <td><?php 
                           $no_of_video[$key] = DB::table('contents')->where(['type'=>'video','topic_id'=>$row->topic_id])->count();
                           echo $no_of_video[$key];
                        ?>
                        </td>
                         <td><?php 

                           $no_of_notes[$key] = DB::table('contents')->where(['type'=>'notes','topic_id'=>$row->topic_id])->count();
                           echo $no_of_notes[$key];
                        ?>
                        </td>
                     
                        <td>{{$row->is_paid}}</td>

                        <td>{{$row->is_free}}</td>
                        <td>

                          @if($row->status!='N')

                            <span class="badge badge-success">Active</span>

                            @else

                            <span class="badge badge-danger">Deactive</span>

                            @endif

                        <td>
                            
                         <!--    <a href="{{ route('content.edit', $row->id) }}" class="btn btn-primary btn-sm"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a> -->

                          <a href="{{ route('content.show', $row->topic_id) }}" class="btn btn-info btn-sm"><i class="fa fa-eye" aria-hidden="true"></i> View</a>
                        </td>

                    </tr>

                    @endforeach

                </tbody>

            </table>
          <div style="float: right;">
             {{$data->links()}}
          </div>
            </div>
              
            </div>

          </div>

        </div>

      </div><!-- End Row-->
 </form>
<!--start overlay-->

		  <div class="overlay toggle-menu"></div>

		<!--end overlay-->

    </div>

   

@endsection