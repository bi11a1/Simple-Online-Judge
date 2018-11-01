<?php 
	include("header.php");
	include("contestoptions.php");
	include("connection.php");

	if(!isset($_SESSION)) session_start();
	if(!isset($_GET['editid']) or $_SESSION['usertype']!=3) header("location: contest.php");
	if(!isset($_SESSION['cid'])) header("location: contest.php");
	if($_GET['editid'] != $_SESSION['cid']) header("location: contest.php");

	$editid = $_GET['editid'];

	$sql = "SELECT * FROM contest WHERE id = '".$editid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$name = $row['name'];
	$date = $row['date'];
	$time = $row['time'];
	$problems = $row['problems'];
	$duration = $row['duration'];
	$pass = $row['pass'];
	$author = $row['author'];
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		//$type = $_POST["type"];
		$name = $_POST['name'];
		$date = $_POST["date"];
		$time = $_POST["time"];
		$duration = $_POST["duration"];
		$author = $_POST["author"];
		$pass = $_POST["pass"];
		$problems = $_POST['problems'];

		$sql = "UPDATE contest SET name='".$name."', date='".$date."', time='".$time."', duration='".$duration."', problems='".$problems."', pass='".$pass."', author='".$author."' WHERE id='".$editid."' ";
		if($conn->query($sql)){
			header("location: pufEditContest.php?editContest=$editid");
		}
		else echo $conn->error;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Edit Contest</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<link rel="stylesheet" type="text/css" href="CSS/TableStyle.css">
</head>
<body>
	<center>
	<div class="div-main">
	<form method="post" action="EditContest.php?editid=<?php echo $editid; ?>">
		<table>
			<caption><h1>Edit contest: <?php echo $name; ?></h1></caption>
		  	<tr>
		  		<td class="title">Author: </td>
		  		<td class="title">
		  			<input type="text" class="text" style="background-color: lavender" name="author" readonly maxlength="100" required value="<?php echo $_SESSION['username']; ?>">
		  		</td>
		  	</tr>
		  	<tr>
		  		<td class="title">Contest Name: </td>
		  		<td class="title">
		  			<input type="text" class="text" name="name" maxlength="100" required value="<?php echo $name; ?>">
		  		</td>
		  	</tr>
			<tr>
				<td class="title">Contest Time: </td>
		  		<td class="title">
		  			<input type="date" class="text" style="width: 55%; float: left;" name="date" title="Date: DD/MM/YYYY" min="2015/01/01" max="9999/01/01" required value="<?php echo $date; ?>">
		  			<input type="time" class="text" style="width: 40%; float: right;" name="time" title="Time: HH:MM" required value="<?php echo $time; ?>">
		  		</td>
		  	</tr>
			<tr>
				<td class="title">Contest Duration: </td>
		  		<td class="title">
		  			<input type="Number" class="text" name="duration" min="1" required title="In minute" value="<?php echo $duration; ?>">
		  		</td>
		  	</tr>
		  	<tr>
		  		<td class="title">Total no of Problems: </td>
		  		<td class="title">
		  			<!--<?php echo $problems; ?>-->
		  			<input type="Number" class="text" name="problems" min="1" max="26" required value="<?php echo $problems; ?>">
		  		</td>
		  	</tr>
		  	<tr>
		  		<td class="title">Contest Password: </td>
		  		<td class="title">
		  			<input type="text" class="text" name="pass" maxlength="100" pattern="[A-Z .a-z_0-9\-]{1,50}" required title="[A-Z .a-z_0-9-]" value="<?php echo $pass; ?>">
		  		</td>
		  	</tr>
		  	<tr>
		  		<td class="title"></td>
		  		<td class="title">
		  			<input type="submit" class="submit" name="submit">
		  		</td>
		  	</tr>
		</table>
	</form>
	</div>
	</center>
</body>
</html>