@extends('admin/layout')

@section('examination')

active

@endsection

@section('question')

active

@endsection

@section('content')

   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>

   <script src="{{URL::asset('font/plugin.js')}}"></script>

<div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

        <h4 class="page-title">View/Edit Question</h4>

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

      <div class="card-body">



             @if ($errors->any())



              @foreach ($errors->all() as $error)



              <div id="fadeout-msg" class="alert alert-danger">



                  {{ $error }}



              </div>



              @endforeach



          @endif



            <form action="{{ route('question.update')}}" method="post" enctype="multipart/form-data">



            @csrf

            <input type="hidden" name="id" value="{{$question->id}}">

          <div class="row">

                <div class="col-md-6">

                    <div class="lng_fst_com">

                        <label>English Question</label>

                        <textarea id='e_question'  name='e_question'  style='border: 1px solid black;' >{{$question->e_question}}</textarea> <br>

                    </div>

                </div>

                <!--/.col-->

                <div class="col-md-6">

                    <div class="lng_fst_com">

                        <label>Hindi Question</label>

                        <textarea id='h_question' name='h_question'  style='border: 1px solid black;'  >{{$question->h_question}}</textarea><br>

                    </div>

                </div>

                <!--/.col-->

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



                                  <input type="checkbox" id="user-checkbox" name="correct[0]" <?php if($options[0]['correct']==1){ echo "checked"; } ?> value="1">



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


                              @foreach($options as $key => $op)

                                <input type="hidden" name="op_id[]" value="{{$op[$key]['id']}} ">

                                @endforeach
                                  <div class="lng_fst_com">



                                      <label>English Options</label>



                                      <textarea id='e_option1'   name='e_option[]' style='border: 1px solid black;'>{{$options[0]['option_e']}}</textarea><br>



                                  </div>



                              </div>



                              <!--/.col-->



                              <div class="col-md-6">



                                  <div class="lng_fst_com">



                                      <label>Hindi Options</label>



                                      <textarea id='h_option1' name='h_option[]' style='border: 1px solid black;'  >{{$options[0]['option_h']}}</textarea><br>



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



                                  <input type="checkbox" id="user-checkbox" <?php if($options[1]['correct']==1){ echo "checked"; } ?> name="correct[1]" value="1">



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



                                      <textarea id='e_option2'  name='e_option[]' style='border: 1px solid black;'>{{$options[1]['option_e']}}</textarea><br>



                                  </div>



                              </div>



                              <!--/.col-->



                              <div class="col-md-6">



                                  <div class="lng_fst_com">



                                      <label>Hindi Options</label>



                                      <textarea id='h_option2' name='h_option[]'  style='border: 1px solid black;'  >{{$options[1]['option_h']}}</textarea><br>



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



                                  <input type="checkbox" id="user-checkbox" <?php if($options[2]['correct']==1){ echo "checked"; } ?> name="correct[2]" value="1">



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



                                      <textarea id='e_option3'  name='e_option[]' style='border: 1px solid black;'>{{$options[2]['option_e']}}</textarea><br>



                                  </div>



                              </div>



                              <!--/.col-->



                              <div class="col-md-6">



                                  <div class="lng_fst_com">



                                      <label>Hindi Options</label>



                                      <textarea id='h_option3' name='h_option[]'  style='border: 1px solid black;'  >{{$options[2]['option_h']}}</textarea><br>



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



                                  <input type="checkbox" id="user-checkbox" name="correct[3]" <?php if($options[3]['correct']==1){ echo "checked"; } ?> value="1">



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



                                      <textarea id='e_option4' name='e_option[]' style='border: 1px solid black;'>{{$options[3]['option_e']}}</textarea><br>



                                  </div>



                              </div>



                              <!--/.col-->



                              <div class="col-md-6">



                                  <div class="lng_fst_com">



                                      <label>Hindi Options</label>



                                      <textarea id='h_option4' name='h_option[]' style='border: 1px solid black;'  >{{$options[3]['option_h']}}</textarea><br>



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



                                  <input type="checkbox" id="user-checkbox" name="correct[4]" <?php 

                                  if(isset($options[4]['correct'])){
                                   if($options[4]['correct']==1){ echo "checked";



                                    } }?> value="1">


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


                                      <textarea id='e_option5' name='e_option[]' style='border: 1px solid black;'  >{{$options[4]['option_h'] ??''}}</textarea><br>

                                    

                                  </div>



                              </div>



                              <!--/.col-->



                              <div class="col-md-6">



                                  <div class="lng_fst_com">



                                      <label>Hindi Options</label>

                                       <textarea id='h_option5' name='h_option[]' style='border: 1px solid black;'  >{{$options[4]['option_h'] ?? ''}}</textarea><br>

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

                        <textarea id='e_solutions'  name='e_solutions'  style='border: 1px solid black;' >{{isset($solution->e_solutions)?$solution->e_solutions:''}}</textarea><br>

                    </div>

                </div>

                <!--/.col-->

                 <div class="col-md-6">

                    <div class="lng_fst_com">

                        <label>Hindi Solutions</label>

                        <textarea id='h_solutions'  name='h_solutions'  style='border: 1px solid black;' >{{isset($solution->h_solutions)?$solution->h_solutions:''}}</textarea><br>

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

        <script src="https://cdn.ckeditor.com/4.13.1/basic/ckeditor.js"></script>

         <script>



           function getSubject() {

             boards = $("#boards").val();

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

 

</script>

@endsection