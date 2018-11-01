<?php
	ob_start();
	if(!isset($_SESSION)){
		session_start();
	}
	if(isset($_SESSION['username'])){
		unset($_SESSION['username']);
		session_destroy();
		session_start();
		include("Notification.php");
		SetNotification("You have logged out.", "info");
	}
	header("location: login.php");
?>