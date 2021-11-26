@extends('admin/layout')

@section('dashboard')
active
@endsection
@section('content')
   <script src="https://cdn.ckeditor.com/4.15.0/standard/ckeditor.js"></script>
   <div class="container-fluid">

      <!-- Breadcrumb-->

     <div class="row pt-2 pb-2">

        <div class="col-sm-9">

        <h4 class="page-title">Manage Setting</h4>

        <ol class="breadcrumb">

            <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

            <li class="breadcrumb-item active"><a href="javaScript:void();">My Setting</a></li>

         </ol>

     </div>

     <div class="col-sm-3">

       <div class="btn-group float-sm-right">



      </div>

     </div>

     </div>

    <!-- End Breadcrumb-->

  <div class="card">

        <div class="card-header">Add/Edit Setting</div>

           <div class="card-body">

             @if ($errors->any())

              @foreach ($errors->all() as $error)

              <div id="fadeout-msg" class="alert alert-danger">

                  {{ $error }}

              </div>

              @endforeach

          @endif
            <form action="{{ route('setting.store') }}" method="post" enctype="multipart/form-data">
            @csrf
            <div class="form-group row">
               <div class="col-md-12 d-none">
                    <div class="lng_fst_com">
                        <label>Referral Amount</label>
                       <input type="number" name="referral_amount" class="form-control" value="{{$data->referral_amount}}" step="0.01"> <br>
                    </div>
               </div>
                <div class="col-md-12">
                    <div class="lng_fst_com">
                        <label>Privacy Policy</label>
                        <textarea id='privacy'  name='privacy' style='border: 1px solid black;'>{{$data->privacy}}</textarea><br>
                    </div>
                </div>
                <!--/.col-->
                <div class="col-md-12">
                    <div class="lng_fst_com">
                        <label>Term & Conditions</label>
                        <textarea id='terms' name='terms' style='border: 1px solid black;'  >{{$data->terms}}</textarea><br>
                    </div>
                </div>
                <!--/.col-->
                  <!--/.col-->
                <div class="col-md-12">
                    <div class="lng_fst_com">
                        <label>About US</label>
                        <textarea id='about_us' name='about_us' style='border: 1px solid black;'  >{{$data->about_us}}</textarea><br>
                    </div>
                </div>
                <!--/.col-->

             
   <!--/.col-->
                <div class="col-md-12">
                    <div class="lng_fst_com">
                        <label>Follow US</label>
                        <textarea id='follow_us' name='follow_us' style='border: 1px solid black;'  >{{$data->follow_us}}</textarea><br>
                    </div>
                </div>
                <!--/.col-->

                <div class="col-md-12 d-none">
                    <div class="lng_fst_com">
                        <label>Help & Feed</label>
                        <textarea id='terms' name='help_feed' style='border: 1px solid black;'  >{{$data->help_feed}}</textarea><br>
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

         </div>
          <script>
               CKEDITOR.replace( 'terms' );
              CKEDITOR.replace( 'privacy' );
              CKEDITOR.replace( 'about_us' );
              CKEDITOR.replace( 'follow_us' );
              CKEDITOR.replace( 'privacy' );
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