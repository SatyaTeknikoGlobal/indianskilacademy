@extends('admin/layout')

@section('users')
active
@endsection
@section('faculties')
active
@endsection
@section('content')
<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Manage Faculties</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Faculties</a></li>
         </ol>
     </div>
     <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('faculties.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
    @foreach($data as $row)
      <div class="row">
        <div class="col-lg-4">
           <div class="card profile-card-2">
            <div class="card-img-block">
                <img class="img-fluid" src="{{URL::asset('assets/images/gallery/31.jpg')}}" alt="Card image cap">
            </div>
            <div class="card-body pt-5">
               @if(isset($row->image) && is_file(public_path('images/faculties/' .$row->image)))
                <img src="{{URL::asset('images/faculties')}}/{{$row->image}}" alt="profile-image" class="profile" height="80px" width="80px">
                 @endif
                <h5 class="card-title">{{$row->name}}</h5>
                <p class="card-text">{{$row->speciality}}</p>
                 <span>Mobile - <b>{{$row->phone}}</b></span>
                 <br>

                <span>Email - <b>{{$row->email}}</b></span>
                <br>
                 <span>DOB - <b>{{$row->dob}}</b></span>
                 <br>

                <br>
                <br>
                <div class="icon-block">
                  <a href="javascript:void();"><i class="fa fa-facebook bg-facebook text-white"></i></a>
        				  <a href="javascript:void();"> <i class="fa fa-twitter bg-twitter text-white"></i></a>
        				  <a href="javascript:void();"> <i class="fa fa-google-plus bg-google-plus text-white"></i></a>
                </div>
            </div>
          </div>

          </div>

        <div class="col-lg-8">
           <div class="card">
            <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">
                <li class="nav-item">
                    <a href="javascript:void();" data-target="#profile" data-toggle="pill" class="nav-link active"><i class="icon-user"></i> <span class="hidden-xs">Profile</span></a>
                </li>
               <!--  <li class="nav-item">
                    <a href="javascript:void();" data-target="#messages" data-toggle="pill" class="nav-link"><i class="icon-envelope-open"></i> <span class="hidden-xs">Messages</span></a>
                </li> -->
               <!--  <li class="nav-item">
                    <a href="javascript:void();" data-target="#edit" data-toggle="pill" class="nav-link"><i class="icon-note"></i> <span class="hidden-xs">Subject</span></a>
                </li> -->
            </ul>
            <div class="tab-content p-3">
                <div class="tab-pane active" id="profile">
                    <h5 class="mb-3">About</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>Speciality</h6>
                            <p>
                              {{$row->speciality}}
                            </p>
                            <h6>Education</h6>
                            <p>
                                {{$row->education}}
                            </p>
                        </div>
                        <div class="col-md-6">
                            <h6>College Name</h6>
                             <p>
                                {{$row->college_name}}
                            </p>

                             <h6>Total Experience</h6>
                             <p>
                                {{$row->total_exp}} Year's
                            </p>
                        </div>

                         <div class="col-md-6">
                            <h6>College Location</h6>
                             <p>
                                {{$row->college_location}}
                            </p>
                        </div>

                        
                       
                    </div>
                    <!--/row-->
                </div>
                <div class="tab-pane" id="messages">
                    <div class="alert alert-info alert-dismissible" role="alert">
				   <button type="button" class="close" data-dismiss="alert">×</button>
				    <div class="alert-icon">
					 <i class="icon-info"></i>
				    </div>
				    <div class="alert-message">
				      <span><strong>Info!</strong> Lorem Ipsum is simply dummy text.</span>
				    </div>
                  </div>
                  <div class="table-responsive">
                    <table class="table table-hover table-striped">
                        <tbody>                                    
                            <tr>
                                <td>
                                   <span class="float-right font-weight-bold">3 hrs ago</span> Here is your a link to the latest summary report from the..
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   <span class="float-right font-weight-bold">Yesterday</span> There has been a request on your account since that was..
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   <span class="float-right font-weight-bold">9/10</span> Porttitor vitae ultrices quis, dapibus id dolor. Morbi venenatis lacinia rhoncus. 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   <span class="float-right font-weight-bold">9/4</span> Vestibulum tincidunt ullamcorper eros eget luctus. 
                                </td>
                            </tr>
                            <tr>
                                <td>
                                   <span class="float-right font-weight-bold">9/4</span> Maxamillion ais the fix for tibulum tincidunt ullamcorper eros. 
                                </td>
                            </tr>
                        </tbody> 
                    </table>
                  </div>
                </div>
                <div class="tab-pane" id="edit">
                    <form>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Boards Name</label>
                            <div class="col-lg-9">
                                  <select class="form-control" id="boards" name="boards">
                                    <option readonly>Select Boards</option>
                                    @foreach($boards as $row)
                                      <option value="{{$row->id}}">{{$row->board_name}}</option>
                                      @endforeach
                                    </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Class Name</label>
                            <div class="col-lg-9">
                                  <select class="form-control" id="class" name="class_id" onchange="getSubject()">
                                    <option readonly>Select Classes</option>
                                    @foreach($class as $row)
                                      <option value="{{$row->id}}">{{$row->class_name}}</option>
                                      @endforeach
                                    </select>
                            </div>
                        </div>
                       <div class="form-group row">
                         <label class="col-lg-3 col-form-label form-control-label">Subject</label>
                          <div class="col-lg-9">
                          <select class="form-control" id="subject_list" name="subject_id">
                            <option readonly>Select Subject</option>
                          
                            </select>
                          </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label"></label>
                            <div class="col-lg-9">
                                <input type="reset" class="btn btn-secondary" value="Cancel">
                                <input type="button" class="btn btn-primary" value="Add">
                            </div>
                        </div>
                    </form>
                </div>
            </div>
        </div>
      </div>
      </div>
        
    </div>
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
    </script>
    @endforeach

@endsection