<?php
include('server/dbc.php');
session_start();
if(!isset($_SESSION['uid'])){
header('location:login.php');
}
?>

<!DOCTYPE html>
<html>
<head>
<meta charset="utf-8">
<meta name="viewport" content="width=device-width, initial-scale=1, shrink-to-fit=no user-scalable=no">
<link rel="stylesheet" href="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/css/bootstrap.min.css">
<script src="https://ajax.googleapis.com/ajax/libs/jquery/3.3.1/jquery.min.js"></script>
<script src="https://maxcdn.bootstrapcdn.com/bootstrap/4.1.0/js/bootstrap.min.js"></script>
<link href='https://fonts.googleapis.com/css?family=Source+Sans+Pro:400,600,700,300' rel='stylesheet' type='text/css'>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/meyer-reset/2.0/reset.min.css'>
<link rel='stylesheet prefetch' href='https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.6.2/css/font-awesome.min.css'>

<link rel="stylesheet" href="chat.css">
<title>pCHAT: Realtime Chat App</title>
<link rel="icon" type="image/gif/png" href="https://image.flaticon.com/icons/png/512/16/16808.png">
</head><body>

<div id="frame">
	<div id="sidepanel">
		<div id="profile">
			<div class="wrap">
				<img id="profile-img" src="<?php echo $_SESSION['uimg']; ?>" class="online" />
				<p> <?php echo $_SESSION['uname']; ?> </p>
			</div>
		</div>
		<div id="contacts">
			<ul>
			<div id="userdata"></div>
			<div class="dummy-contacts">
			<?php include_once('dummy-contacts.php'); ?>
</div>
			
		</ul>
		</div>
	</div>
	<div class="content">
		<div class="contact-profile">
			<img class="receiver_img" src="" />
			<p class="receiver_name"></p>
			<div class="social-media" style="margin-right: 5px;">
                <a href="server/logout.php"><button class="btn btn-sm theme-btn">Signout <i class="fa fa-sign-out" aria-hidden="true"></i></button>
</a>
			</div>
		</div>
		<div class="messages" data-receiver_id=""><ul></ul></div>
		<div class="message-input" align="center">
			<div class="wrap">
			<input type="text" placeholder="Write your message..." class="message-box"/>
			<button id="submit_button" class="submit"><i class="fa fa-paper-plane" aria-hidden="true"></i></button>
			</div>
		</div>
	</div>
</div>
<script src="chat.js"></script>
</body></html>