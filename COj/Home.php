<?php

	include('header.php');
	include('options.php');
	include('connection.php');
	include('Notification.php');
	ShowNotification();

	// For Contest Information
	$runningContest = -1;
	$cid = $cname = "";
	date_default_timezone_set("Asia/Dhaka");
  	$curdatetime = date("Y-m-d H:i:s");
	$sql = "SELECT * FROM contest ORDER BY CONCAT( date, ' ', time)";
	$result = $conn->query($sql);
	while($row = $result->fetch_assoc()){
		$cname = $row['name'];
		$cid = $row['id'];
		$ctime = $row['date'].' '.$row['time'];
		$cduration = $row['duration'];
		if($ctime>$curdatetime) {
			$runningContest = 0;
			break;
		}
		else if(strtotime($curdatetime)-strtotime($ctime)<=$cduration*60){
			$runningContest = 1;
			break;
		}
	}

	// For Notice
	$noticeNo = 1;
	if(isset($_GET['noticeNo'])) $noticeNo = $_GET['noticeNo'];
	$title = "";
	$description = "";
	$author = "";
	$datetime = "";
	$next = -1; 
	$prev = -1;
	$sql = "SELECT * FROM notice ORDER BY datetime DESC";
	$result = $conn->query($sql);
	$rowcount=0;
	$unit = 1; // Maximum number of notice per page
	$found = 0;
	while ($row = $result->fetch_assoc()) {
		$rowcount++;
		if($rowcount==$noticeNo){
			$found = 1;
			$title = $row['title'];
			$description = $row['description'];
			$author = $row['author'];
			$datetime = $row['datetime'];
			$noticeId = $row['id'];
		}
	}

	if($found==0) header("location: home.php");
	if($noticeNo != 1) $prev = $noticeNo-1;
	if($rowcount>0 && $noticeNo != ceil($rowcount/$unit)) $next = $noticeNo+1;
?>

<!DOCTYPE html>
<html>
<head>
	<title>Home</title>
	<link rel="stylesheet" type="text/css" href="CSS/TableStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.side-div{
			width: 95%;
			background-color: white; 
			border-radius: 20px; 
			margin-top: 20px;
			float: right;
			box-shadow: 2px 2px 2px 2px #888888;
		}
		.notice-div{
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
		.next-prev{
			margin: 0px 1% 2px 3%;
			padding: 1%;
			width: 69%; 
			float: left;
			overflow: auto;
			background-color: white;
			box-shadow: 1px 0 15px -4px #888888, -1px 0 15px -4px #888888;
		}
		.search{
			width: -moz-calc(100% - 35px); /* Firefox */
			width: -webkit-calc(100% - 35px); /* WebKit */
			width: -o-calc(100% - 35px); /* Opera */
			width: calc(100% - 35px); /* Standard */
			float: left;
			border-right: 0;
			border-radius: 4px 0 0 4px;
		}

		a:link.a1, a:visited.a1{
			color: blue;
			text-decoration: none;
		}
		a:hover.a1, a:active.a1{
			color: dodgerblue;
		}

		a:link.set-option, a:visited.set-option{
			font-size: 120%;
			color: black;
			padding: 4px 0px 4px 0px;
			margin: 0px;
			width: 90%;
			display: inline-block;
			background-color: white;
			text-decoration: none;
		}
		a:hover.set-option, a:active.set-option{
			color: black;
			font-size: 125%;
			background-color: lavender;
			text-decoration: none;
		}

		a.a2:link, a.a2:visited{
			margin: 20px;
			margin-left: 0px;
			margin-right: 0px;
			background-color: #ddd;
			padding: 1%;
			box-shadow: 1px 1px 1px 1px grey;
			text-decoration: none;
			color: black;
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
	<div style="overflow: hidden;">
		<!-- Div for notice -->
		<div class="notice-div">
			<?php
				$time = new DateTime($datetime);
				$date = $time->format('d-m-Y');
				$time = $time->format('h:i a');
				if($_SESSION['username']==$author || $_SESSION['usertype']==3){
					echo '<h1 style="margin-bottom:0px;">'.$title.'</h1>';
				}
				else{
					echo '<h1 style="margin-bottom:0px;">'.$title.'</h1>';
				}
				echo '<p style="padding-left: 1%; margin-top: 0px;font-size: 130%;width: 100%; border-left: 3px solid violet">By: '.$author.'<br>Time: '.$date.' | '.$time.'</p>';
				echo "<hr style='border-top: 1px solid #ddd;'>";

				/*------------Displaying the notice--------------------*/
				echo "<span style='width: 90%; text-align: justify; color: black; font-size: 120%;'>".$description."</span>";
				/*-----------------------------------------------------*/

			?>
		</div>
		<!-- Div for contest,upload notice etc.. -->
		<div style="width: 23%; float: right; padding: 1%;">
			<center>
				<div class="side-div">
					<?php 
						if($runningContest == 1){
							echo '<p style="padding: 30px; padding-bottom: 0px; font-size: 130%;"><b>Running Contest:<br>'.$cname.'</b></p><p style="font-size: 120%; padding-bottom: 30px;">Click <a class="a1" href="ContestLogin.php?cid='.$cid.'">here</a> to enter.</span></p>';
						}
						else if($runningContest == 0){
							$time = new DateTime($ctime);
							$date = $time->format('d-m-Y');
							$time = $time->format('H:i:s');
							echo '<p style="padding: 30px; padding-bottom: 0px; font-size: 130%;"><b>Upcoming Contest:<br>'.$cname.'</b></p><p style="font-size: 120%; padding-bottom: 30p;">'.$date.' | '.$time.'<br>Click <a class="a1" href="ContestLogin.php?cid='.$cid.'">here</a>.</span></p>';
						}
						else{
							echo '<p style="padding: 30px; padding-bottom: 30px; font-size: 130%;">No contest yet!</p>';
						}
					?>
				</div>
				<?php if($_SESSION['usertype']==2 or $_SESSION['usertype']==3) { ?>
					<div class="side-div">

						<p style="font-size: 120%;">Choose an action:</p>
						<hr style="width: 90%; margin: 0px;">

						<?php if($_SESSION['username']==$author or $_SESSION['usertype']==3) { ?>
							<?php echo '<a class="set-option" href="EditNotice.php?noticeId='.$noticeId.'"><i class="fa">Edit Notice &#9998;</i></a>'; ?>
							<hr style="width: 90%; margin: 0px;">
						<?php } ?>
						<!-- Only type 3 can set contest -->
						<?php if($_SESSION['usertype']==3) { ?>
							<a href="SetContest.php" class="set-option">Set Contest <i class="fa" style="font-size: 90%">&#xf271;</i></a>
							<hr style="width: 90%; margin: 0px;">
						<?php } ?>

						<!-- Only type 2 or type 3 can add problems -->
						<?php if($_SESSION['usertype']==2 or $_SESSION['usertype']==3) { ?>
							<a href="AddProblem.php" class="set-option">Add problem <i class="fa">&#xf196;</i></a>
							<hr style="width: 90%; margin: 0px;">
						<?php } ?>

						<!-- Only type 2 or type 3 can add problems -->
						<?php if($_SESSION['usertype']==2 or $_SESSION['usertype']==3) { ?>
							<a href="SetNotice.php" class="set-option">Upload notice <i class="fa" style="font-size: 90%">&#xf24a;</i></a>
						<?php } ?>
						<hr style="width: 90%; margin: 0px; margin-bottom: 20px">
					</div>
				<?php } ?>
				<div class="side-div">
					<form method="GET" action="UserStatistics.php" style="margin: 10%; display: inline-block;">
						<span style="font-size: 120%; float: left; margin-bottom: 3%">Find user:</span>
		                <input type="text" class="text search" placeholder="Search..." required name="name">
		                <button type="submit" class="text" style="width: 35px; float: right; border-radius: 0 4px 4px 0;"><i class="fa" style="font-size: 100%">&#xf002;</i></button>
		            </form>
				</div>
			</center>
		</div>
		<div class="next-prev">
			<?php
			echo "<center>";
				if($prev != -1){
					echo '<a class="a2" style="" title="Previous Notice" href="Home.php?noticeNo='.$prev.'">&#x2329</a>';
				}
				else{
					echo '<a class="a2" style="pointer-events: none; cursor: default; background-color: white; box-shadow: none" title="Previous Notice" href="Home.php?noticeNo='.$prev.'">&#x2329</a>';
				}
				echo "&nbsp&nbsp";
				if($next != -1){
					echo '<a class="a2" style="" title="Next Notice" href="Home.php?noticeNo='.$next.'">&#x232A</a>';	
				}
				else{
					echo '<a class="a2" style="pointer-events: none; cursor: default; background-color: white; box-shadow: none" title="Next Notice" href="Home.php?noticeNo='.$next.'">&#x232A</a>';	
				}
			echo "</center>";
			?>
		</div>
	</div>

</body>
</html>

<?php
	include('footer.php');
?>