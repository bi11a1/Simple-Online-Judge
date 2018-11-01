<!DOCTYPE html>
<html>
<head>
	<meta charset="utf-8">
	<script type="text/javascript" src="Notify/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="Notify/notify.js"></script>
</head>
<body>
	<?php
		if(!isset($_SESSION)) session_start();
		function SetNotification($msg, $type){
			$_SESSION['notify']="<script>$.notify('".$msg."', '".$type."');</script>";
		}
		function ShowNotification(){
			if(isset($_SESSION['notify'])){
				echo $_SESSION['notify'];
				unset($_SESSION['notify']);
			}
			//else echo "<script>$.notify('Not Found', 'error');</script>";
		}
	?>
</body>
</html>