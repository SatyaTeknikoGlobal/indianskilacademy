@extends('admin/layout')

@section('manage_content')

active

@endsection

@section('faq')

active

@endsection

@section('content')
<style type="text/css">
.container{max-width:1170px; margin:auto;}
img{ max-width:100%;}
.inbox_people {
  background: #f8f8f8 none repeat scroll 0 0;
  float: left;
  overflow: hidden;
  width: 40%; border-right:1px solid #c4c4c4;
}
.inbox_msg {
  border: 1px solid #c4c4c4;
  clear: both;
  overflow: hidden;
}
.top_spac{ margin: 20px 0 0;}


.recent_heading {float: left; width:40%;}
.srch_bar {
  display: inline-block;
  text-align: right;
  width: 60%;
}
.headind_srch{ padding:10px 29px 10px 20px; overflow:hidden; border-bottom:1px solid #c4c4c4;}

.recent_heading h4 {
  color: #05728f;
  font-size: 21px;
  margin: auto;
}
.srch_bar input{ border:1px solid #cdcdcd; border-width:0 0 1px 0; width:80%; padding:2px 0 4px 6px; background:none;}
.srch_bar .input-group-addon button {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  padding: 0;
  color: #707070;
  font-size: 18px;
}
.srch_bar .input-group-addon { margin: 0 0 0 -27px;}

.chat_ib h5{ font-size:15px; color:#464646; margin:0 0 8px 0;}
.chat_ib h5 span{ font-size:13px; float:right;}
.chat_ib p{ font-size:14px; color:#989898; margin:auto}
.chat_img {
  float: left;
  width: 11%;
}
.chat_ib {
  float: left;
  padding: 0 0 0 15px;
  width: 88%;
}

.chat_people{ overflow:hidden; clear:both;}
.chat_list {
  border-bottom: 1px solid #c4c4c4;
  margin: 0;
  padding: 18px 16px 10px;
}
.inbox_chat { height: 550px; overflow-y: scroll;}

.active_chat{ background:#ebebeb;}

.incoming_msg_img {
  display: inline-block;
  width: 6%;
}
.received_msg {
  display: inline-block;
  padding: 0 0 0 10px;
  vertical-align: top;
  width: 92%;
}
.received_withd_msg p {
  background: #ebebeb none repeat scroll 0 0;
  border-radius: 3px;
  color: #646464;
  font-size: 14px;
  margin: 0;
  padding: 5px 10px 5px 12px;
  width: 100%;
}
.time_date {
  color: #747474;
  display: block;
  font-size: 12px;
  margin: 8px 0 0;
}
.received_withd_msg { width: 57%;}
.mesgs {
  float: left;
  padding: 30px 15px 0 25px;
  width: 60%;
}

.sent_msg p {
  background: #05728f none repeat scroll 0 0;
  border-radius: 3px;
  font-size: 14px;
  margin: 0; color:#fff;
  padding: 5px 10px 5px 12px;
  width:100%;
}
.outgoing_msg{ overflow:hidden; margin:26px 0 26px;}
.sent_msg {
  float: right;
  width: 46%;
}
.input_msg_write input {
  background: rgba(0, 0, 0, 0) none repeat scroll 0 0;
  border: medium none;
  color: #4c4c4c;
  font-size: 15px;
  min-height: 48px;
  width: 100%;
}

.type_msg {border-top: 1px solid #c4c4c4;position: relative;}
.msg_send_btn {
  background: #05728f none repeat scroll 0 0;
  border: medium none;
  border-radius: 50%;
  color: #fff;
  cursor: pointer;
  font-size: 17px;
  height: 33px;
  position: absolute;
  right: 0;
  top: 11px;
  width: 33px;
}
.messaging { padding: 0 0 50px 0;}
.msg_history {
  height: 516px;
  overflow-y: auto;
}
</style>

<div class="container-fluid">

  <!-- Breadcrumb-->

  <div class="row pt-2 pb-2">

    <div class="col-sm-9">

      <h4 class="page-title">Manage Doubts</h4>

      <ol class="breadcrumb">

        <li class="breadcrumb-item"><a href="{{ url('dashboard') }}">Home</a></li>

        <li class="breadcrumb-item active"><a href="javaScript:void();">Doubts</a></li>

    </ol>

</div>


</div>

</div>

<!-- End Breadcrumb-->


<div class="row">
    <div class="container">
        <h3 class=" text-center">Doubts</h3>
        <div class="messaging">
          <div class="inbox_msg">
            <div class="inbox_people">
              <div class="headind_srch">
                <div class="recent_heading">
                  <h4>Recent</h4>
              </div>

          </div>
          <div class="inbox_chat">


          </div>
      </div>
      <div class="mesgs">

        <h4 id="user_name"></h4>

        <div class="msg_history">


          <!--   <div class="incoming_msg">
              <div class="incoming_msg_img"> <img src="https://ptetutorials.com/images/user-profile.png" alt="User"> </div>
              <div class="received_msg">
                <div class="received_withd_msg">
                  <p>Test which is a new approach to have all
                    solutions</p>
                  <span class="time_date"> 11:01 AM    |    June 9</span></div>
              </div>
            </div>


            <div class="outgoing_msg">
              <div class="sent_msg">
                <p>Test which is a new approach to have all
                  solutions</p>
                <span class="time_date"> 11:01 AM    |    June 9</span> </div>
            </div>
        -->

    </div>
    <div class="type_msg">
        <form id="send_message">
            <input type="hidden" name="sender_id" id="sender_id" value="0">
            <input type="hidden" name="receiver_id" id="receiver_id" value="">
            <input type="hidden" name="sender_type" id="sender_type" value="admin">
            <input type="hidden" name="receiver_type" id="receiver_type" value="user">
            <input type="hidden" name="status" id="status" value="1">

            <div class="input_msg_write">
              <input type="text" class="write_msg" name="message" placeholder="Type a message" />
              <button class="msg_send_btn" type="submit"><i class="fa fa-paper-plane-o" aria-hidden="true"></i></button>
          </div>
      </form>
  </div>
</div>
</div>


</div>



<!--start overlay-->

<div class="overlay toggle-menu"></div>

<!--end overlay-->

</div>

<!-- End container-fluid-->
<script type="text/javascript">
    $( document ).ready(function() {
        get_users();


       setInterval(get_new_chat, 3000);

    });





    function get_users(user_id=0){
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ url('get_chats') }}",
            type: "POST",
            data: {user_id:user_id},
            dataType:"HTML",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){
                $('.inbox_chat').html(resp);
                var user_id = $("#get_chat").val();
                get_chat_by_user(user_id);
            }
        });
    }


    function get_chat_by_user(user_id){
        var _token = '{{ csrf_token() }}';
        $.ajax({
            url: "{{ url('get_chat_by_user') }}",
            type: "POST",
            data: {user_id:user_id},
            dataType:"HTML",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){
                get_user_name(user_id);

                $('#receiver_id').val(user_id);
               // $('.write_msg').val('');
               $('.msg_history').html(resp);

           }
       });   





    }


    function get_new_chat(){
        var user_id_new = $("#new_user_id").val();
        if(user_id_new != undefined || user_id_new !=0 || user_id_new !=''){
            //alert(user_id_new);
            get_chat_by_user(user_id_new);
        }
    }


    function get_user_name(user_id){
        var _token = '{{ csrf_token() }}';

        $.ajax({
            url: "{{ url('get_user_name') }}",
            type: "POST",
            data: {user_id:user_id},
            dataType:"JSON",
            headers:{'X-CSRF-TOKEN': _token},
            cache: false,
            success: function(resp){
                //$('.write_msg').val('');
                $('#user_name').html(resp.name);

            }
        });  
    }


    $(document).ready(function(){

        $("form#send_message").submit(function() {
            event.preventDefault();
            var data = $('#send_message').serialize();
            var _token = '{{ csrf_token() }}';
            $.ajax({
                url: "{{ url('send_message') }}",
                type: "POST",
                data: $('#send_message').serialize(),
                dataType:"JSON",
                headers:{'X-CSRF-TOKEN': _token},
                cache: false,
                success: function(resp){
                    if(resp.status){
                        $('.write_msg').val('');

                        get_chat_by_user(resp.user_id);
                    }
                }
            }); 
        });
        return false;
    });

</script>

@endsection