<?php
	include('header.php');
	include('options.php');
	include('connection.php');
	include('notification.php');
	if(!isset($_SESSION)) session_start();
	if(!isset($_GET['noticeId'])) header("location: home.php");
	$noticeId=$_GET['noticeId'];
	$sql = "SELECT * FROM notice WHERE id='".$noticeId."'";
	$result = $conn->query($sql);
	if($result){
		$row = $result->fetch_assoc();
		$author = $row['author'];
		$description = $row['description'];
		$title = $row['title'];
	}
	else{
		header("location: home.php");
	}
	if($_SESSION['username']!=$author and $_SESSION['usertype']!=3) header("location: home.php");

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$author = $_POST['author'];
		$title = mysql_real_escape_string($_POST['title']);
		$description = mysql_real_escape_string($_POST['description']);
		date_default_timezone_set("Asia/Dhaka");
  		$curdatetime = date("Y-m-d H:i:s");

  		$sql = "UPDATE notice SET author ='".$author."', title = '".$title."', description = '".$description."' WHERE id = '".$noticeId."' ";
  		$conn->query($sql);
  		SetNotification("The notice informations have been updated.", "success");
  		header("location: home.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Notice</title>
	<link rel="stylesheet" href="CSS/ConfirmDelete.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
		input[type=submit].form {
			font-size: 120%;
		    background-color: #4CAF50;
		    color: white;
		    padding: 14px 20px;
		    margin: 8px 0;
		    border: none;
		    border-radius: 4px;
		    cursor: pointer;

		}
		input[type=submit].form:hover {
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
		<h1 style="padding-left: 2%; margin-top: 30px; text-align: left;">Enter notice informations:</h1>
		<!-- --------------------Delete Notice Here-------------------- -->
		<div style="padding-left: 2%; text-align: left;" title="Delete Notice">
		<button class="confirm-button" onclick="document.getElementById('del-notice').style.display='block'"><i style="font-size:30px" class="fa">&#xf014</i></button>
		</div>
		<div id="del-notice" class="modal">  
		  	<div class="modal-content animate">
			    <div style="display: inline-block; width: 100%; text-align: left;">
			    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 150%;">Delete Notice:</div>
			    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 120%;background-color: #ffdddd; border-left: 6px solid #f44336;">Are you sure you want to delete this notice?</div>
			    	<form method="post" action="DeleteAction.php?noticeId=<?php echo($noticeId); ?>">
						<input type="Submit" class="delbtn" name="delNotice" value="Delete">
					</form>
					<button class="cancelbtn" onclick="document.getElementById('del-notice').style.display='none'">Cancel</button>
					<span id="del"></span>
			    </div>
		  	</div>
		</div>

		<script>
			// Get the modal
			var modal = document.getElementById('del-notice');
			// When the user clicks anywhere outside of the modal, close it
			window.onclick = function(event) {
			    if (event.target == modal) {
			        modal.style.display = "none";
			    }
			}
		</script>
		<!-- ---------------------End of delete Notice-------------------- -->

		<form method="POST" action="EditNotice.php?noticeId=<?php echo $noticeId; ?>">
			<table style="width: 100%">
				<tr>
					<td class="td1">
						<p style="font-size: 120%; margin-bottom: 0px;">Author:</p>
						<input type="text" name="author" readonly style="width: 65%; background-color: #ddd" value="<?php echo $_SESSION['username']; ?>">
					</td>
				</tr>
				<tr>
					<td class="td1">
						<p style="font-size: 120%; margin-bottom: 0px;">Notice Title:</p>
						<input type="text" name="title" required maxlength="300" style="width: 65%;" value="<?php echo $title; ?>">
					</td>
				</tr>
				<tr>
					<td class="td1">
						<p style="font-size: 120%; margin-bottom: 10px;">Description:</p>
						<textarea name="description" cols="60" style="width: 65%; height: 390px" id="description"><?php echo $description; ?></textarea>
					</td>
				</tr>
				<tr>
					<td style="padding-left: 30%;">
						<input type="submit" class="form" value="Update">
					</td>
				</tr>
			</table>
		</form>
	</div>
	</center>
</body>
</html>