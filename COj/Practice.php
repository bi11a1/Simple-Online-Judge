<!DOCTYPE html>
<html>
<head>
	<title>Practice</title>
</head>
<style>
	div.prbset{
		width: 50%;
		float: left;
		text-align: center;
		height: 150px;
		line-height: 150px;
	}
	a.prbset:link{
		color: black;
	}
	a.prbset:visited{
		color: black;
	}
	a.prbset:hover{
		color: darkgrey;
	}

</style>
<body>
	<?php
		include("header.php");
		include("options.php");
	?>

	<a class="prbset" href="ProblemSet.php">
		<div class="prbset">
			<h1 align="center">Problem Set</h1>
		</div>
	</a>
	<a class="prbset" href="ProblemCategory.php">
		<div class="prbset">
			<h1 align="center">Problem Category</h1>
		</div>
	</a>
	<a class="prbset" href="PracticeSubmission.php">
		<div class="prbset">
			<h1 align="center">My Submission</h1>
		</div>
	</a>

</body>
</html>