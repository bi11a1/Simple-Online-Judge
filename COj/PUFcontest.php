<?php 
	include("header.php");
	include("options.php");
	include("connection.php");
	include("notification.php");

	$totalProblems = 0;

	if(isset($_GET['setContestId'])){
		$id = $_GET['setContestId'];
		$sql = "SELECT problems FROM contest WHERE id='".$id."'";
		$result = $conn->query($sql);
		if ($result->num_rows == 0) {
			header("location: contest.php");
		}
		else{
			$row = $result->fetch_assoc();
			$totalProblems = $row['problems'];
		}

		if ($_SERVER["REQUEST_METHOD"] == "POST") {
			for ($indx=1, $serial='A'; $indx <= $totalProblems; $indx++, $serial++) {
				$problemId[$serial]=$_POST[$serial];
				$sql = "INSERT INTO cproblems(cid, serial, pid) VALUES ('".$id."', '".$serial."', '".$problemId[$serial]."')" ;
				$conn->query($sql);
			}
			SetNotification("New contest has been added", "success");
			header("location: contest.php");
		}
	}
	else{
		header("location: contest.php");
	}
?>

<!DOCTYPE html>
<html>
<head>
	<title>Set Problems</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<link rel="stylesheet" type="text/css" href="CSS/TableStyle.css">
	<style>
		.new-problem:link, .new-problem:visited{
			margin: 10px 10px 10px 0;
			background-color: #ddd;
			padding: 10px;
			border-radius: 10px;
			box-shadow: 2px 2px 0px grey;
			text-decoration: none;
			color: black;
			position: relative;
		}
		.new-problem:hover{
			text-decoration: none;
			background-color: lightgrey;
			color: black;
		}
	 	.new-problem:active{
			box-shadow: none;
			text-decoration: none;
			color: black;
		}
	</style>
</head>
<body>
	<center>
	<div class="div-main">
	<form method="post" action="pufcontest.php?setContestId=<?php echo $id; ?>">
		<table>
			<caption><h1>Enter problem ids: </h1></caption>
			<?php for($indx=1, $serial='A'; $indx <= $totalProblems; $indx++, $serial++){ ?>
		  	<tr>
		  		<td class="title">Problem-<?php echo $serial, ": "; ?> </td>
		  		<td class="title">
		  			<input type="number" class="text" title="Add an existing problem" name="<?php echo $serial; ?>" required value="<?php echo $problemId[$serial] ?>">
		  		</td>
		  		<td class="title">
		  			<span class="text" style="border: 0">Or</span>
		  		</td>
		  		<td class="title">
		  			<a class="new-problem" href="AddProblem.php" title="Add a new problem" target="blank">Add</a>
		  		</td>
		  	</tr>
		  	<?php } ?>
		</table>
		<input type="submit" class="submit" name="submit" style="margin: 15px; margin-bottom: 40px">
	</form>
	</div>
	</center>
</body>
</html>