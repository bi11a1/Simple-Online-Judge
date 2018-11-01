<?php 
	include("header.php");
	include("options.php");
	include("connection.php");

	$type = $name = $date = $time = $problems = $duration = $pass = $author = "";
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$name = $_POST['name'];
		$date = $_POST["date"];
		$time = $_POST["time"];
		$problems = $_POST["problems"];
		$duration = $_POST["duration"];
		$author = $_POST["author"];
		$pass = $_POST["pass"];

		$sql = "INSERT INTO Contest(name, date, time, duration, problems, pass, author) 
			      	VALUES ('".$name."','".$date."','".$time."','".$duration."','".$problems."','".$pass."','".$author."')";
		if($conn->query($sql)){
			$CId=$conn->insert_id;
			header("location: pufcontest.php?setContestId=$CId");
		}
		else echo $conn->error;
	}
?>

<!DOCTYPE html>
<html>
<head>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<link rel="stylesheet" type="text/css" href="CSS/TableStyle.css">
	<title>Set Contest</title>
</head>
<body>
	<center>
	<div class="div-main">
	<form method="post" action="<?php echo htmlspecialchars($_SERVER["PHP_SELF"]);?>">
		<table>
			<caption><h1>Set a new contest: </h1></caption>
		  	<tr>
		  		<td class="title">Author: </td>
		  		<td class="title">
		  			<input type="text" class="text" style="background-color: lavender" name="author" readonly value="<?php echo $_SESSION['username']; ?>">
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
		  			<input type="Number" class="text" name="duration" min="1" placeholder="In minute" required value="<?php echo $duration; ?>">
		  		</td>
		  	</tr>
		  	<tr>
		  		<td class="title">Total no of Problems: </td>
		  		<td class="title">
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