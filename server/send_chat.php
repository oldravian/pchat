<?php
include('dbc.php');
session_start();
date_default_timezone_set('UTC');
$c_time=date("Y-m-d H:i:s");

$from_user_id=$_SESSION['uid'];
$to_user_id=$_POST['to_user_id'];
$chat_message=$_POST['chat_message'];
$query="INSERT INTO chat_message (to_user_id,from_user_id,chat_message,timestamp,status) VALUES (:to_user_id,:from_user_id,:chat_message,:timestamp,:status)";
$statement=$dbc->prepare($query);
$status=$statement->execute([
    ':to_user_id'=>$to_user_id,
    ':from_user_id'=>$from_user_id,
    ':chat_message'=>$chat_message,
    ':timestamp'=>$c_time,
    ':status'=>1
]);

if(!$status){
    $errors=$statement->errorInfo();
    exit($errors[2]);
}
if($statement->rowCount()>0){
    echo 'success';
}



?>