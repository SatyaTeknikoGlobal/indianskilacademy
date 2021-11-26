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

                <h4 class="page-title">Manage Exam Question</h4>

                <ol class="breadcrumb">

                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

                    <li class="breadcrumb-item active"><a href="javaScript:void();">Question</a></li>

                </ol>

            </div>

            <div class="col-sm-3">

                <div class="btn-group float-sm-right">

                    <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('exam.index') }}">Back</a>

                </div>

            </div>

        </div>

        <!-- End Breadcrumb-->

        <div class="card">

            <div class="card-header">View Question</div>

            <div class="card-body">

                @if ($errors->any())

                    @foreach ($errors->all() as $error)

                        <div id="fadeout-msg" class="alert alert-danger">

                            {{ $error }}

                        </div>

                    @endforeach

                @endif

                 <form method="GET">
                <div class="row">
                    <div class="col">
                        <div class="form-group">

                            <label for="validationCustom09" id="select_class">Select Subject</label>

                            <select class="form-control valid" name="subject" id="subject_list" onchange="getChapter()"  aria-invalid="false">

                                <option selected="" readonly value="0"> Select Subject</option>
                                @foreach($subject as $sb)

                                <option  <?php if(isset($_GET['subject'])&&$_GET['subject'].""==$sb->id.""){echo "selected";} ?>   value="{{$sb->id}}">{{$sb->title}}</option>


                                @endforeach

                            </select>

                        </div>
                    </div>
                     <div class="col">

                        <div class="form-group">

                            <label for="validationCustom08">Select Chapter</label>

                            <select class="form-control valid" name="chapter" id="chapter_list" onchange="getTopic();"  aria-invalid="false">



                            </select>

                        </div>

                    </div>

                </div>

                <div class="row">

                    <div class="col">

                        <div class="form-group">

                            <label for="validationCustom09" id="select_class">Select Topic</label>

                            <select class="form-control valid" name="topic"  id="topic_list" aria-invalid="false">



                            </select>

                        </div>

                    </div>
                    <div class="col">
                         <div class="form-group">

                              <label for="validationCustom09" id="select_class">Filter Question</label>
                              <br>
                                @if ($parameters)
                                    <div class="col-md-4">
                                        <a href="{{ Request::url() }}" class="btn btn-primary">{{ __('Clear Filters') }}</a>
                                    </div>
                                @else
                                    <div class="col-md-8">
                                         <button class="btn btn-primary ">Filter</button>
                                    </div>
                                @endif

                           
                         


                        </div>
                    </div>

                </div>


               </form>
                <hr>

            </div>

            <div class="row">

                <div class="col-lg-12">

                    <div class="card">

                        <div class="card-header"><i class="fa fa-book "></i>Add Questions </div>

                        <div class="card-body">

                            <form action="{{ url('addQuestionExam')}}" method="post">

                                @csrf

                                <input type="hidden" name="exam_id"  value="{{$id}}">


                                <input type="hidden" name="type"  value="exam">

                            <div class="table-responsive">

                                <table id="default-datatable" class="table table-bordered">

                                    <thead>

                                    <tr>

                                        <th> Select All <input name="selectquestions[]" type="checkbox" class="get_value" onchange="toggcheckboxe(this,'questions[]')" id="users[]" ></th>

                                        <th>#</th>

                                        <th>Subject</th>

                                        <th>Question</th>

                                        <th>Marks</th>

                                        <th>Diffculty Level</th>

                                    </tr>

                                    </thead>

                                    <tbody id="questionList">

                                        @php

                                        $i=1;

                                        @endphp

                                        @foreach($question as $row)

                                        <tr>

                                            <td>

                                                <input name="questions[]"  <?php foreach ($qData as  $r):

                                                        if ($row->id==$r->q_id): ?>

                                                    	<?php echo 'checked'; else:?>

                                                    <?php endif ?>

                                                    <?php endforeach ?> type="checkbox" class="get_value" id="users[]" value="{{ $row->id }}">

                                            </td>

                                            <td>{{$i++}}</td>

                                            <td>{{$row->subjects->title}}</td>

                                            <td class="text-wrap">{!! nl2br(Str::limit($row->e_question,250)) !!}</td>

                                            <td class="text-wrap">{{$row->marks}}</td>

                                            <td class="text-wrap">{{$row->difficulty_level}}</td>

                                        </tr>

                                        @endforeach

                                    </tbody>



                                </table>



                            </div>

                                <div class="col-sm-10">

                                    <button type="submit" class="btn btn-dark btn-round px-5"><i class="icon-lock"></i> Submit</button>

                                </div>

                            </form>

                        </div>

                    </div>

                </div>

            </div>

            <!-- End Row-->

        </div>

        <script>



            function getSubject() {

                boards = $("#boards").val();

                $.ajax({

                    type: "POST",

                    url: "{{ url ('/getSubjectList') }}",

                    data: {'board_id':boards,'_token':'{{ csrf_token() }}'},

                    success:function(data){

                        var data = jQuery.parseJSON(data);

                        $('#subject_list').html(data.subjectList);

                    }

                })



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

            function getTopic() {

                chapter_id  = $("#chapter_list").val();

                $.ajax({

                    type: "POST",

                    url: "{{ url ('/getTopic') }}",

                    data: {id:chapter_id,'_token':'{{ csrf_token() }}'},

                    success:function(data){

                        $('#topic_list').html(data);

                    }

                });

            }

        </script>

        <script>

            function toggcheckboxe(master, stud) {

                var cbarray = document.getElementsByName(stud);

                for (var i = 0; i < cbarray.length; i++) {

                    cbarray[i].checked = master.checked;

                }

            }

        </script>

@endsection