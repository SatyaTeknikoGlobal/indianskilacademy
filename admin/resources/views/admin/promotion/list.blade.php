@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('promotionalvideo')

active

@endsection

@section('content')

<?php
$categories = config('custom.video_categories');
?>
    <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

		    <h4 class="page-title">Manage Promotional Video</h4>

		    <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">Promotional Video</a></li>

         </ol>

	   </div>

	   <div class="col-sm-3">

       <div class="btn-group float-sm-right">

        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{ route('promotionalvideo.create')}}">Add</a>

      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

      <div class="row">

        <div class="col-lg-12">

          <div class="card">

            <div class="card-header"><i class="fa fa-book"></i> Promotional Video List </div>

            <div class="card-body">

              <div class="table-responsive">

              <table id="default-datatable" class="table table-bordered">

                <thead>

                    <tr>

                        <th>#</th>

                        <th>Name</th>
                        <th>Category Name</th>

                        <th>Video</th>


                        <th>Status</th>

                        <th>Action</th>

                    </tr>

                </thead>

                <tbody>

                    @php

                    $i=1

                    @endphp

                    @foreach($data as $row)

                    <tr>
                        <td>{{$i++}}</td>

                       
                        <td>
                            
                       {{$row->name}}
                        </td>


                        <td>
                            <?php if(!empty($categories)){
                                foreach($categories as $key=>$value){
                                    if($key == $row->cat_id){
                                        echo $value;
                                    }
                                }
                            }
                                ?>
                      
                        </td>


                        <td>
                           <iframe id="ytplayer" type="text/html" width="200" height="100"
                         src="https://www.youtube.com/embed/{{$row->video_id}}"
                         frameborder="0"></iframe>
                        </td>
                         <td>
                           @if($row->status!='0')

                            <span class="badge badge-success">Active</span>

                            @else

                            <span class="badge badge-danger">Deactive</span>

                         
                            @endif
                        </td>


                        <td>

                          <!-- Button trigger modal -->

                            <!-- <button type="button" class="btn btn-info btn-sm" data-toggle="modal" data-target="#notesModel{{$row->id}}">

                               Add Notes

                            </button>

                            <br> -->


                            <!--Notes   Modal -->

                           <!--  <div class="modal fade" id="notesModel{{$row->id}}" tabindex="-1" role="dialog" aria-labelledby="exampleModalLabel" aria-hidden="true">

                                <div class="modal-dialog" role="document">

                                    <div class="modal-content">

                                        <div class="modal-header">

                                            <h5 class="modal-title" id="exampleModalLabel">Add Notes</h5>

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

                                                    <form action="{{ route ('notes.store') }}" method="post" enctype="multipart/form-data">

                                                        @csrf

                                                        <input type="hidden" name="topic_id" value="{{$row->id}}">

                                                        <div class="form-group row">

                                                            <label for="input-26" class="col-sm-2 col-form-label">Notes</label>

                                                            <div class="col-sm-10">

                                                                <input type="file" class="form-control-file" id="input-26" name="notes">

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

                            </div> -->
                            <a href="{{ route('promotionalvideo.edit', $row->id) }}" class="btn btn-primary btn-sm flex"><i class="fa fa-edit" aria-hidden="true"></i> Edit</a>

                            <form action="{{ route('promotionalvideo.destroy',$row->id) }}" method="POST" id="delete_record">

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