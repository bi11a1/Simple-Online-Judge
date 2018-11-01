<?php 
	include("header.php");
	include("contestoptions.php");
	include("connection.php");
	include('Notification.php');

	ShowNotification();
	
	$cid = $_SESSION['cid'];
	$sql = "SELECT * FROM contest WHERE id='".$cid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$cname = $row['name'];
?>


<!DOCTYPE html>
<html>
<head>
	<title>My Submissions</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
</head>
<body>
	<center>
	<div class="div-main">
		<h1 align="center">Contest: <?php echo $cname; ?></h1>
		<h2 style="margin: 0px; padding:10px; padding-top: 0px;" align="center">My Submissions</h2>

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
			$sql = "SELECT * FROM contestcode WHERE cid='".$cid."' AND username='".$_SESSION['username']."' ORDER BY submissiontime DESC ";
			$result = $conn->query($sql);
			while ($row = $result->fetch_assoc()) {
				$time = new DateTime($row['submissiontime']);
				$date = $time->format('d-m-Y');
				$time = $time->format('H:i:s');
				$submissionid=$row['submissionid'];
				if($row['verdict']=="Accepted") $col="green";
				else $col="red";
				$sql1 = "SELECT * FROM cproblems WHERE cid='".$cid."' AND serial='".$row['pserial']."' ";
				$result1 = $conn->query($sql1);
				$row1 = $result1->fetch_assoc();
				$pid = $row1['pid'];
				echo "<div class='div-description'>
					<table style='width:100%'>
					<tr style='width:100%'>
						<td style='width: 15%;'><a title='See Source Code' style='color:black; text-decoration: none' href='ShowContestCode.php?subid=$submissionid' >".$row['submissionid']."</a></td>
						<td style='width: 40%;'><a style='color:black; text-decoration: none' href='GetContestProblem.php?pid=".$pid.$row['pserial']."'>".$row['pserial'].". ".$row['pname']."</a></td>
						<td style='width: 15%;'>".$date."<br>".$time."</td>
						<td style='width: 15%;'>".$row['language']."</td>
						<td style='width: 15%; color: ".$col."'>".$row['verdict']."</td>
					</tr>
					</table>
				</div>";
			}
		?>
	</div>
	</center>
</body>
</html>