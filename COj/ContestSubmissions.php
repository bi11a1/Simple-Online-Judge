<?php 
	include("header.php");
	include("contestoptions.php");
	include("connection.php");

	$cid = $_SESSION['cid'];
	$sql = "SELECT * FROM contest WHERE id='".$cid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$cname = $row['name'];

	$page=1;
	if(isset($_GET['page'])) $page=$_GET['page'];
	if($page<=0) $page=1;
?>


<!DOCTYPE html>
<html>
<head>
	<title>My Submissions</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<style>
		a.next-prev:link, a.next-prev:visited{
			margin: 20px;
			background-color: #ddd;
			padding: 10px;
			box-shadow: 0px 2px 0px grey;
			text-decoration: none;
			color: black;
			position: relative;
		}
		a.next-prev:hover{
			text-decoration: none;
			background-color: lightgrey;
			color: black;
		}
	 	a.next-prev:active{
			box-shadow: none;
			text-decoration: none;
			color: black;
		}
	</style>
</head>
<body>
	<center>
	<div class="div-main">
		<h1 align="center">Contest: <?php echo $cname; ?></h1>
		<h2 style="margin: 0px; padding:10px; padding-top: 0px;" align="center">All Submissions</h2>

		<table style="width: 100%; border-collapse: collapse;">
			<tr>
				<td class="table-header" style="width: 25%"><b>User</b></td>
				<td class="table-header" style="width: 30%"><b>Problem</b></td>
				<td class="table-header" style="width: 15%"><b>Time</b></td>
				<td class="table-header" style="width: 15%"><b>Language</b></td>
				<td class="table-header" style="width: 15%"><b>Verdict</b></td>
			</tr>
		</table>

		<?php
			$sql = "SELECT * FROM contestcode WHERE cid='".$cid."' ORDER BY submissiontime DESC ";
			$result = $conn->query($sql);

			$rowcount=0;
			$unit = 30; // Maximum number of rows per page
			$found = 0;
			while ($row = $result->fetch_assoc()) {
				$rowcount++;
				if($rowcount>($page-1)*$unit && $rowcount<=$page*$unit){
					$found = 1;
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
							<td style='width: 25%;'><a style='text-decoration: none; color: black' href='UserStatistics.php?name=".$row['username']."'>".$row['username']."</a></td>
							<td style='width: 30%;'><a style='color:black; text-decoration: none' href='GetContestProblem.php?pid=".$pid.$row['pserial']."'>".$row['pserial'].". ".$row['pname']."</a></td>
							<td style='width: 15%;'>".$date."<br>".$time."</td>
							<td style='width: 15%;'>".$row['language']."</td>
							<td style='width: 15%; color: ".$col."'>".$row['verdict']."</td>
						</tr>
						</table>
					</div>";
				}
			}
			if($found == 0 and $page != 1) header("location: ContestSubmissions.php");
		?>

		<?php
			if($page != 1){
				echo '<a class="next-prev" style="float: left" title="Previous Page" href="ContestSubmissions.php?page='.($page-1).'">Prev</a>';
			}
			if($rowcount>0 && $page != ceil($rowcount/$unit)){
				echo '<a class="next-prev" style="float: right" title="Next Page" href="ContestSubmissions.php?page='.($page+1).'">Next</a>';
			}
		?>
	</div>
	</center>
</body>
</html>