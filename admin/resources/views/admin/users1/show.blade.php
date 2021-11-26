@extends('admin/layout')
@section('users')
    active
@endsection
@section('app_users')
    active
@endsection
@section('content')
    <div class="container-fluid">
        <!-- Breadcrumb-->
        <div class="row pt-2 pb-2">
            <div class="col-sm-9">
                <h4 class="page-title">Manage Users</h4>
                <ol class="breadcrumb">
                    <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
                    <li class="breadcrumb-item active"><a href="javaScript:void();">Subcription</a></li>
                </ol>
            </div>

               <div class="col-sm-3">

                <div class="btn-group float-sm-right">

                    <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('app_users.index') }}">Back</a>

                </div>

            </div>
        </div>
        <!-- End Breadcrumb-->
        <div class="row">
            <div class="col-lg-12">

            <div class="card">

                <div class="card-header">Add Subcription</div>

                   <div class="card-body">

                     @if ($errors->any())

                      @foreach ($errors->all() as $error)

                      <div id="fadeout-msg" class="alert alert-danger">

                          {{ $error }}

                      </div>

                      @endforeach

                  @endif

                    <form action="{{ route ('app_users.store')}}" method="post">

                    @csrf

                    <input type="hidden" name="user_id" value="{{$id}}">
                   <div class="form-group row">

                    <label for="input-26" class="col-sm-2 col-form-label">Select Programs*</label>

                    <div class="col-sm-10">
                        <!-- name="type" -->
                    <select class="form-control form-control-rounded" name="topic_id">
                       <?php if(!empty($topics)){
                        foreach($topics as $topic){
                            $subject = \App\Subject::where('id',$topic->subject_id)->first();

                        ?>
                        <option  value="{{$topic->id}}">{{$subject->title}} -----> {{$topic->name}}</option>
                        <?php }}?>
                    </select>

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

                 </div>
                <div class="card">



                    <div class="card-header"><i class="fa fa-users"></i> Subcription Histroy </div>
                    <div class="card-body">
                        <div class="table-responsive">
                            <table id="default-datatable" class="table table-bordered">
                                <thead>
                                <tr>
                                    <th>#</th>
                                    <th>User</th>
                                    <th>Subject Name</th>
                                   
                                    <th>Amount</th>
                                    <th>Paid Amount</th>
                                    <th>End Date</th>
                                    <th>Action</th>
                                </tr>
                                </thead>
                                <tbody>
                                @php
                                    $i=1
                                @endphp
                                @foreach($data as $row)

                                <?php 
                                $topic_id  = $row->topic_id;
                                $topic = \App\Topic::where('id',$topic_id)->first();
                                $subject = \App\Subject::where('id',$topic->subject_id)->first();
                                ?>


                                    <tr>
                                        <td>{{$i++}}</td>
                                        <td>
                                          {{$row->name}}
                            
                                        </td>
                                        <td>
                                        {{$subject->title ?? ''}}
                                        </td>
                                       
                                        <td>{{$row->amount}}</td>
                                        <td>{{$row->paid_amount}}</td>
                                        <td>{{$row->end_date}}</td>

                                        <td>
                                            <!-- Update Subription -->
                                            <div class="modal fade" id="exampleModal{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">
                                                  <div class="modal-dialog" role="document">
                                                    <div class="modal-content">
                                                      <div class="modal-header">
                                                        <h5 class="modal-title" id="exampleModalLabel">Update Subcription</h5>
                                                        <button type="button" class="close" data-dismiss="modal" aria-label="Close">
                                                          <span aria-hidden="true">&times;</span>
                                                        </button>
                                                      </div>
                                                       <form action="{{ url('user_subcription')}}" method="POST">
                                                                @csrf
                                                                <input type="hidden" name="id" value="{{$row->id}}">
                                                                <input type="hidden" name="user_id" value="{{$id}}">

                                                      <div class="modal-body">
                                                           
                                                                 <div class="form-group row">
                                                                    <label for="input-26" class="col-sm-2 col-form-label">End date</label>
                                                                    <div class="col-sm-10">
                                                                    <input type="date" class="form-control" id="input-26" name="end_date" value="{{$row->end_date}}">
                                                                    </div>
                                                                  </div>
                                                      </div>
                                                      <div class="modal-footer">
                                                        <button type="button" class="btn btn-secondary" data-dismiss="modal">Close</button>
                                                        <button type="submit" class="btn btn-primary">Update</button>
                                                      </div>
                                                       </form>
                                                    </div>
                                                  </div>
                                                </div>
                                         <button href="javascript:void(0);" type="submit" class="btn btn-primary btn-sm" data-toggle="modal" data-target="#exampleModal{{$row->id}}"><i class="fa fa-lock" aria-hidden="true"></i> Update</button>
                                            <a href="{{ url('deleteSubcription') }}/{{$row->id}}" class="btn btn-danger btn-sm"><i class="fa fa-trash" aria-hidden="true"></i> Delete</a>
                                        </td>
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