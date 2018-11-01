<?php
	include("Connection.php");
	if(!isset($_SESSION)) session_start();
	if(!isset($_GET['email']) or !isset($_GET['hash'])) header("location: home.php");

	$email = $_GET['email'];
	$hash = $_GET['hash'];
	
	$sql = " UPDATE user SET usertype=1 WHERE email='".$email."' AND pass='".$hash."' AND usertype=0 ";
	if($conn->query($sql)){
		include("Notification.php");
		SetNotification("Email has been confirmed. Login now!", "success");
		header("location: login.php");
	}
?>