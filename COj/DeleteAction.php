<?php
	include('Connection.php');
	include('Notification.php');

	/*Delete Notice*/
	if (isset($_POST['delNotice'])) {
		$sql = "DELETE FROM notice WHERE id = '".$_GET['noticeId']."'";
		if($conn->query($sql)){
			SetNotification("The notice has been deleted!", "success");
		}
		header("location: home.php");
	}

	/*Delete Contest*/
	if(isset($_POST['delContest'])){
		$sql = "DELETE FROM contest WHERE id = '".$_GET['contestId']."'";
		if($conn->query($sql)){
			$msg = $_GET['cname']." has been deleted!";
			SetNotification($msg, "success");
		}
		header("Location: contest.php");
	}

	/*Delete Problem*/
	if(isset($_POST['delProblem'])){
		$sql = "DELETE FROM problems WHERE id = '".$_GET['problemId']."'";
		if($conn->query($sql)){
			$msg = $_GET['problemId'].". ".$_GET['problemName']." has been deleted!";
			SetNotification($msg, "success");	
		}
		header("Location: ProblemSet.php");
	}

	/*Show Hidden Problem*/
	if(isset($_POST['showProblem'])){
		$sql = "UPDATE problems SET hide=0 WHERE id = '".$_GET['problemId']."'";
		if($conn->query($sql)){
			$msg = $_GET['problemId'].". ".$_GET['problemName']." is now visible for practice!";
			SetNotification($msg, "success");	
		}
		header("Location: ProblemSet.php");	
	}

	/*Promote user to problem setter*/
	if(isset($_POST['promote'])){
		$sql = "UPDATE user SET usertype = 2 WHERE username = '".$_GET['userId']."' ";
		if($conn->query($sql)){
			$msg = $_GET['userId']." has been promoted! User must logout to view the changes!";
			SetNotification($msg, "success");
		}
		header('Location: UserStatistics.php?name='.$_GET['userId'].'');
	}

	/*Demote user to contestant*/
	if(isset($_POST['demote'])){
		$sql = "UPDATE user SET usertype = 1 WHERE username = '".$_GET['userId']."' ";
		if($conn->query($sql)){
			$msg = $_GET['userId']." has been demoted! User must logout to view the changes!";
			SetNotification($msg, "success");
		}
		header('Location: UserStatistics.php?name='.$_GET['userId'].'');
	}
	
?>