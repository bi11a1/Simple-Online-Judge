<?php
	include("header.php");
	include("contestOptions.php");
	include("connection.php");

	$cid = $_SESSION['cid'];
	$sql = "SELECT * FROM contest WHERE id='".$cid."'";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$cname = $row['name'];
	$cproblems = $row['problems'];
?>


<!DOCTYPE html>
<html>
<head>
	<title>Standings</title>
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
		td.td1{
			border: 0px;
			padding: 3px;
		}
		tr.tr1{
			text-align: center;
			border: 1px solid #ddd;
		}
		tr.tr1:hover{
			background-color: white;
			border: 2px solid lightgrey;
		}
	</style>
</head>
<body>
	<center>
	<div class="div-main">
		<h1 align="center">Contest: <?php echo $cname; ?></h1>
		<h2 style="margin: 0px; padding:10px; padding-top: 0px;" align="center">Ranklist</h2>

		<table style="width: 100%; border-collapse: collapse;">
			<tr>
				<td class="table-header" style="width: 5%"><b>Rank</b></td>
				<td class="table-header" style="width: 20%"><b>Name</b></td>
				<td class="table-header" style="width: 10%"><b>Solved/Penalty</b></td>

				<!-- <div class="div-header" style="width: 5%"><b>Rank</b></div>
				<div class="div-header" style="width: 20%"><b>Name</b></div>
				<div class="div-header" style="width: 10%"><b>Solved/Penalty</b></div> -->
				<?php 
					$totWidth = 65;
					for ($i=0, $serial='A'; $i < $cproblems; $i++, $serial++) { 
						$ProblemWidth[$i] = $totWidth/$cproblems;
					}
					for ($i=0, $serial='A'; $i < $cproblems; $i++, $serial++) { 
						echo '<td class="table-header" style="width: '.$ProblemWidth[$i].'%"><b>'.$serial.'</b></td>';
					}
				?>
			</tr>
		</table>

		<?php
			$sql = "SELECT * FROM contestrank WHERE cid='".$cid."' ORDER BY solved DESC, penalty";
			$result = $conn->query($sql);
			$rank = 1;
			while ($row = $result->fetch_assoc()) {
				echo 
				"<div class='div-description'>
					<table style='width:100%;'>
					<tr style='width:100%'>
						<td style='width: 5%;'>".$rank."</td>
						<td style='width: 20%;'><a style='text-decoration: none; color: black' href='UserStatistics.php?name=".$row['username']."'>".$row['username']."</a></td>
						<td style='width: 10%;'>".$row['solved'].'/'.floor($row['penalty']/60)."</td>";
					for ($i=0, $serial='A'; $i < $cproblems; $i++, $serial++) { 
						$sql1 = "SELECT verdict FROM contestcode WHERE cid='".$cid."' AND username='".$row['username']."' AND pserial='".$serial."' AND verdict='Rejected' ";
						$result1 = $conn->query($sql1);
						$totaltry = $result1->num_rows;
						$sql1 = "SELECT verdict FROM contestcode WHERE cid='".$cid."' AND username='".$row['username']."' AND pserial='".$serial."' AND verdict='Accepted' ";
						$result1 = $conn->query($sql1);
						if($result1->num_rows){
							echo "<td style='color: green; width: ".$ProblemWidth[$i]."%;'>&#10004<br>(".($totaltry+1).")</td>";
						}
						else if($totaltry != 0){
							echo "<td style='color: red; width: ".$ProblemWidth[$i]."%;'>&#10008<br>(".$totaltry.")</td>";
						}
						else{
							echo "<td style='color: black; width: ".$ProblemWidth[$i]."%;'>-</td>";
						}
					}
					$rank++;
				echo "</tr>
					</table>
				</div>";
			}
		?>
	</div>
	</center>

</body>
</html>