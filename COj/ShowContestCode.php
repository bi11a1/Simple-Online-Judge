<?php 
	include('header.php');
	include('ContestOptions.php');
	include('connection.php');
	if(!isset($_GET['subid'])){
		header("location: MyContestSubmissions.php");
	}
	$cid = $_SESSION['cid'];
	$sql = "SELECT * FROM contest WHERE id='".$cid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$cname = $row['name'];

	$subid = $_GET['subid'];
	
?>
<!DOCTYPE html>
<html>
<head>
	<title>Contest Code</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
</head>
<body>
	<center>
	<div class="div-main">
		<h1 align="center">Contest: <?php echo $cname; ?></h1>

		<table style="width: 100%; border-collapse: collapse;">
			<tr>
				<td class="table-header" style="width: 15%"><b>Submission Id</b></td>
				<td class="table-header" style="width: 40%"><b>Problem</b></td>
				<td class="table-header" style="width: 15%"><b>Time</b></td>
				<td class="table-header" style="width: 15%"><b>Language</b></td>
				<td class="table-header" style="width: 15%"><b>Verdict</b></td>
			</tr>
		</table>

		<?php
			$sql = "SELECT * FROM contestcode WHERE submissionid='".$subid."'";
			$result = $conn->query($sql);
			$row = $result->fetch_assoc();
			if($row['username']!=$_SESSION['username']){
				header("location: RunningContest.php");
			}
			$time = new DateTime($row['submissiontime']);
			$date = $time->format('d-m-Y');
			$time = $time->format('H:i:s');
			$submissionid=$row['submissionid'];
			$pid = $row['pid'];
			if($row['verdict']=="Accepted") $col="green";
			else $col="red";
			echo "<div class='div-description'>
				<table style='width:100%'>
				<tr style='width:100%'>
					<td style='width: 15%;'>".$row['submissionid']."</td>
					<td style='width: 40%;'><a style='color:black; text-decoration: none' href='GetContestProblem.php?pid=".$pid.$row['pserial']."'>".$row['pserial'].". ".$row['pname']."</a></td>
					<td style='width: 15%;'>".$date."<br>".$time."</td>
					<td style='width: 15%;'>".$row['language']."</td>
					<td style='width: 15%; color: ".$col."'>".$row['verdict']."</td>
				</tr>
				</table>
			</div>";
		?>
		<table style="width: 80%" align="center">
			<tr>
				<th><br><h3 style="margin: 5px">Source Code</h3></th>
			</tr>
			<tr>
				<td>
					<textarea style='width: 100%; height: 550px; border-top: 8px; border-bottom: 6px solid DarkSlateGrey; border-left: 5px; border-right: 3px solid DarkSlateGrey; border-style: inset; padding: 5px; font-size: 110%; color: black;' readonly='yes'><?php echo $row['sourcecode']; ?></textarea>
				</td>
			</tr>
		</table>
	</div>
	</center>

</body>
</html>