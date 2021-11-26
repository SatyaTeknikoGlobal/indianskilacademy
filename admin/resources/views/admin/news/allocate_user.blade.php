@extends('admin/layout')

@section('app_setting')

active

@endsection

@section('board')

active

@endsection

@section('content')

<div class="container-fluid">

  <!-- Breadcrumb-->

  <div class="row pt-2 pb-2">

    <div class="col-sm-9">

      <h4 class="page-title">Manage Courses Allocation</h4>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

        <li class="breadcrumb-item active"><a href="javaScript:void();">Courses Allocation</a></li>

      </ol>

    </div>

</div>

<link rel="stylesheet" href="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/css/bootstrap.min.css">
<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/css/bootstrap-select.css" />
<script src="https://ajax.googleapis.com/ajax/libs/jquery/2.1.1/jquery.min.js"></script>
<script src="https://stackpath.bootstrapcdn.com/bootstrap/4.1.1/js/bootstrap.bundle.min.js"></script>
<script src="https://cdnjs.cloudflare.com/ajax/libs/bootstrap-select/1.13.1/js/bootstrap-select.min.js"></script>

<!-- End Breadcrumb-->
<div class="row">
  <div class="col-lg-12">
    <div class="card">
      <div class="card-body">
        <form action="" method="post">
           {{ csrf_field() }}
          <label><strong>Course Name :</strong> {{$boards->board_name}}</label>
          <input type="hidden" name="board_id" class="form-control" value="{{$boards->id}}"><br>
          <label><strong>Name</strong></label>
           <div class="col-lg-12">
          <div class="demo">
          <select name="user_id[]" id="multiple" class="selectpicker" multiple data-live-search="true" >
           <!--  <option value="" selected disabled>Select User</option> -->
            <?php
           
             if(!empty($users) && count($users)>0){
              foreach($users as $user){
                 $selected = '';
                foreach($allocates as $allo){
                  if($user->id == $allo->user_id){
                    $selected = 'selected';
                  }
                }
              ?>
              <option value="{{$user->id}}" <?php echo $selected;?>>{{$user->name}} ({{$user->phone}})</option>
            <?php }}?>
          </select>
        </div>
        </div><br>
          <label>&nbsp;</label>
          <button type="submit" class="btn btn-primary">Allocate</button>
        </form>
      </div>
    </div>
  </div>
</div>






<div class="row">

  <div class="col-lg-12">

    <div class="card">

      <div class="card-header"><i class="fa fa-clipboard"></i>Users List</div>

      <div class="card-body">

        <div class="table-responsive">

          <table id="default-datatable" class="table table-bordered">

            <thead>

              <tr>

                <th>#</th>

                <th>Courses Name</th>
                <th>Name</th>
                <th>Email</th>
                <th>Phone</th>
                <th>Action</th>

              </tr>

            </thead>

            <tbody>

              <?php if(!empty($allocates) && count($allocates) >0){
                $i=1;
                foreach($allocates as $allocat){
                  $user_id = isset($allocat->user_id) ? $allocat->user_id : 0;
                  $name = '';
                  $email = '';
                  $phone = '';
                  if(!empty($users) && count($users)>0){
                    foreach($users as $user){
                      if($user->id == $user_id){
                        $name = $user->name;
                        $email = $user->email;
                        $phone = $user->phone;
                      }
                    }
                  }


                  ?>

              <tr>
                <td>{{$i++}}</td>
                <td>{{$boards->board_name}}</td>
                <td>{{$name}}</td>
                <td>{{$email}}</td>
                <td>{{$phone}}</td>
                <td><a class="btn btn-danger" href="{{route('allocate.delete',[$allocat->id,$boards->id])}}" onclick="return confirm('Do You Want To Remove this Subscription?')">Remove</a></td>

              </tr>

            <?php }}?>

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
<script type="text/javascript">
     $('select').selectpicker();


</script>
@endif

@endsection