@extends('admin/layout')

@section('examination')

active

@endsection

@section('question')

active

@endsection

@section('content')

   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>

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

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('question.index') }}">List</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

	<div class="card">

        <div class="card-header">Add Question</div>

           <div class="card-body">

           	 @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif

            <form action="{{ route('question.store')}}" method="post" enctype="multipart/form-data">

            @csrf

              <div class="row">

            <div class="col">

              <div class="form-group">

              <label for="validationCustom08">Select Courses</label>


                  <select class="form-control valid" name="boards" required="" id="boards" aria-invalid="false"  onchange="getSubject()">

                          <option selected="" disabled="">Select Courses</option>
                        @foreach($boards as $row)

                          <option value="{{$row->id}}">{{$row->board_name}}</option>

                        @endforeach

                      </select>

                </div>

              </div>

            <div class="col">

              <div class="form-group">

              <label for="validationCustom09" id="select_class">Select Subject</label>

                <select class="form-control valid" name="subject" id="subject_list" onchange="getChapter()" required="" aria-invalid="false">

                         

                  </select>

             </div>

            </div>

          </div>

          <div class="row">

            <div class="col">

              <div class="form-group">

              <label for="validationCustom08">Select Chapter</label>

                  <select class="form-control valid" name="chapter" id="chapter_list" onchange="getTopic();" required="" aria-invalid="false">

                         

                      </select>

                </div>

              </div>

            <div class="col d-none">

              <div class="form-group">

              <label for="validationCustom09" id="select_class">Select Topic</label>

              <select class="form-control valid" name="topic" required="" id="topic_list" aria-invalid="false">
                      </select>





             </div>

            </div>

          </div>

          <hr>
          <div class="row">

                <div class="col-md-6">

                    <div class="lng_fst_com">

                        <label>English Question</label>

                        <textarea id='e_question'  name='e_question' style='border: 1px solid black;'></textarea><br>

                    </div>

                </div>

                <!--/.col-->

                <div class="col-md-6">

                    <div class="lng_fst_com">

                        <label>Hindi Question</label>

                        <textarea id='h_question' name='h_question' style='border: 1px solid black;'  ></textarea><br>

                    </div>

                </div>

                <!--/.col-->

            </div>

            <div class="row">

              <div class="col"></div>

              <div class="col">

                   <div class="form-group row">

                  <label class="col-form-label">Image</label>

                  <input type="file" name="image">

                  </div>

              </div>

              <div class="col"></div>

            </div>

            <div class="row">

            <div class="col-md-3"></div>

            <div class="col-md-3"></div>

          </div>

            <div class="row">

              <div class="col-lg-12">

                 <div class="card">

                  <div class="card-body">

                  <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">

                      <li class="nav-item">

                          <a href="javascript:void();" data-target="#option1" data-toggle="pill" class="nav-link active"><i class="icon-note"></i> <span class="hidden-xs">Option 1</span></a>

                      </li>

                      <li class="nav-item">

                          <a href="javascript:void();" data-target="#option2" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">Option 2</span></a>

                      </li>

                      <li class="nav-item">

                          <a href="javascript:void();" data-target="#option3" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">Option 3</span></a>

                      </li>

                      <li class="nav-item">

                          <a href="javascript:void();" data-target="#option4" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">Option 4</span></a>

                      </li>

                      <li class="nav-item">

                          <a href="javascript:void();" data-target="#option5" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">Option 5</span></a>

                      </li>

                      <li class="nav-item">

                          <a href="javascript:void();" data-target="#option6" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">Option6</span></a>

                      </li>

                  </ul>

                  <div class="tab-content p-3">

                      <div class="tab-pane active" id="option1">

                          <h5 class="mb-3">Option 1</h5>

                          <div class="row">

                              <div class="col-md-6">

                                <div class="icheck-material-primary">

                                  <label for="user-checkbox">Correct</label>

                                  <input type="checkbox" id="user-checkbox" name="correct[0]" value="1">

                                </div>

                              </div>

                            <div class="col-md-6">

                              <div class="icheck-material-primary">

                                <label for="user-checkbox">Image</label>

                                <input type="file" name="option_image[]">

                              </div>

                            </div>

                          </div>

                          <br>

                          <div class="row">

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>English Options</label>

                                      <textarea id='e_option1'  name='e_option[]' style='border: 1px solid black;'></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>Hindi Options</label>

                                      <textarea id='h_option1' name='h_option[]' style='border: 1px solid black;'  ></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                          </div>

                          <!--/row-->

                      </div>

                      <div class="tab-pane" id="option2">

                           <h5 class="mb-3">Option 2</h5>

                           <div class="row">

                              <div class="col-md-6">

                                <div class="icheck-material-primary">

                                  <label for="user-checkbox">Correct</label>

                                  <input type="checkbox" id="user-checkbox" name="correct[1]" value="1">

                                </div>

                              </div>

                            <div class="col-md-6">

                              <div class="icheck-material-primary">

                                <label for="user-checkbox">Image</label>

                                <input type="file" name="option_image[]">

                              </div>

                            </div>

                          </div>

                          <br>

                          <div class="row">

                                <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>English Options</label>

                                      <textarea id='e_option2'  name='e_option[]' style='border: 1px solid black;'></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>Hindi Options</label>

                                      <textarea id='h_option2' name='h_option[]' style='border: 1px solid black;'  ></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                          </div>

                        

                      </div>

                      <div class="tab-pane" id="option3">

                         <h5 class="mb-3">Option 3</h5>

                          <div class="row">

                              <div class="col-md-6">

                                <div class="icheck-material-primary">

                                  <label for="user-checkbox">Correct</label>

                                  <input type="checkbox" id="user-checkbox" name="correct[2]" value="1">

                                </div>

                              </div>

                            <div class="col-md-6">

                              <div class="icheck-material-primary">

                                <label for="user-checkbox">Image</label>

                                <input type="file" name="option_image[]">

                              </div>

                            </div>

                          </div>

                          <br>

                          <div class="row">

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>English Options</label>

                                      <textarea id='e_option3'  name='e_option[]' style='border: 1px solid black;'></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>Hindi Options</label>

                                      <textarea id='h_option3' name='h_option[]' style='border: 1px solid black;'  ></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                          </div>

                      </div>

                       <div class="tab-pane" id="option4">

                         <h5 class="mb-3">Option 4</h5>

                        <div class="row">

                              <div class="col-md-6">

                                <div class="icheck-material-primary">

                                  <label for="user-checkbox">Correct</label>

                                  <input type="checkbox" id="user-checkbox" name="correct[3]" value="1">

                                </div>

                              </div>

                            <div class="col-md-6">

                              <div class="icheck-material-primary">

                                <label for="user-checkbox">Image</label>

                                <input type="file" name="option_image[]">

                              </div>

                            </div>

                          </div>

                          <br>

                          <div class="row">

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>English Options</label>

                                      <textarea id='e_option4'  name='e_option[]' style='border: 1px solid black;'></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>Hindi Options</label>

                                      <textarea id='h_option4' name='h_option[]' style='border: 1px solid black;'  ></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                          </div>

                      </div>

                       <div class="tab-pane" id="option5">

                         <h5 class="mb-3">Option 5</h5>

                        <div class="row">

                              <div class="col-md-6">

                                <div class="icheck-material-primary">

                                  <label for="user-checkbox">Correct</label>

                                  <input type="checkbox" id="user-checkbox" name="correct[4]" value="1">

                                </div>

                              </div>

                            <div class="col-md-6">

                              <div class="icheck-material-primary">

                                <label for="user-checkbox">Image</label>

                                <input type="file" name="option_image[]">

                              </div>

                            </div>

                          </div>

                          <br>

                          <div class="row">

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>English Options</label>

                                      <textarea id='e_option5'  name='e_option[]' style='border: 1px solid black;'></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>Hindi Options</label>

                                      <textarea id='h_option5' name='h_option[]' style='border: 1px solid black;'  ></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                          </div>

                      </div>

                       <div class="tab-pane" id="option6">

                         <h5 class="mb-3">Option 6</h5>

                        <div class="row">

                              <div class="col-md-6">

                                <div class="icheck-material-primary">

                                  <label for="user-checkbox">Correct</label>

                                  <input type="checkbox" id="user-checkbox" name="correct[5]" value="1">

                                </div>

                              </div>

                            <div class="col-md-6">

                              <div class="icheck-material-primary">

                                <label for="user-checkbox">Image</label>

                                <input type="file" name="option_image[]">

                              </div>

                            </div>

                          </div>

                          <br>

                          <div class="row">

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>English Options</label>

                                      <textarea id='e_option6'  name='e_option[]' style='border: 1px solid black;'></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                              <div class="col-md-6">

                                  <div class="lng_fst_com">

                                      <label>Hindi Options</label>

                                      <textarea id='h_option6' name='h_option[]' style='border: 1px solid black;'  ></textarea><br>

                                  </div>

                              </div>

                              <!--/.col-->

                          </div>

                      </div>

                  </div>

                </div>

                </div>

              </div>

              </div>

             <div class="row">

                <div class="col-md-6">

                    <div class="lng_fst_com">

                        <label>English Solutions</label>

                        <textarea id='e_solutions'  name='e_solutions' style='border: 1px solid black;'></textarea><br>

                    </div>

                </div>

                <!--/.col-->

                 <div class="col-md-6">

                    <div class="lng_fst_com">

                        <label>Hindi Solutions</label>

                        <textarea id='h_solutions'  name='h_solutions' style='border: 1px solid black;'></textarea><br>

                    </div>

                </div>

                <!--/.col-->

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

<script src="http://code.jquery.com/jquery-1.9.1.js"></script>

<script src="http://code.jquery.com/ui/1.11.0/jquery-ui.js"></script>

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

        <script src="https://cdn.jsdelivr.net/npm/sweetalert2@9"></script>

        @if ($message = Session::get('success'))

            <script>

            Swal.fire({

                icon: 'success',

                title: '{{ $message }}',

                showConfirmButton: false,

                timer: 2500

              });

            setInterval(function(){ window.location.href="{{ route('question.create')}}"}, 1500);

            </script>

          @endif



<script>

    // CKEDITOR.replace('e_question');

    // CKEDITOR.replace('h_question');

    // CKEDITOR.replace('e_option1');

    // CKEDITOR.replace('h_option1');

    // CKEDITOR.replace('e_option2');

    // CKEDITOR.replace('h_option2');

    // CKEDITOR.replace('e_option3');

    // CKEDITOR.replace('h_option3');

    // CKEDITOR.replace('e_option4');

    // CKEDITOR.replace('h_option4');

    // CKEDITOR.replace('e_option5');

    // CKEDITOR.replace('h_option5');

    // CKEDITOR.replace('e_option6');

    // CKEDITOR.replace('h_option6');

    // CKEDITOR.replace('e_solutions');

    // CKEDITOR.replace('h_solutions');

</script>

@endsection