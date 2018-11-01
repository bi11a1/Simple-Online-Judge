<?php
	include('header.php');
	include('options.php');
	include('connection.php');
	include('notification.php');

	if(!isset($_SESSION)){
		session_start();
	}

	if(!isset($_GET['pid'])){
		header('location: Practice.php');
	}

	$pid = $_GET['pid'];
	
	// PROBLEM INFORMATION
	$sql = "SELECT * FROM problems WHERE id='".$pid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$pname = $row['name'];
	$formatErr = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$verdict = "";
		$formatErr = "*Wrong output file format!";
		$language = $_POST['language'];
		$sourcecode = mysqli_real_escape_string($conn,$_POST['sourcecode']);

		if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
			if ($_FILES['fileToUpload']['type'] == "text/plain") {
				if ($_FILES["fileToUpload"]["size"] > 100000000) {
				    $formatErr = "*Output file is too large";
				}
				else{
				  	date_default_timezone_set("Asia/Dhaka");
				  	$curdatetime = date("Y-m-d H:i:s");

					$formatErr = "";
					$locOut = "Problems/".$pid."out.txt";
					$judgeOutput = mysqli_real_escape_string($conn,file_get_contents($locOut));
					$userOutput = mysqli_real_escape_string($conn,file_get_contents($_FILES['fileToUpload']['tmp_name'])); /////UPloaded code is in here.......
					if($judgeOutput!=$userOutput)
					{ 
						$verdict = "Rejected";
						SetNotification("Rejected!", "error");
					}
					else
					{ 
						$verdict = "Accepted";
						SetNotification("Accepted!", "success");
					}

					//-----------------------------INSERT INFORMATIONS INTO DATABASE-----------------------
					$sql = "INSERT INTO practicecode(language, verdict, U_name, problemId, source, submissiontime, probname) VALUES ('".$language."', '".$verdict."', '".$_SESSION['username']."', '".$pid."', '".$sourcecode."', '".$curdatetime."', '".$pname."')";
					if($conn->query($sql))
					{
						if($verdict=='Accepted')
						{
							$query="INSERT INTO solve(prbid,Uname) VALUES ('".$pid."','".$_SESSION['username']."')";
							$conn->query($query);
						}
						header("location:PracticeSubmission.php");
					}
				}
			}
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Submit Code</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<script type="text/javascript" src="Notify/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="Notify/notify.js"></script>
	<style>
		a:link.a1, a:visited.a1{
			color: blue;
			text-decoration: none;
		}
		a:hover.a1{
			text-decoration: underline;
		}
	</style>
</head>
<body>
	<!--For checking the file extension
	allowedExtensions can be an array: like ['jpeg', 'jpg', 'png']
	It will also clear the field (i.e: the id) if a wrong extension is encountered 
	maxSize is in MB -->
	<script type="text/javascript">
	function validate(filename, id, allowedExtensions, maxSize) {
	    var ext = filename.split(".");
	    var fileSize = Math.floor( ($(id)[0].files[0].size)/(1024*1024) );
	    ext = ext[ext.length-1].toLowerCase();
	    if (allowedExtensions.lastIndexOf(ext) == -1) {
	    	var msg = '.'+allowedExtensions[0]+' format only';
	        $.notify(msg, 'error');
	        $(id).val("");
	    }
	    else if( fileSize > maxSize ){
	    	var msg = 'Your file is '+fileSize+' MB, File size must be less than '+maxSize+' MB';
	    	$.notify(msg, 'error');
	    	$(id).val("");
	    }
	}
	</script>

	<!-- To disable multiple form submission -->
	<script type="text/javascript">
		$(function()
		{
		  $('#submitProblem').submit(function(){
		    $("input[type='submit']", this)
		      .val("Submitting...")
		      .attr('disabled', 'disabled');
		    return true;
		  });
		});
	</script>
	<center>
	<div class="div-main">
		<h1 align="center"><?php echo $pid.'. '.$pname; ?></h1>

		<span style="font-size: 120%">Input file: <a class="a1" href="<?php echo 'Problems/'.$pid.'in.txt'; ?>" download="<?php echo $pid.'. '.$pname.'.txt'; ?>">Download</a></span>
		<form id="submitProblem" method="POST" action="SubmitPracticeCode.php?pid=<?php echo $_GET['pid']; ?>" enctype="multipart/form-data">
			<table border="0">		
				<tr>
					<td>
						<textarea name="sourcecode" cols="93" rows="25" maxlength="1000000" style="border-top: 10px; border-bottom: 8px solid DarkSlateGrey; border-left: 5px; border-right: 2px solid DarkSlateGrey; border-style: inset; padding: 2px; color: black;" placeholder="Enter your code here..." required></textarea>
					</td>
				</tr>
			</table>
			<span style="color: red"><?php echo $formatErr; ?><br></span>
			<p style="font-size: 120%; margin: 10px;">Your Output file(.txt format): 
			<input style="width: 175px;" type="file" title=".txt file only" name="fileToUpload" id="fileToUpload" required onChange="validate(this.value, fileToUpload, ['txt'], 10)">
			</p>
			<table>
				<tr>
					<td>
						<span style="font-size: 120%"> Select Language: </span>
					</td>
					<td>
						<select name="language" style="width: 75px; font-size: 100%; padding: 5px;">
							<option value="C++">C++</option>
							<option value="C">C</option>
							<option value="Java">Java</option>
						</select>
					</td>
				</tr>
				<tr>
					<td><p></p></td>
				</tr>
			</table>
			<input type="submit" value="Submit" style="width: 100px; margin-bottom: 20px; height: 30px">
		</form>
	</div>
	</center>
</body>
</html>