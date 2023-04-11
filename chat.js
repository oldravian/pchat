var r_id='';
$(document).ready(function(){
            update_last_activity();
            fetchuserFirstTime();
            
            
            setInterval(function(){
                update_last_activity();
                fetchuser();
                update_chat_history();
            },4000);
            

            $(document).on('click','.contact',function(){ 
                var to_user_id = $(this).data('touserid');
                if(!to_user_id){ return; }
                r_id=to_user_id;
                $(".messages").html('');
                var to_user_name = $(this).data('tousername');
                var to_user_img = $(this).data('touserimg');
                $('.receiver_img').attr("src",to_user_img);
                $('.receiver_name').text(to_user_name);
                fetch_chat_history(to_user_id);
              });


              //Typing Status Update Logic
        $(document).on('focus','.message-box',function(){
            var is_type='yes';
                $.ajax({
                url:'server/update_is_type_status.php',
                method:'POST',
                data:{is_type:is_type},
                success:function(data,status){
                }
            });            
        });
        
        $(document).on('blur','.message-box',function(){
            var is_type='no'; 
            $.ajax({
                url:'server/update_is_type_status.php',
                method:'POST',
                data:{is_type:is_type},
                success:function(data,status){
                }
            });            
        });

        $('.submit').click(function() {
            newMessage();
          });
          
          $('.message-box').on('keydown', function(e) {
            if (e.which == 13) {
              newMessage();
              return false;
            }
          });


              function fetchuser(){
                $.ajax({
                    url:'server/fetchuser.php',
                    method:'get',
                    success:function(data,status){
                        $("#userdata").html(data);
                    }
                });
            }

            function fetchuserFirstTime(){
                $.ajax({
                    url:'server/fetchuser.php',
                    method:'get',
                    success:function(data,status){
                        $("#userdata").html(data);
                    },
                    complete:function(){
                        $('#first_contact').trigger("click");
                    }
                });
            }
            
            
            function update_last_activity(){
                $.ajax({
                    url:'server/update_last_activity.php',
                    success:function(data,status){}
                });
            }
            
// The following method will return the chat history of a particular user
function fetch_chat_history(to_user_id){
    $.ajax({
        url:'server/fetch_chat_history.php',
        method:'post',
        data:{to_user_id:to_user_id},
        success:function(data,status){
            
            //confirm chat_history is fetched for active receiver
            if(to_user_id==r_id){
            $(".messages").html(data);
            $(".messages").animate({ scrollTop: $(document).height() }, "fast"); 
            }
            
        }
    });
}


//the following function will update chat history
function update_chat_history(){
    fetch_chat_history(r_id);
}

//Message Sending Logic
function newMessage() {
    message = $(".message-input input").val();
    if($.trim(message) == '') {
        return false;
    }
    $('<li class="sent"><img src="'+$('#profile-img').attr('src')+'" /><p>' + message + '<br><label class="msg"><i class="fa fa-clock-o"></i> just now</label></p></li>').appendTo($('.messages ul'));
    $('.message-input input').val(null);
    $('.contact.active .preview').html('<span>You: </span>' + message);
    $(".messages").animate({ scrollTop: $(document).height() }, "fast");
    $.ajax({
        url:'server/send_chat.php',
        method:'post',
        data:{to_user_id:r_id,
               chat_message:message},
        success:function(data,status){
        }
    });
};
     
        });


            
            
            