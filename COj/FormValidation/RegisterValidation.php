<?php
	include('../Connection.php');
	if(isset($_POST['username'])){
		$username = mysql_real_escape_string($_POST['username']);
		if (!preg_match("/^[a-zA-Z _0-9]*$/",$username)) {
			echo "*Letter, Digit, Space or Underscore only!";
			exit();
		}
		$sql="SELECT username FROM user WHERE username='".$username."'";
        $result=$conn->query($sql);
        if($result->num_rows){
        	echo "*Already in use!";
        	exit();
        }
		echo "valid";
	}
	if(isset($_POST['email'])){
		$email = mysql_real_escape_string($_POST['email']);
		if (!filter_var($email, FILTER_VALIDATE_EMAIL)) {
		  	echo "*Invalid email format"; 
		  	exit();
		}
		$sql="SELECT email FROM user WHERE email='".$email."'";
        $result=$conn->query($sql);
        if($result->num_rows){
        	echo "*Email is in use";
        	exit();
        }
        echo "valid";
	}
?>