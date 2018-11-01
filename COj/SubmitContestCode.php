<?php
	include('header.php');
	include('ContestOptions.php');
	include('connection.php');
	include('Notification.php');

	if(!isset($_SESSION)){
		session_start();
	}

	if(!isset($_GET['pid'])){
		header('location: RunningContest.php');
	}

	$tmp = $_GET['pid'];
	$length = strlen($tmp);
	$pid = substr($tmp, 0, $length-1);
	$pserial = substr($tmp, $length-1, 1);
	$cid = $_SESSION['cid'];

	// CONTEST INFORMATION
	$sql = "SELECT * FROM contest WHERE id='".$cid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$cname = $row['name'];
	$cproblems = $row['problems'];
	$cduration = $row['duration'];
  	date_default_timezone_set("Asia/Dhaka");
  	$curdatetime = date("Y-m-d H:i:s");
	$ctime = $row['date'].' '.$row['time'];

	if(strtotime($curdatetime)-strtotime($ctime)>$cduration*60){
		SetNotification("Contest has ended!", "error");
		header("location: RunningContest.php");
	}

	// PROBLEM INFORMATION
	$sql = "SELECT * FROM problems WHERE id='".$pid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$pname = $row['name'];
	$formatErr = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(strtotime($curdatetime)-strtotime($ctime)>$cduration*60){
			header("location: RunningContest.php");
		}
		$verdict = "";
		$language = $_POST['language'];
		$sourcecode = mysql_real_escape_string($_POST['sourcecode']);

		if (is_uploaded_file($_FILES['fileToUpload']['tmp_name'])) {
		  	date_default_timezone_set("Asia/Dhaka");
		  	$curdatetime = date("Y-m-d H:i:s");

			$formatErr = "";
			$locOut = "Problems/".$pid."out.txt";
			$judgeOutput = mysql_real_escape_string(file_get_contents($locOut));
			$userOutput = mysql_real_escape_string(file_get_contents($_FILES['fileToUpload']['tmp_name'])); /////UPloaded code is in here.......
			if($judgeOutput!=$userOutput) $verdict = "Rejected";
			else $verdict = "Accepted";
			//echo "<script type='text/javascript'>alert('$verdict');</script>";

			// For submission info
			
			if($_SESSION['usertype']==3){
				//if admin submits the problem it wont be inserted into the database
				$msg = "Your submission result = ".$verdict."! But since you are an admin your code will not be stored into the database!";
				if($verdict=="Accepted") SetNotification($msg, "success");
				else SetNotification($msg, "error");
				ShowNotification();
			}
			else{
				// If the user is not admin
				// For ranklist
				$sql = "INSERT INTO contestrank(cid, username) VALUES ('".$cid."', '".$_SESSION['username']."')";
				$conn->query($sql);
				$sql = "SELECT cid FROM contestcode WHERE cid='".$cid."' AND username='".$_SESSION['username']."' AND pserial='".$pserial."' AND verdict='Accepted' ";
				$result=$conn->query($sql);
				if(!($result->num_rows) AND ($verdict == 'Accepted')){	//If the solution is not accepted before
					$sql = "SELECT cid FROM contestcode WHERE cid='".$cid."' AND username='".$_SESSION['username']."' AND pserial='".$pserial."' AND verdict='Rejected' ";
					$result = $conn->query($sql);
					$penalty = (strtotime($curdatetime)-strtotime($ctime))+($result->num_rows)*20000;
					$sql = "UPDATE contestrank SET solved=solved+1, penalty=penalty+'".$penalty."' WHERE cid='".$cid."' AND username='".$_SESSION['username']."' ";
					$conn->query($sql);
				}

				$sql = "INSERT INTO contestcode(cid, username, pid, pserial, pname, language, verdict, submissiontime, sourcecode) VALUES ('".$cid."', '".$_SESSION['username']."', '".$pid."', '".$pserial."', '".$pname."', '".$language."', '".$verdict."', '".$curdatetime."', '".$sourcecode."')" ;
				if($verdict=="Accepted") SetNotification("Accepted!", "success");
				else SetNotification("Rejected!", "error");
				if($conn->query($sql)) {
	                header("location: MyContestSubmissions.php");
	            }
	            else{
	            	echo $conn->error;
	            }
			}
			//echo $language, ' ', $formatErr, ' ', $verdict;
		}
	}
?>


<!DOCTYPE html>
<html>
<head>
	<title>Submit Code</title>
	<script type="text/javascript" src="Notify/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="Notify/notify.js"></script>
</head>
	<style>
		a:link.a1, a:visited.a1{
			color: blue;
			text-decoration: none;
		}
		a:hover.a1{
			text-decoration: underline;
		}
		.side-div{
			width: 95%;
			background-color: white; 
			border-radius: 20px; 
			margin-top: 20px;
			float: right;
			box-shadow: 2px 2px 2px 2px #888888;
		}

		.problems-div{
			margin: 0px 1% 0% 3%;
			padding: 1%;
			background-color: white;
			width: 69%; 
			height: 100%;
			min-height: 600px;
			float: left;
			overflow: auto;
			box-shadow: 1px 0 15px -4px #888888, -1px 0 15px -4px #888888;
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
	<script type="text/javascript">
		function running_time(id)
		{	
			curDate = new Date();
	        contestDate = new Date(<?php echo strtotime($ctime)*1000+$cduration*60*1000; ?>);
	        diff = contestDate-curDate;
	        if(diff<0){
	        	diff = 0;
	        	alert('Contest has ended!');
	        	location.reload();
	        }
	        document.getElementById(id).innerHTML = show(diff);
	        setTimeout('running_time("'+id+'");','1000');
	        return true;
		}
		function show(diff){
			day = Math.floor(diff/(1000 * 60 * 60 * 24));
	        diff = diff - (day*(1000 * 60 * 60 * 24));
	        hr = Math.floor(diff/(1000 * 60 * 60));
	        diff = diff - hr * (1000 * 60 * 60);
	        min = Math.floor(diff/(1000 * 60));
	        diff = diff - min * (1000 * 60);
	        sec = Math.floor(diff/1000);
	        result = '';
	        if(day>0){
	        	if(day>1) result += day+' days ';
	        	else result += day+' day ';
	        }
	        if(hr >0){
	        	if(hr>1) result += hr+' hrs ';
	        	else result += hr+' hr ';
	        }
	        if(min>0){
	        	if(min>1) result += min+' mins ';
	        	else result += min+' min ';
	        }
	        if(sec<0) sec=0;
	        if(sec>=0) result += sec+' sec';
	        return result;
		}
	</script>
	<div style="width: 100%; display: inline-block;">
		<div class="problems-div">
			<h1 align="center"><?php echo $pserial.'. '.$pname; ?></h1>

			<center>
			<span style="font-size: 120%">Input file: <a class="a1" href="<?php echo 'Problems/'.$pid.'in.txt'; ?>" download="<?php echo $pserial.'. '.$pname.'.txt'; ?>">Download</a></span>
			<form id="submitProblem" method="POST" action="SubmitContestCode.php?pid=<?php echo $_GET['pid']; ?>" enctype="multipart/form-data">
				<table border="0">		
					<tr>
						<td>
							<textarea name="sourcecode" cols="93" rows="25" maxlength="1000000" style="border-top: 10px; border-bottom: 8px solid DarkSlateGrey; border-left: 5px; border-right: 2px solid DarkSlateGrey; border-style: inset; padding: 2px; color: black;" placeholder="Enter your code here..." required></textarea>
						</td>
					</tr>
				</table>
				<p style="font-size: 120%; margin: 10px;">Your Output file(.txt format): 
				<input style="width: 200px;" type="file" title=".txt file only" name="fileToUpload" id="fileToUpload" required onChange="validate(this.value, fileToUpload, ['txt'], 10)">
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
				<center><input type="submit" value="Submit" style="width: 100px; margin-bottom: 20px; height: 30px"></center>
			</form>
			</center>

		</div>
		<div style="width: 23%; float: right; padding: 1%">
			<center>
			<div class="side-div">
				<p style="font-size: 120%; padding: 20px;">Contest: <?php echo $cname; ?> </p>
			</div>
			<div class="side-div">
				<?php 
					if($ctime>$curdatetime){
						echo "<p style='padding: 30px; font-size: 120%;'> Contest is not started yet! </p>";
					}
					else if(strtotime($curdatetime)-strtotime($ctime)<=$cduration*60){
						echo '<p style="padding: 30px; font-size: 120%;"><b>Contest is running!</b><br><br>Time Remaining:<br><span id="running_time"></span></b>
							<script type="text/javascript">window.onload = running_time("running_time");</script></p>';
					}
					else{
						echo "<p style='padding: 30px; font-size: 120%'> Contest has ended! </p>";	
					}
				?>
			</div>
			</center>
		</div>
	</div>

</body>
</html>