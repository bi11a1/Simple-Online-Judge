<?php
	include('connection.php');
	include('header.php');
	include('options.php');
	include('notification.php');
	ShowNotification();
	if(!isset($_GET['cid'])){
		header('location: contest.php');
	}
	if(isset($_SESSION['cid']) and $_SESSION['cid']==$_GET['cid']){
		header("location: RunningContest.php");
	}

	$cid=$_GET['cid'];
	$pass = $error = "";

	$sql = "SELECT * FROM contest WHERE id='".$cid."' ";
  	$result = $conn->query($sql);
  	$row = $result->fetch_assoc();
  	$cname = $row['name'];
  	$cduration = $row['duration'];

  	date_default_timezone_set("Asia/Dhaka");
  	$curdatetime = date("Y-m-d H:i:s");
	$ctime = $row['date'].' '.$row['time'];

	if($_SERVER["REQUEST_METHOD"] == "POST"){
		$pass = $_POST['pass'];
        if($row['pass'] == $pass){
        	$_SESSION['cid']=$cid;
            header("location: RunningContest.php");
        }
        else{
        	$error = "<br>*Wrong Password<br>";
        }
	}

?>

<!DOCTYPE html>
<html>
<head>
	<title>Contest Login</title>
	<script type="text/javascript" src="Notify/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="Notify/notify.js"></script>
	<style>
		div.d2{
			width: 50%; 
			min-height: 250px;
			background-color: white; 
			margin: 100px;
			border: 1px solid #ddd;
			border-radius: 20px;
			box-shadow: 1px 0 15px -4px black, -1px 0 15px -4px black;
		}

		input[type=text],input[type=password], select {
		    padding: 12px 20px;
		    margin: 8px;
		    font-size: 100%;
		    display: inline-block;
		    border: 1px solid #ccc;
		    border-radius: 4px;
		    box-sizing: border-box;
		}
		input[type=submit] {
		    background-color: #4CAF50;
		    color: white;
		    padding: 14px 20px;
		    margin: 8px 0;
		    margin-bottom: 50px;
		    border: none;
		    border-radius: 4px;
		    cursor: pointer;

		}
		input[type=submit]:hover {
		    background-color: #45a049;
		}
	</style>
</head>
<body>
    <script type="text/javascript">
		function date_time(id)
		{	
			curDate = new Date();
	        contestDate = new Date(<?php echo strtotime($ctime)*1000; ?>);
	        diff = contestDate-curDate;
	        if(diff<=0){
	        	diff = 0;
	        	//alert('Contest has started!');
	        	location.reload();
	        }
	        document.getElementById(id).innerHTML = show(diff);
	        setTimeout('date_time("'+id+'");','1000');
	        return true;
		}
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
	        	if(hr>1) result += min+' mins ';
	        	else result += min+' min ';
	        }
	        if(sec<0) sec=0;
	        if(sec>=0) result += sec+' sec';
	        return result;
		}
	</script>
    <div align="center">
    	<div class="d2">
    	<br><br><h1 style="margin: 0px;">Contest: <?php echo $cname, "<br>"?></h1>

    	<?php 
    	//If contest is not yet started
    	if($ctime>$curdatetime){ ?>
    		<p style="background-color: #e7f3fe; font-size: 140%; padding: 10px;">Starts in:<br><span id="date_time"></span></p>
			<script type="text/javascript">window.onload = date_time('date_time');</script>

			<!-- SET USER PRIORITY TO EDIT THE CONTEST -->
			<?php if($_SESSION['usertype']==3){ ?>
				<form method="post" action="ContestLogin.php?cid=<?php echo $cid; ?>">
					<span style="color: red;"><?php echo $error; ?></span>
					<span style="font-size: 120%; ">Contest Password:&nbsp&nbsp</span><input type="text" name="pass" maxlength="100" placeholder="" required value="<?php echo $pass; ?>">
					<br>

					<input type="submit" style="font-size: 120%" value="Enter">
				</form>
			<?php } ?>
		<?php }

		//If contest is running
		else if(strtotime($curdatetime)-strtotime($ctime)<=$cduration*60){ ?>
			<p style="background-color: #ddffdd; font-size: 140%; padding: 10px;">Contest is Running!<br>Time remaining: <span id="running_time"></span></p>
			<script type="text/javascript">window.onload = running_time('running_time');</script>
			<form method="post" action="ContestLogin.php?cid=<?php echo $cid; ?>">
				<span style="color: red;"><?php echo $error; ?></span>
				<span style="font-size: 120%">Contest Password:&nbsp&nbsp</span><input type="text" name="pass" maxlength="100" placeholder="" required value="<?php echo $pass; ?>">
				<br>

				<input type="submit" style="font-size: 120%" value="Enter">
			</form>
		<?php }

		//If contest has ended
		else { ?>
			<p style="background-color: #ffffcc; font-size: 140%; padding: 10px;">Contest Has Ended!</p>
			<form method="post" action="ContestLogin.php?cid=<?php echo $cid; ?>">
				<span style="color: red;"><?php echo $error; ?></span>
				<span style="font-size: 120%">Contest Password:&nbsp&nbsp</span></b><input type="text" name="pass" maxlength="100" placeholder="" required value="<?php echo $pass; ?>">
				<br>
				<input type="submit" style="font-size: 120%" value="Enter">
			</form>
		<?php } ?>

		</div>
	</div>

</body>
</html>