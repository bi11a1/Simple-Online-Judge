<?php
	include('header.php');
	include('options.php');
	include('connection.php');
	include('Notification.php');
	ShowNotification();

	$cpage = 1;
	if(isset($_GET['cpage'])) $cpage = $_GET['cpage'];
	if($cpage<=0) $cpage = 1;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Contest</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<style>
		table.t1{
			width: 100%;
			border-collapse: collapse;
		}
		th.th1{
			width: 20%;
			padding: 5px;
			border: 1px solid #ddd;
		}
		tr.tr1{
			text-align: center;
			border: 1px solid #ddd;
		}
		tr.tr1:hover{
			background-color: white;
			border: 2px solid lightgrey;
		}
		td a.a1{
			text-decoration: none;
		   	display: block; 
		   	border: 0px;
			padding: 15px; 
		}

		a.a1:link, a.a1:visited {
			color: black;
			background-color: white;
			text-decoration:none; 
		}
		a.a1:hover, a.a1:active{
			background-color: white;
		}

		a.a2:link, a.a2:visited{
			margin: 20px;
			background-color: #ddd;
			padding: 10px;
			box-shadow: 0px 2px 0px grey;
			text-decoration: none;
			color: black;
			position: relative;
		}
		a.a2:hover{
			text-decoration: none;
			background-color: lightgrey;
			color: black;
		}
	 	a.a2:active{
			box-shadow: none;
			text-decoration: none;
			color: black;
		}
	</style>
</head>
<body>
	<?php 
		if(isset($_SESSION['print'])) echo $_SESSION['print'];
		unset($_SESSION['print']);
	?>
	<center>
	<div class="div-main">
		<h1 style="padding: 20px; margin: 0px; background-color: white; color: black">
			<b>Contest list</b>
		</h1>
		
		<div class="div-header" style="width: 50%;"><b>Name</b></div>
		<div class="div-header" style="width: 20%;"><b>Author</b></div>
		<div class="div-header" style="width: 15%;"><b>Time</b></div>
		<div class="div-header" style="width: 15%;"><b>Duration</b></div>

		<?php
			$sql = "SELECT * FROM contest ORDER BY CONCAT( date, ' ', time) DESC";
			$result = $conn->query($sql);
			$rowcount = 0;
			$unit = 20; // Maximum number of rows per page
			$found = 0;
			while ($row = $result->fetch_assoc()) {
				$rowcount++;
				$cid = $row['id'];
				if($rowcount>($cpage-1)*$unit && $rowcount<=$cpage*$unit){
					$found = 1;
					$time = new DateTime($row['date'].' '.$row['time']);
					$date = $time->format('d-m-Y');
					$time = $time->format('H:i:s');
					echo "<a class='div-description' style='padding: 10px 0px 10px 0px;' href='contestLogin.php?cid=$cid' >
						<table style='width:100%'>
						<tr style='width:100%'>
							<td style='width: 50%;'>".$row['name']."</td>
							<td style='width: 20%;'>".$row['author']."</td>
							<td style='width: 15%;'>".$date, "<br>", $time."</td>
							<td style='width: 15%;'>".floor($row['duration']/60)." hr ".($row['duration']%60)."</td>
						</tr>
						</table>
					</a>";
				}
			}
			if($found==0) header("location: contest.php");
		?>
	<?php
		if($cpage != 1){
			echo '<a class="a2" style="float: left" title="Previous Page" href="Contest.php?cpage='.($cpage-1).'">Prev</a>';
		}
		if($rowcount>0 && $cpage != ceil($rowcount/$unit)){
			echo '<a class="a2" style="float: right" title="Next Page" href="Contest.php?cpage='.($cpage+1).'">Next</a>';
		}
	?>
	</div>
</center>

</body>
</html>