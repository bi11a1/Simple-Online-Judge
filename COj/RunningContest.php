<?php
	if(!isset($_SESSION)){
		session_start();
	}
	include('header.php');
	include('contestOptions.php');
	include('connection.php');
	include('Notification.php');
	ShowNotification();

	$cid = $_SESSION['cid'];

	$sql = "SELECT * FROM contest WHERE id='".$cid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$cname = $row['name'];
	$cproblems = $row['problems'];
	$cduration = $row['duration'];
  	date_default_timezone_set("Asia/Dhaka");
  	$curdatetime = date("Y-m-d H:i:s");
	$ctime = $row['date'].' '.$row['time'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Contest</title>
	<link rel="stylesheet" href="CSS/ConfirmDelete.css">
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
		a:link.a1{
			background-color: white;
			text-decoration:none; 
		}
		a:hover.a1{
			background-color: #f5f5f5;
		}
		td a.a1{
			text-decoration: none;
		   	display: block;
			padding: 15px;
		}
	</style>
</head>
<body>
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
	<div style="display: inline-block; width: 100%">
		<div class="problems-div">
			<h1 align="center">Contest: <?php echo $cname; ?></h1>
			<div style="float: left; width: 100%;">
				<div style="width: 100%; background-color: white; border: 1px solid #ddd;">
					<table style="width: 100%;border-spacing: 0px; border-collapse: collapse;">
					<?php
						$sql = "SELECT * FROM cproblems WHERE cid='".$cid."' ORDER BY serial ";
						$result = $conn->query($sql);
						echo "
						<tr>
							<th style='border-bottom: 1px solid #ddd; background-color: #f5f5f5'>
								<h2>Problems:</h2>
							</th>
						</tr>";
						while($row = $result->fetch_assoc()){
							echo "<tr>";
							echo"<td style='border-bottom: 1px solid #ddd'><a class='a1' style='font-size:120%; color: black' href='GetContestProblem.php?";
							$tmp=$row['pid'].$row['serial'];
							echo "pid=$tmp'><b>".$row['serial']."</b>";

							//PROBLEM INFORMATION TO SHOW IN DASHBOARD
							$sql1 = "SELECT name FROM problems WHERE id='".$row['pid']."' ";
							$result1 = $conn->query($sql1);
							$row1 = $result1->fetch_assoc();
							echo ".&nbsp&nbsp".$row1['name']."</a></td>";
							echo "</tr>";
						}
					?>
					</table>
				</div>
			</div>
		</div>
		<center>
		<div style="width: 23%; float: right; padding: 1%;">
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
			<?php if($_SESSION['usertype']==3){ ?>
				<!-- Only type 3 can edit/delete contest -->
				<span class="side-div">
					<p style="font-size: 120%;">Choose an action:</p>
					<hr style="width: 90%; margin: 0px;">

					<a class="set-option" href="EditContest.php?editid=<?php echo $cid; ?>">Edit Contest <i class="fa">&#9998;</i><br></a>
					<hr style="width: 90%; margin: 0px;">

					<!-- Delete Contest here -->
					<a class="set-option" href="#" onclick="document.getElementById('del-contest').style.display='block'"><i class="fa">Delete Contest &#xf014</i><br></a>
					<hr style="width: 90%; margin: 0px; margin-bottom: 20px">
					<div id="del-contest" class="modal">  
					  	<div class="modal-content animate">
						    <div style="display: inline-block; width: 100%; text-align: left;">
						    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 150%;">Delete Contest: <?php echo $cname; ?></div>
						    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 120%;background-color: #ffdddd; border-left: 6px solid #f44336;">Are you sure you want to delete this Contest?</div>
						    	<form method="post" action="DeleteAction.php?contestId=<?php echo $cid; ?>&cname=<?php echo $cname; ?>">
									<input type="Submit" class="delbtn" name="delContest" value="Delete">
								</form>
								<button class="cancelbtn" onclick="document.getElementById('del-contest').style.display='none'">Cancel</button>
								<span id="del"></span>
						    </div>
					  	</div>
					</div>
					<script>
						// Get the modal
						var modal = document.getElementById('del-contest');
						// When the user clicks anywhere outside of the modal, close it
						window.onclick = function(event) {
						    if (event.target == modal) {
						        modal.style.display = "none";
						    }
						}
					</script>
				</span>
			<?php } ?>
		</div>
		</center>
	</div>
</body>
</html>