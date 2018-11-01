<?php
	include('header.php');
	include('ContestOptions.php');
	if(!isset($_SESSION)){
		session_start();
	}

	if(!isset($_GET['pid'])){
		header('location: RunningContest.php');
	}

	include('connection.php');

	$tmp = $_GET['pid'];
	$length = strlen($tmp);
	$pid = substr($tmp, 0, $length-1);
	$pserial = substr($tmp, $length-1, 1);
	$cid = $_SESSION['cid'];

	// CONTEST INFORMATION
	$sql = "SELECT * FROM contest WHERE id='".$cid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$cname = $row['name'];
	$cproblems = $row['problems'];
	$cduration = $row['duration'];
  	date_default_timezone_set("Asia/Dhaka");
  	$curdatetime = date("Y-m-d H:i:s");
	$ctime = $row['date'].' '.$row['time'];

	// PROBLEM INFORMATION
	$sql = "SELECT * FROM problems WHERE id='".$pid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$pname = $row['name'];

	
?>

<!DOCTYPE html>
<html>
<head>
	<title>Contest Problem</title>
	<style>
		.side-div{
			width: 95%;
			background-color: white; 
			border-radius: 20px; 
			margin-top: 20px;
			float: right;
			box-shadow: 2px 2px 2px 2px #888888;
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

		.button {
		  border-radius: 10px;
		  background-color: black;
		  border: none;
		  color: #FFFFFF;
		  text-align: center;
		  font-size: 20px;
		  padding: 10px;
		  width: 120px;
		  transition: all 0.5s;
		  cursor: pointer;
		  margin: 5px;
		}
		.button span {
		  cursor: pointer;
		  display: inline-block;
		  position: relative;
		  transition: 0.5s;
		}
		.button span:after {
		  content: '»';
		  position: absolute;
		  opacity: 0;
		  top: 0;
		  right: -20px;
		  transition: 0.5s;
		}
		.button:hover span {
		  padding-right: 25px;
		}
		.button:hover span:after {
		  opacity: 1;
		  right: 0;
		}
		.hidden {
			display: none;
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
		<h1 align="center"><?php echo $pserial.'. '.$pname; ?></h1>
		<iframe src="Problems/<?php echo $pid; ?>.pdf#zoom=110" style="width: 100%; height: 1000px; border: 1px solid darkgrey;"></iframe>
		<center style="margin-bottom: 30px;">
		<?php 
		if($_SESSION['usertype']==3 or ($curdatetime>=$ctime and strtotime($curdatetime)-strtotime($ctime)<=$cduration*60)){ ?>
			<a href="SubmitContestCode.php?pid=<?php echo $_GET['pid'] ?>" style="color: white; text-decoration: none"><button type="Submit" name="Submit" class="button"><span>Submit </span></button></a>
		<?php } ?>
		</center>
	</div>
	<div style="width: 23%; float: right; padding: 1%">
		<center>
			<div class="side-div">
				<p style="font-size: 120%; padding: 20px;">Contest: <?php echo $cname; ?> </p>
			</div>
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
		</div>
		</center>
	</div>
	</div>

</body>
</html>