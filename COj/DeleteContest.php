<?php
	include('header.php');
	include('contestOptions.php');
	include('connection.php');

	if(!isset($_SESSION)) session_start();
	if(!isset($_GET['editid']) or $_SESSION['usertype']!=3){
		header("location: runningContest.php");
	}

	$editid = $_GET['editid'];
	$sql = "SELECT * FROM contest WHERE id = '".$editid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$cname = $row['name'];
	$cpass = $row['pass'];
	$error = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$pass = $_POST['pass'];
		if($pass == $cpass){
			//echo $editid;
			$sql = "DELETE FROM contest WHERE id='".$editid."'";
			$conn->query($sql);
			include("Notification.php");
			$msg = $cname." has been deleted!";
			SetNotification($msg, "success");
			header("location: Contest.php");
		}
		else{
			$error = "*Incorrect Password!<br>";
		}
	}
	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Delete Contest</title>
	<style>
		input[type=text],input[type=password], select {
		    padding: 12px 20px;
		    margin: 8px 0;
		    font-size: 100%;
		    display: inline-block;
		    border: 1px solid #ccc;
		    border-radius: 4px;
		    box-sizing: border-box;
		}
		input[type=submit] {
		    background-color: #4CAF50;
		    color: white;
		    padding: 14px 20px;
		    margin: 8px 0;
		    border: none;
		    border-radius: 4px;
		    cursor: pointer;
		}
		input[type=submit]:hover {
		    background-color: #45a049;
		}
	</style>
</head>
<body>
	<center>
		<br><br><h1>Delete Contest: <?php echo $cname; ?></h1>
		<form method="post" action="DeleteContest.php?editid=<?php echo $editid; ?>">
			<span style="color: red; font-size: 120%"><?php echo $error; ?></span>
			<span style="font-size: 120%">Enter Contest Password to confirm: </span><br>
			<input type="text" name="pass" pattern="[A-Z .a-z_0-9\-]{1,50}" required title="[A-Z .a-z_0-9-]" maxlength="100">
			<br>
			<input type="submit" value="SUBMIT">
		</form>
	</center>
</body>
</html>