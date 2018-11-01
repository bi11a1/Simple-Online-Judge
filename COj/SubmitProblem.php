<?php
	include('header.php');
	include("options.php");
	include('connection.php');

	if(!isset($_SESSION)){
		session_start();
	}


	//$pid = $_GET['pid'];
	$pid="";
	$error="";
	// PROBLEM INFORMATION
	
	$formatErr = "";

	if ($_SERVER["REQUEST_METHOD"] == "POST") {

		$verdict = "";
		$formatErr = "*Wrong output file format!";
		$language = $_POST['language'];
		$sourcecode = mysqli_real_escape_string($conn,$_POST['sourcecode']);
		$pid = $_POST['pid'];

		$sql = "SELECT * FROM problems WHERE id='".$pid."'";
		$result = $conn->query($sql);
		$row = $result->fetch_assoc();
		$pname = $row['name'];

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
					}
					else
					{ 
						$verdict = "Accepted";
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
						header("location:SubmitProblem.php");
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
	<style>
		a:link.a1, a:visited.a1{
			color: blue;
			text-decoration: none;
		}
		a:hover.a1{
			text-decoration: underline;
		}
		input[type=text],input[type=password], select {
				padding: 12px 20px;
				margin: 8px 0;
				font-size: 100%;
				display: inline-block;
				border: 1px solid #ccc;
				border-radius: 4px;
				box-sizing: border-box;
		}
	</style>
</head>
<body>
		<center>
	<span style="float: left; width: 100%; margin-left: 3%"></span>
		<form method="post" action="login.php">
		<b style="color: red"><?php echo $error; ?><br></b>
			<b>Problem Id: </b><input type="text" name="pid" maxlength="50" placeholder="" required value="<?php echo $pid; ?>">
			<br><br><br>
		</span>
		<form method="POST" action="SubmitPracticeCode.php?pid=<?php echo $_GET['pid']; ?>" enctype="multipart/form-data">

			<table border="0">    
				<tr><td><center><span style="font-size: 120%">Input file: <a class="a1" href="<?php echo 'Problems/'.$pid.'in.txt'; ?>" download>Download</a></center></td></tr>
				<tr>
					<td>
						<textarea name="sourcecode" cols="93" rows="25" maxlength="1000000" style="border-top: 10px; border-bottom: 8px solid DarkSlateGrey; border-left: 5px; border-right: 2px solid DarkSlateGrey; border-style: inset; padding: 2px; color: black;" placeholder="Enter your code here..." required></textarea>
					</td>
				</tr>
			</table>
			<span style="color: red"><?php echo $formatErr; ?><br></span>
			<p style="font-size: 120%; margin: 10px;">Your Output file(.txt format): 
			<input style="width: 175px;" type="file" title=".txt file only" name="fileToUpload" id="fileToUpload" required>
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
				<tr>
					<td><center><input type="submit" value="Submit" style="width: 70px; margin-bottom: 20px; height: 30px"></center></td>
				</tr>
			</table>
		</form>
		</center>

	</span>
</body>
</html>