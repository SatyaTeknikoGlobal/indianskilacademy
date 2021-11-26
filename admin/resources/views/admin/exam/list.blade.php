@extends('admin/layout')

@section('examination')

active

@endsection

@section('exam')

active

@endsection

@section('content')

    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Exam</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Exam</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('exam.create')}}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><i class="zmdi zmdi-account "></i>  Exams List </div>

            <div class="card-body">

              <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>Exam ID</th>

                        <th>Title</th>

                        <th>Image</th>

                        <th>Start Date</th>

                        <th>End Date</th>

                        <th>Time</th>

                        <th>Cutoff Reward Mark</th>

                        <th>No of Question</th>



                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @php

                    $i=1

                    @endphp

                    @foreach($data as $row)

                    <tr>

                        <td>{{$row->id}}</td>

                        <td>{{$row->title}}</td>

                        <td> @if(isset($row->image) && is_file(public_path('images/exam/' .$row->image)))

                              <img src="{{URL::asset('images/exam')}}/{{$row->image}}" alt="profile-image" class="profile" height="80px" width="80px">

                             @endif

                        </td>

                        <td class="text-wrap">{{$row->start_date}}</td>

                        <td class="text-wrap">{{$row->end_date}}</td>

                        <td>

                         {{$row->session_time}} mins

                        </td>

                          <td>

                         {{$row->reward_mark}}

                        </td>

                         <td>

                       <?php 
                       $exam_questions = DB::table('exam_question')->where('exam_id',$row->id)->count();

                       echo $exam_questions;

                       ?>

                        </td>

                        <td>

                           <!--  <a href="{{ route('exam.show', $row->id) }}" class="btn btn-outline-primary btn-sm flex">Add Question</a> -->

                               <button type="button" class="btn btn-outline-dark btn-sm" data-toggle="modal" data-target="#SlidesModel{{$row->id}}">

                              Import Question

                            </button>
                            <br>
                            <a href="{{ route('report.show', ['id'=>$row->id]) }}" class="btn btn-warning">Report</a>
                            <br>

                            <a href="{{ route('exam.edit', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>



                        

                              <!-- Modal -->

                            <div class="modal fade" id="SlidesModel{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                <div class="modal-dialog" role="document">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title" id="exampleModalLabel">Import Question</h5>
                                             <a href="{{URL::asset('uploads/isaquestion.xlsx')}}" download="" class="btn btn-sm btn-dark mx-2">Sample</a>
                                            <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                <span aria-hidden="true">&times;</span>

                                            </button>

                                        </div>

                                        <div class="modal-body">

                                                    @if ($errors->any())

                                                        @foreach ($errors->all() as $error)

                                                            <div id="fadeout-msg" class="alert alert-danger">

                                                                {{ $error }}

                                                            </div>

                                                        @endforeach

                                                    @endif

                                                    <form action="{{ url ('importQuestionExamWise') }}" method="post" enctype="multipart/form-data">

                                                        @csrf

                                                        <input type="hidden" name="exam_id" value="{{$row->id}}">

                                                        <div class="form-group row">

                                                            <label for="input-26" class="col-sm-2 col-form-label">Files (xls/xlsx)</label>

                                                          </div>

                                                          <div class="form-group row">

                                                            <div class="col-sm-10">

                                                                <input type="file" class="form-control-file" id="input-26" name="select_file"  required="">

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

                                        <div class="modal-footer">

                                            <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>

                                        </div>

                                    </div>

                                </div>

                            </div>

                            <form action="{{ route('exam.destroy',$row->id) }}" method="POST" id="delete_record">

                              @csrf

                              @method('DELETE')

                            <button href="javascript:void(0);" type="submit" class="btn btn-danger btn-sm" onclick="return confirm('You Want to Delete this?')"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button></td>

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