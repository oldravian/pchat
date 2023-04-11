<?php
include('dbc.php');
session_start();
$id=$_SESSION['uid'];
$status=$_POST['is_type'];

$query="UPDATE login_users SET is_type='$status' WHERE user_id='$id'";
$statement=$dbc->query($query);

?>