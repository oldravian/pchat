<?php
include('dbc.php');
session_start();
date_default_timezone_set('UTC');
 if(isset($_SESSION['uid'])){
     $id=$_SESSION['last_activity'];
     $c_time=date("Y-m-d H:i:s");
     $query="UPDATE login_users SET last_activity='$c_time' WHERE id='$id'";
     $statement=$dbc->prepare($query);
     $status=$statement->execute();
     if(!$status){
         $errors=$statement->errorInfo();
         exit($errors[2]);
     }
 }
     ?>