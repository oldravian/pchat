<?php
include('dbc.php');
session_start();
date_default_timezone_set('UTC');
 if(isset($_SESSION['uid'])){
     $id=$_SESSION['uid'];
     $query="SELECT * FROM login WHERE id!= '$id' ";
     $query=$dbc->query($query);
     if($query->rowCount()>0){
     $result=$query->fetchAll();

     
     $output='';

         foreach($result as $i=>$row){

             //get the last message
             $recent_msg='';
     $query="SELECT from_user_id, chat_message FROM chat_message WHERE (to_user_id=:to_user_id AND from_user_id=:from_user_id) OR (to_user_id=:from_user_id AND from_user_id=:to_user_id) ORDER BY timestamp desc limit 1";
     $statement=$dbc->prepare($query);
     $statement->execute([
         ':to_user_id'=>$row['id'],
         ':from_user_id'=>$id //logged-in user id
     ]);
     $result=$statement->fetch();
     if($result['from_user_id']==$id){
        //logged_in user is sender
        $recent_msg.='<span>You: </span>'.$result['chat_message'];
    }
    else{
        //logged_in user is receiver
        $recent_msg.=$result['chat_message'];
    }


     




             $status='';
             $current_timestamp=strtotime(date('Y-m-d H:i:s').'-10second');
             $current_timestamp= date('Y-m-d H:i:s',$current_timestamp);
             $last_activity=get_last_activity($row['id'],$dbc);
             if($last_activity>$current_timestamp){
                 //user is online
                 $status='online';
             }
             $c_id=($i==0? 'first_contact': '');
             $output.='<li class="contact" id="'.$c_id.'" data-touserid="'.$row['id'].'" data-tousername="'.$row['username'].'" data-touserimg="'.$row['img'].'"><div class="wrap"><span class="contact-status '.$status.'"></span><img src="'.$row['img'].'" /><div class="meta"><p class="name">'.$row['username'].' '.load_unseen_notifications($row['id'],$id,$dbc).' '.fetch_is_type_status($row['id'],$dbc).'</p><p class="preview">'.$recent_msg.'</p></div></div></li>';
         }
     
     
     
     echo $output;
     }
 }



function get_last_activity($id,$dbc){
  $query="SELECT last_activity FROM login_users WHERE user_id= '$id' ORDER BY id desc LIMIT 1";
     $query=$dbc->query($query); 
    $row=$query->fetch();
    return $row['last_activity'];
}

function load_unseen_notifications($from_user_id,$to_user_id,$dbc){
    
    
    $query="SELECT * FROM chat_message WHERE from_user_id='$from_user_id' AND to_user_id='$to_user_id' AND  status=1";
    $statement=$dbc->prepare($query);
    $status=$statement->execute([
    ':to_user_id'=>$to_user_id,
    ':from_user_id'=>$from_user_id
]);

if(!$status){
    $errors=$statement->errorInfo();
    exit($errors[2]);
}
    $output='';
    if($statement->rowCount()>0){
        $counter=$statement->rowCount();
        $output='<label class="badge badge-success">'.$counter.'</label>';
    }
    return $output;
    
}

function fetch_is_type_status($id,$dbc){
    $query="SELECT is_type FROM login_users WHERE user_id='$id' ORDER BY last_activity desc LIMIT 1";
    $statement=$dbc->prepare($query);
    $status=$statement->execute();
    $output='';
    $row=$statement->fetch();
    if($row['is_type']=='yes'){
    $output='<label style="color:#28a745;">Typing...</label>';
    }
    return $output;
}

?>