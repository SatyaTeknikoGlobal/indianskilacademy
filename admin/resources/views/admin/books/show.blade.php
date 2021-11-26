@extends('admin/layout')

@section('books')
active
@endsection
@section('book')
active
@endsection
@section('content')
<div class="container-fluid">
      <!-- Breadcrumb-->
     <div class="row pt-2 pb-2">
        <div class="col-sm-9">
        <h4 class="page-title">Manage Books</h4>
        <ol class="breadcrumb">
            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>
            <li class="breadcrumb-item active"><a href="javaScript:void();">Books</a></li>
         </ol>
     </div>
     <div class="col-sm-3">
       <div class="btn-group float-sm-right">
        <a type="button" class="btn btn-primary waves-effect waves-light" href="{{  route('book.index') }}">List</a>
      </div>
     </div>
     </div>
    <!-- End Breadcrumb-->
      <div class="row">
        <div class="col-lg-4">
           <div class="card profile-card-2">
            <div class="card-img-block">
               @if(isset($data->image) && is_file(public_path('images/books/' .$data->image)))
                <img class="img-fluid" src="{{URL::asset('images/books')}}/{{$data->image}}" alt="Card image cap">
              @endif
            </div>
            <div class="card-body pt-5">
                <h5 class="card-title">{{$data->type}}
                   @if($data->type=='ebook')
                  @if(isset($data->file_name) && is_file(public_path('images/books/' .$data->file_name)))
                  <a href="{{URL::asset('images/books')}}/{{$data->file_name}}" target="_blank" class="btn btn btn-primary btn-sm">View</a>
                    @endif
                    @endif
                    <br>
                  </h5>
                <p class="card-text">{{$data->categories->category_name}}</p>
                 <span>Book Name: - <b>{{$data->book_name}}</b></span>
                 <br>
                <span>Author :- <b>{{$data->authors->name}}</b></span>
                <br>
                 <span>Publisher: - <b>{{$data->publishers->name}}</b></span>
                 <br>
                 <span>Released: - <b>{{$data->released}}</b></span>
                <br>
                <br>
            </div>
          </div>
          </div>
        <div class="col-lg-8">
           <div class="card">
            <div class="card-body">
            <ul class="nav nav-tabs nav-tabs-primary top-icon nav-justified">
                <li class="nav-item">
                    <a href="javascript:void();" data-target="#profile" data-toggle="pill" class="nav-link active"><i class="fa fa-book"></i> <span class="hidden-xs">Book Details</span></a>
                </li>
            </ul>
            <div class="tab-content p-3">
                <div class="tab-pane active" id="profile">
                    <h5 class="mb-3">About</h5>
                    <div class="row">
                        <div class="col-md-6">
                            <h6>No of Pages :- <b>
                              {{$data->pages}}
                            </b></h6>
                           
                            <h6>MRP :- <b>
                                {{$data->mrp}}
                            </b></h6>
                           
                        </div>
                        <div class="col-md-6">
                            <h6>Stock Count :- <b>
                                {{$data->stock_count}}
                            </b></h6>
                            

                             <h6>In Stock :-<b>
                                {{$data->in_stock}}
                            </b></h6>
                             
                        </div>

                         <div class="col-md-6">

                           <h6>Language :-  <b>
                                
                                {{$data->language}}
                            </b></h6>
                            


                            <h6>Description:-</h6>
                             <b>
                                
                                {!! nl2br($data->description) !!}
                            </b>
                           
                            
                        </div>
                        <div class="col-md-6">
                            

                              <h6>In Deal :-  <b>
                                
                                {{$data->in_deal}}
                            </b></h6>



                            <h6>Sale Price :- <b>
                                {{$data->sale_price}}
                            </b></h6>
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
                                 
                                    </select>
                            </div>
                        </div>
                        <div class="form-group row">
                            <label class="col-lg-3 col-form-label form-control-label">Class Name</label>
                            <div class="col-lg-9">
                                  <select class="form-control" id="class" name="class_id" onchange="getSubject()">
                                    <option readonly>Select Classes</option>
                                 
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
@endsection