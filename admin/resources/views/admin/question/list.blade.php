@extends('admin/layout')

@section('examination')

active

@endsection

@section('question')

active

@endsection

@section('content')

<div class="container-fluid">

    <!-- Breadcrumb-->

    <div class="row pt-2 pb-2">

        <div class="col-sm-9">

            <h4 class="page-title">Manage Question</h4>

            <ol class="breadcrumb">

                <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

                <li class="breadcrumb-item active"><a href="javaScript:void();">Question</a></li>

            </ol>

        </div>

        <div class="col-sm-3">

            <div class="btn-group float-sm-right">

              <!--   <a type="button" class="btn btn-primary mx-2" href="{{  route('question.create') }}">Add</a>

                <button type="button" class="btn btn-primary" data-toggle="modal" data-target="#Qustionimport">
                    IMPORT
                </button> -->
            </div>
            <!-- Modal -->
            <div class="modal fade" id="Qustionimport" tabindex="-1" role="dialog" aria-labelledby="exampleModalCenterTitle" aria-hidden="true">
                <div class="modal-dialog" role="document">
                  <div class="modal-content">
                    <div class="modal-header">
                      <h5 class="modal-title" id="exampleModalLongTitle">Import CSV File</h5>
                      <a href="{{URL::asset('uploads/iasgyanquestion.csv')}}" download="" class="btn btn-sm btn-dark mx-2">Sample</a>
                      <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                        <span aria-hidden="true">&times;</span>
                    </button>
                </div>
                @if ($errors->any())
                <div class="alert alert-danger">
                    <ul>
                        @foreach ($errors->all() as $error)
                        <li>{{ $error }}</li>
                        @endforeach
                    </ul>
                </div>  
                @endif
                <form role="form" method="post" action="{{ url ('uploadQuestionFile') }}" id="add_contant_form" enctype='multipart/form-data'>
                    @csrf
                    <div class="modal-body">
                        <div class="row">

                            <div class="col">

                                <div class="form-group">

                                    <label for="validationCustom08">Select Courses</label>

                                    <select class="form-control valid" name="boards" required="" id="boards" aria-invalid="false" onchange="getSubject()" required="">

                                        <option selected="" disabled="">Select Courses</option>
                                        @foreach($boards as $row)

                                        <option value="{{$row->id}}">{{$row->board_name}}</option>

                                        @endforeach

                                    </select>

                                </div>

                            </div>

                            <div class="col">

                                <div class="form-group">

                                    <label for="validationCustom09" id="select_class">Select SubCOurses</label>

                                    <select class="form-control valid" name="subject" id="subject_list" onchange="getChapter()" required="" aria-invalid="false" required="">



                                    </select>

                                </div>

                            </div>

                        </div>

                        <div class="row">

                            <div class="col">

                                <div class="form-group">

                                    <label for="validationCustom08">Select Programs</label>

                                    <select class="form-control valid" name="chapter" id="chapter_list" onchange="getTopic();" required="" aria-invalid="false" required="">



                                    </select>

                                </div>

                            </div>


                             <div class="col">

                                <div class="form-group">

                                    <label for="validationCustom09" id="select_class">Select Subject</label>

                                    <select class="form-control valid" name="subject_name"  aria-invalid="false" required="">
                                        <?php if(!empty($subtopics)){
                                            foreach($subtopics as $sub){
                                            ?>

                                            <option value="{{$sub->subject_name}}">{{$sub->subject_name}}</option>

                                        <?php }}?>


                                    </select>

                                </div>

                            </div>


                        <?php /*
                        <div class="col">

                            <div class="form-group">

                                <label for="validationCustom09" id="select_class">Select Topic*</label>

                                <select class="form-control valid" name="topic" required="" id="topic_list" aria-invalid="false" required="">



                                </select>

                            </div>

                        </div>
                        */?>



                    </div>

                    <div class="row">
                        <div class="col">
                            <div class="form-group">
                              <label>File* (import only csv file)</label>
                              <input name="csv_import" type="file" required class="form-control-file" required="">
                          </div>
                      </div>
                  </div>
                  

              </div>
              <div class="modal-footer">
                  <button type="button" class="btn btn-dark" data-dismiss="modal">Close</button>
                  <button type="submit" name='submit' class="btn btn-primary">Submit</button>
              </div>
          </form>
      </div>
  </div>

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

        <form action="" method="">



            <div class="row">

                <div class="col">

                    <div class="form-group">

                        <label for="validationCustom08">Select Courses</label>

                        <select class="form-control valid" name="boards"  id="boards1" aria-invalid="false" onchange="getSubject1()">

                            <option selected="" disabled="">Select Courses</option>
                            @foreach($boards as $row)

                            <option value="{{$row->id}}">{{$row->board_name}}</option>

                            @endforeach

                        </select>

                    </div>

                </div>

                <div class="col">

                    <div class="form-group">

                        <label for="validationCustom09" id="select_class">Select SubCourses</label>

                        <select class="form-control valid" name="subject" id="subject_list1" onchange="getChapter1()"  aria-invalid="false">



                        </select>

                    </div>

                </div>

            </div>

            <div class="row">

                <div class="col">

                    <div class="form-group">

                        <label for="validationCustom08">Select Programs</label>

                        <select class="form-control valid" name="chapter" id="chapter_list1" onchange="getTopic();"  aria-invalid="false">



                        </select>

                    </div>

                </div>
                
                        <div class="col">

                            <div class="form-group">

                               <label for="validationCustom08">Select Exam</label>

                        <select class="form-control valid" name="exam_id" id="exam_id"  aria-invalid="false">
                            <option value="" selected disabled>Select Exams</option>
                            <?php 
                            if(!empty($exams)){
                                foreach($exams as $exam){



                                    ?>
                                    <option value="{{$exam->id}}">{{$exam->id}} -- {{$exam->title}}</option>

                                <?php }
                            }
                            ?>

                        </select>
                            </div>

                        </div>
        
        </div>



    </form>

    <hr>

</div>



























<div class="row">

    <div class="col-lg-12">

        <div class="card">

            <div class="card-header"><i class="fa fa-book "></i> Questions </div>

            <div class="card-body">

                <div class="table-responsive">

                    <table id="default" class="table table-bordered">

                        <thead>

                            <tr>

                                <th>#</th>

                                <th>Question</th>

                                <th>Marks</th>

                                <th>Diffculty Level</th>

                                <th>Action</th>

                            </tr>

                        </thead>



                    </table>

                </div>

            </div>

        </div>

    </div>

</div>

<!-- End Row-->

</div>

<script src="https://code.jquery.com/jquery-1.9.1.js"></script>

<script src="https://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

<script src="https://cdn.ckeditor.com/4.13.1/basic/ckeditor.js"></script>

<script>

    function getSubject() {

        boards = $("#boards").val();

        class_id = $("#class").val();

        $.ajax({

            type: "POST",

            url: "{{ url ('/getSubjectList') }}",

            data: {'board_id':boards,'class_id':class_id,'_token':'{{ csrf_token() }}'},

            success:function(data){

                var data = jQuery.parseJSON(data);

                $('#subject_list').html(data.subjectList);

                $('#questionList').html(data.questionList);

            }

        })
    }


    function getSubject1() {

        boards = $("#boards1").val();


        $.ajax({

            type: "POST",

            url: "{{ url ('/getSubjectList') }}",

            data: {'board_id':boards,'_token':'{{ csrf_token() }}'},

            success:function(data){

                var data = jQuery.parseJSON(data);

                $('#subject_list1').html(data.subjectList);

                $('#questionList').html(data.questionList);

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

    function getChapter1() {

        subject_id  = $("#subject_list1").val();

        $.ajax({

            type: "POST",

            url: "{{ url ('/getSubject') }}",

            data: {id:subject_id,'_token':'{{ csrf_token() }}'},

            success:function(data){

                $('#chapter_list1').html(data);

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
    $(document).ready(function() {

        get_question_datatable();

        function get_question_datatable(board ='',subject='',chapter='',exam_id=''){
            var oTable = $('#default').DataTable({
                processing: true,
                serverSide: true,
                searching: false,
                ajax: {
                  url: '{{ route("question.getCustomFilter") }}',
                  data:{
                    board:board, subject:subject,chapter:chapter,exam_id:exam_id
                }
            },

            columns: [
            {data: 'id', name: 'id'},
            { "data": null, "render":function (data, type, full, meta){
              var question = '';
              if (full['e_question']!=null && full['e_question']!='') {
                 question = full['e_question'];
             }
             else{
                question = full['h_question'];
            }
            return question;
        } },
        {data: 'marks', name: 'marks'},
        {data: 'difficulty_level', name: 'difficulty_level'},
        {data: 'id', name: 'updated_at'}
        ],
        "columnDefs": [{"render": createManageBtn, "data":null, "targets": [4]}],

    });
        }


        // $('#search-form').on('submit', function(e) {
        //     oTable.draw();
        //     e.preventDefault();
        // });


        $('#boards1').change(function() {
          var board = $('#boards1').val();
          var subject='';
          var chapter='';

          var exam_id = $('#exam_id').val();

  //alert(board);
  $('#default').DataTable().destroy();

  get_question_datatable(board,subject,chapter,exam_id);

});

        $('#subject_list1').change(function() {
          var subject = $('#subject_list1').val();
          var board = $('#boards1').val();
          var exam_id = $('#exam_id').val();

          var chapter='';
          $('#default').DataTable().destroy();

          get_question_datatable(board,subject,chapter,exam_id);


      });



        $('#exam_id').change(function() {
          var subject = $('#subject_list1').val();
          var board = $('#boards1').val();
          var exam_id = $('#exam_id').val();

          var chapter='';
          $('#default').DataTable().destroy();

          get_question_datatable(board,subject,chapter,exam_id);


      });



        $('#chapter_list1').change(function() {
          var chapter = $('#chapter_list1').val();
          var subject = $('#subject_list1').val();
          var board = $('#boards1').val();
          var exam_id = $('#exam_id').val();
          $('#default').DataTable().destroy();
          
          get_question_datatable(board,subject,chapter,exam_id);


      });















    });



</script>

<script type="text/javascript">
    function createManageBtn(id) {
        return '<a href="question/'+id+'" class="btn btn-primary btn-sm flex"><i class="fa fa-eye" aria-hidden="true"></i> View</a> <button onclick="deleteQuestion('+id+');" type="submit" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Delete</button>';
    }
    function deleteQuestion(id) {
       if(!confirm('You Want to Delete this?')){
        return false;
    }
    $.ajax({

        type: "POST",

        url: "{{ url ('/deleteQuestion') }}",

        data: {id:id,'_token':'{{ csrf_token() }}'},

        success:function(data){

           if (data==1) {
             Swal.fire({

                icon: 'success',

                title: 'Question Deleted.',

                showConfirmButton: false,

                timer: 2500

            });

             location.reload();

         }  
     }

 });

}
</script>

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