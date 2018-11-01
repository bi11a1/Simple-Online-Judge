<?php
	include('header.php');
	include('options.php');
	include('connection.php');
	include('notification.php');
	if(!isset($_SESSION)) session_start();
	if($_SESSION['usertype']!=2 and $_SESSION['usertype']!=3) header("location: home.php");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$author = $_POST['author'];
		$title = mysql_real_escape_string($_POST['title']);
		$description = mysql_real_escape_string($_POST['description']);
		date_default_timezone_set("Asia/Dhaka");
  		$curdatetime = date("Y-m-d H:i:s");
  		$sql = "INSERT INTO notice(author, title, description, datetime) VALUES('".$author."', '".$title."', '".$description."', '".$curdatetime."')";
  		$conn->query($sql);
  		SetNotification("Notice has been uploaded.", "success");
  		header("location: home.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>New Notice</title>
	<style>
		td.td1{
			padding-left: 2%;
		}

		input[type=text], select {
			width: 57%;
		    padding: 12px 20px;
		    margin: 8px 0px 0px 0px;
		    font-size: 100%;
		    display: inline-block;
		    border: 1px solid #ccc;
		    border-radius: 4px;
		    box-sizing: border-box;
		}
		input[type=submit] {
			font-size: 120%;
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
		.div-main{
			width: 90%; 
			background-color: white; 
			margin-top: 0px; 
			display: inline-block; 
			align-content: center;
			box-shadow: 1px 0 15px -4px #888888, -1px 0 15px -4px #888888;
		}
	</style>
</head>
<body>
	<script type="text/javascript" src="http://js.nicedit.com/nicEdit-latest.js"></script>
	<script type="text/javascript">
		bkLib.onDomLoaded(function() {
		    new nicEditor({fullPanel : true, maxHeight : 400}).panelInstance('description');
	  	});
	</script>
	<center>
	<div class="div-main">
		<h1 style="float: left; padding-left: 2%;">Enter notice informations:</h1>
		<form method="POST" action="SetNotice.php">
			<table style="width: 100%">
				<tr>
					<td class="td1">
						<p style="font-size: 120%; margin-bottom: 0px;">Author:</p>
						<input type="text" name="author" readonly style="width: 65%; background-color: lavender" value="<?php echo $_SESSION['username']; ?>">
					</td>
				</tr>
				<tr>
					<td class="td1">
						<p style="font-size: 120%; margin-bottom: 0px;">Notice Title:</p>
						<input type="text" name="title" required maxlength="300" style="width: 65%;">
					</td>
				</tr>
				<tr>
					<td class="td1">
						<p style="font-size: 120%; margin-bottom: 10px;">Description:</p>
						<textarea name="description" cols="60" style="width: 65%; height: 390px" id="description"></textarea>
						<!-- <textarea name="description" maxlength="1000000" style="padding: 10px; font-size: 120%; margin-top: 10px; color: black; width: 70%; height: 600px;" required></textarea> -->
					</td>
				</tr>
				<tr>
					<td style="padding-left: 30%;">
						<input type="submit" value="Upload">
					</td>
				</tr>
			</table>
		</form>
	</div>
	</center>
</body>
</html>