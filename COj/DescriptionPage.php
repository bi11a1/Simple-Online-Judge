<?php
	include("header.php");
	include("options.php");
	if(!isset($_SESSION))
	{
		session_start();
	}
	include('connection.php');

	$tmp = $_GET['id'];
	
	$sql = "SELECT * FROM problems WHERE id='".$tmp."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();

	$pname = $row['name'];
	$pi = $row['id'];	
	$author = $row['author'];
	$hide = $row['hide'];
?>

<!DOCTYPE html>
<html>
<head>
	<title>Problem Description</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<link rel="stylesheet" href="CSS/ConfirmDelete.css">
	<style>
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
	</style>
</head>
<body>
	<center>
	<div class="div-main">
		<div style="width: 100%;">
			<h1 <?php if($_SESSION['usertype']==3) echo 'style="margin-bottom: 0px"'; ?>>
				<?php echo $pi; echo "."; echo " "; echo $pname; ?>
			</h1>

			<?php if($_SESSION['usertype']==3){ ?>
				<?php if($hide==1){ ?>
				<button class="confirm-button" style="margin: 10px 10px 20px 0px" onclick="document.getElementById('show-problem').style.display='block'" title="Click to show the problem in practice">Accept</button>
					Or
				<?php } ?>

				<button class="confirm-button" style="margin: 10px 10px 20px 10px" onclick="document.getElementById('del-problem').style.display='block'" title="Delete this problem">Delete</button>
			<?php }?>
			<div style="width: 100%">
				<iframe src="Problems/<?php echo $tmp; ?>.pdf#zoom=115" style="width: 80%; height: 1000px; border: 1px solid darkgrey;"></iframe>
			</div>
		</div>
		<span>
			<a href="SubmitPracticeCode.php?pid=<?php echo $_GET['id'] ?>" style="color: white; text-decoration: none"><button type="Submit" name="Submit" class="button"><span>Submit </span></button></a>
		</span>
	</div>
	</center>

	<!-- ----------------Delete Problem-------------= -->
	<div id="del-problem" class="modal">  
	  	<div class="modal-content animate">
		    <div style="display: inline-block; width: 100%; text-align: left;">
		    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 150%;">Delete Problem: <?php echo $pi; echo "."; echo " "; echo $pname; ?></div>
		    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 120%;background-color: #ffdddd; border-left: 6px solid #f44336;">Are you sure you want to delete this problem?</div>
		    	<form method="post" action="DeleteAction.php?problemId=<?php echo($pi); ?>&problemName=<?php echo($pname);?>">
					<input type="Submit" class="delbtn" name="delProblem" value="Delete">
				</form>
				<button class="cancelbtn" onclick="document.getElementById('del-problem').style.display='none'">Cancel</button>
		    </div>
	  	</div>
	</div>

	<!-- ----------------To change hide from 1 to 0-------------= -->
	<div id="show-problem" class="modal">  
	  	<div class="modal-content animate">
		    <div style="display: inline-block; width: 100%; text-align: left;">
		    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 150%;">Show Problem: <?php echo $pi; echo "."; echo " "; echo $pname; ?></div>
		    	<div style="padding: 20px; border-bottom: 1px solid #ddd; font-size: 120%;background-color: #ffdddd; border-left: 6px solid #f44336;">Are you sure you want to show this problem in practice?</div>
		    	<form method="post" action="DeleteAction.php?problemId=<?php echo($pi); ?>&problemName=<?php echo($pname);?>">
					<input type="Submit" class="delbtn" name="showProblem" value="Show">
				</form>
				<button class="cancelbtn" onclick="document.getElementById('show-problem').style.display='none'">Hide</button>
		    </div>
	  	</div>
	</div>

	<script>
		// Get the modal
		var modal1 = document.getElementById('del-problem');
		var modal2 = document.getElementById('show-problem');
		// When the user clicks anywhere outside of the modal, close it
		window.onclick = function(event) {
		    if (event.target == modal1) {
		        modal1.style.display = "none";
		    }
		    else if (event.target == modal2) {
		        modal2.style.display = "none";
		    }
		}
	</script>
	<!-- ------------------End----------------- -->

</body>
</html>