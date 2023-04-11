<?php
include('dbc.php');
session_start();
date_default_timezone_set('UTC');
echo fetch_chat_history($_POST['to_user_id'],$_SESSION['uid'],$dbc);





function fetch_chat_history($to_user_id,$from_user_id,$dbc){
 $query="SELECT * FROM chat_message WHERE (to_user_id=:to_user_id AND from_user_id=:from_user_id) OR (to_user_id=:from_user_id AND from_user_id=:to_user_id) ORDER BY timestamp asc";
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
$output.='<ul>';
if($statement->rowCount()>0){
   
    $result=$statement->fetchAll();
   
    foreach($result as $row){
        if($row['from_user_id']==$from_user_id){
            $img=$_SESSION['uimg'];
            $class='sent';
        }
        else{
            $img=get_user_img($to_user_id,$dbc);
            $class='replies';
        }
        $msg_time=time_elapsed_string($row['timestamp']);

        $output.='<li class="'.$class.'">
        <img src="'.$img.'" />
        <p>'.$row['chat_message'].'<br><label class="msg"><i class="fa fa-clock-o"></i> '.$msg_time.'</label></p>
    </li>';
        }
}
$output.='</ul>';  
 
    
    
    //update the unseen notifications by following code
     $query="UPDATE chat_message SET status=0
     WHERE to_user_id='$from_user_id' AND from_user_id='$to_user_id' AND status=1";
$statement=$dbc->prepare($query);
$status=$statement->execute();

if(!$status){
    $errors=$statement->errorInfo();
    exit($errors[2]);
}
    

    return $output;
    
    
}


function get_user_img($to_user_id,$dbc){
$query="SELECT img FROM login WHERE id=:id";
$statement=$dbc->prepare($query);
$status=$statement->execute([
    ':id'=>$to_user_id
]);
  $row=$statement->fetch();
    return $row['img'];
}


function time_elapsed_string($datetime, $full = false) {
    $now = new DateTime;
    $ago = new DateTime($datetime);
    $diff = $now->diff($ago);

    $diff->w = floor($diff->d / 7);
    $diff->d -= $diff->w * 7;

    $string = array(
        'y' => 'year',
        'm' => 'month',
        'w' => 'week',
        'd' => 'day',
        'h' => 'hour',
        'i' => 'minute',
        's' => 'second',
    );
    foreach ($string as $k => &$v) {
        if ($diff->$k) {
            $v = $diff->$k . ' ' . $v . ($diff->$k > 1 ? 's' : '');
        } else {
            unset($string[$k]);
        }
    }

    if (!$full) $string = array_slice($string, 0, 1);
    return $string ? implode(', ', $string) . ' ago' : 'just now';
}


?>

