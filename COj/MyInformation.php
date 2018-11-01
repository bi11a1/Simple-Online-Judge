<!DOCTYPE html>
<html>
<head>
	<title>User Information</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<style>
    #sections02 
    {
        background-color: white;
        width:100%;
        float:left;
    }
    table 
    {
      border-collapse: collapse;
   }
   th
   {
      padding: 8px;
      text-align: center;
      border-bottom: 1px solid #ddd;
      font-size: 120%;
   }
   td 
   {
      padding: 8px;
      text-align: center;
      border-bottom: 1px solid #ddd;
      font-size: 120%;
   }
  tr:hover{/*background-color:#f5f5f5*/}
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
  table.set { 
        border-collapse: collapse;
    }

    th.set {
        padding: 8px;
        text-align: center;
        border-bottom: 1px solid LightSteelBlue;
    }

    td.set{
        padding: 8px;
        text-align: center;
        border-top: 1px solid LightSteelBlue;
        border-bottom: 1px solid LightSteelBlue;
        border-right: 1px solid LightSteelBlue;
        border-left: 1px solid LightSteelBlue;
        height: 5px;
    }
    tr:nth-child(even).set{background-color: white }
    tr:nth-child(odd).set{background-color: #f5f5f5}
    </style>
</head>
<body>
<?php
    include("header.php");
    include("options.php");
    include("Notification.php");
    ShowNotification();
    if(!isset($_SESSION))
    {
      session_start();
    }
  ?>
  <center>
<div class="div-main">
		<h1 style="text-align: center;">
			Category: 
			<?php
				if($_SESSION['usertype']==3) echo "<span style='color: red' title='User category'>Admin</span>";
				if($_SESSION['usertype']==2) echo "<span style='color: purple' title='User category'>Problem Setter</span>";
				if($_SESSION['usertype']==1) echo "<span style='color: darkgreen' title='User category'>Contestant</span>";
			?>
		</h1>
		<br>
		<table style="width: 60%;" align="center">
			<tr>
				<td style="border: 1px solid LightSteelBlue; width: 7%">
					<?php 
						$image = 'images/'.$_SESSION['username'].'.jpg';
						if(!file_exists($image)) $image = "images/default.jpg";
					?>
					<img width="100" height="100" style="vertical-align: bottom;" src="<?php echo($image);?>">
				</td>
				<td style="border-right: 1px solid LightSteelBlue;border-left: 1px solid LightSteelBlue;border-top: 1px solid LightSteelBlue;width: 53%;">
				<table style="width: 60%">
				<tr>
					<td style="border-top: 0px;border-right: 0px;border-left: 0px;border-bottom: 0px;text-align: left;">&nbsp&nbsp&nbsp <?php echo $_SESSION['username'];?></td>
				</tr>
				<tr>
					<td style="border-top: 0px;border-right: 0px;border-left: 0px;border-bottom: 0px;text-align: left;font-family: Footlight MT;font-style: Light">
					&nbsp&nbsp&nbsp Join:
					<?php 
					$cnt=0;
     				include("connection.php");
					$sql= "select TIMESTAMPDIFF(YEAR,registration_date,SYSDATE()) AS join_date_year,TIMESTAMPDIFF(MONTH,registration_date,SYSDATE()) AS join_date_month,TIMESTAMPDIFF(DAY,registration_date,SYSDATE()) AS join_date_day
					   from user where username='".$_SESSION['username']."'";
					$rslt1=mysqli_query($conn, $sql);
					if(mysqli_num_rows($rslt1))
					{
						if($conn->query($sql))
						{
							while($user=mysqli_fetch_assoc($rslt1))
							{
								if($user['join_date_year']>0)
								{
									echo $user['join_date_year'];
									$cnt++;
									if($user['join_date_year']>1)
									{
										echo " years ";
									}
									else
									{
										echo " year ";
									}
								}
								if($user['join_date_month']>0)
								{
									echo $user['join_date_month'];
									$cnt++;
									if($user['join_date_month']>1)
									{
										echo " months ";
									}
									else
									{
										echo " month ";
									}	
								}
								if($user['join_date_day']>0)
								{
									echo $user['join_date_day'];
									$cnt++;
									if($user['join_date_day']>1)
									{
										echo " days";
									}
									else
									{
										echo " day";
									}
								}
								if($cnt>0)
								{
									echo " ago";
								}
								else
								{
									echo "Joined Today";
								}
							}
						}
					}
					?>
					<br>
					&nbsp&nbsp&nbsp Institution:
					<?php
					include("connection.php");
					$sql="SELECT institution FROM user WHERE username='".$_SESSION['username']."'";
					$rslt1=mysqli_query($conn,$sql);
					if(mysqli_num_rows($rslt1)!=0)
					{
						if($conn->query($sql))
						{
							while ($user=mysqli_fetch_assoc($rslt1))
							{
								if($user['institution']!=null) echo $user['institution'];
								else echo "Null";
							}
						}
					}
					else
					{
						echo "NA";
					}
					?>
				
				</td>
			</tr>
			</table>
			</td>
			</tr>
		</table>
		<br><br>
		<h1 style="text-align: center;">
			<b>Statistics</b>
		</h1>
		<table class="set" style="width: 35%" align="center">
			<tr class="set">
				<td class="set" style="text-align: center;font-family: Footlight MT;font-style: Light">
					Total Solved:
				</td>
				<td class="set" style="text-align: center">
					<?php
					include("connection.php");
					$sql="SELECT COUNT(prbid) AS no_of_solve FROM solve WHERE Uname='".$_SESSION['username']."'";
					$rslt1=mysqli_query($conn,$sql);
					if(mysqli_num_rows($rslt1))
					{
						if($conn->query($sql))
						{
							while($user=mysqli_fetch_assoc($rslt1))
							{
								echo $user['no_of_solve'];
							}
						}
					}
					?>
				</td>
			</tr>
			<tr class="set">
				<td class="set" style="text-align: center;font-family: Footlight MT;font-style: Light">
					Total Tried:
				</td>
				<td class="set" style="text-align: center;">
					<?php
					include("connection.php");
					$sql="SELECT COUNT(submission_id) AS no_of_try FROM practicecode WHERE U_name='".$_SESSION['username']."' AND verdict<>'Accepted'";
					$rslt1=mysqli_query($conn,$sql);
					if(mysqli_num_rows($rslt1))
					{
						if($conn->query($sql))
						{
							while($user=mysqli_fetch_assoc($rslt1))
							{
								echo $user['no_of_try'];
							}
						}
					}
					?>
				</td>
			</tr>
			<tr class="set">
				<td class="set" style="text-align: center;font-family: Footlight MT;font-style: Light">
					Total Accepted:
				</td>
				<td class="set" style="text-align: center;">
					<?php
					include("connection.php");
					$sql="SELECT COUNT(submission_id) AS no_of_solve FROM practicecode WHERE U_name='".$_SESSION['username']."' AND verdict='Accepted'";
					$rslt1=mysqli_query($conn,$sql);
					if(mysqli_num_rows($rslt1))
					{
						if($conn->query($sql))
						{
							while($user=mysqli_fetch_assoc($rslt1))
							{
								echo $user['no_of_solve'];
							}
						}
					}
					?>
				</td>
			</tr>
			<tr class="set">
				<td class="set" style="text-align: center;font-family: Footlight MT;font-style: Light">
					Total Submission:
				</td>
				<td class="set" style="text-align: center;">
					<?php
					include("connection.php");
					$sql="SELECT COUNT(submission_id) AS no_of_submit FROM practicecode WHERE U_name='".$_SESSION['username']."'";
					$rslt1=mysqli_query($conn,$sql);
					if(mysqli_num_rows($rslt1))
					{
						if($conn->query($sql))
						{
							while($user=mysqli_fetch_assoc($rslt1))
							{
								echo $user['no_of_submit'];
							}
						}
					}
					?>
				</td>
			</tr>
			<tr class="set">
				<td class="set" style="text-align: center;font-family: Footlight MT;font-style: Light">
					Rank:
				</td>
				<td class="set" style="text-align: center;">
					<?php
          			include("connection.php");
          			$sql="SELECT rank FROM ranklist WHERE uname='".$_SESSION['username']."'";
          			$rslt1=mysqli_query($conn,$sql);
          			if(mysqli_num_rows($rslt1)!=0)
          			{
            			if($conn->query($sql))
            			{
              				while($user=mysqli_fetch_assoc($rslt1))
              				{
                				echo $user['rank'];
              				}
            			}
          			}
          			else
          			{
          				echo "NA";
          			}
          		?>
				</td>
			</tr>
		</table>
		<br><br>
		<h1 style="text-align: center;">
			<b>Acctepted Submissions</b>
		</h1>
		<?php
  			$unit=30;
            $center="center";
            $desc="DescriptionPage.php";
            $seecode="ShowCode.php";
            $set="set";
            include('Connection.php');
            $sql="SELECT *
			  	  FROM practicecode
			   	  WHERE verdict='Accepted' AND U_name='".$_SESSION['username']."'";
			$rslt1=mysqli_query($conn,$sql);
			if(!isset($_GET['page'])) 
    		{
      			$_GET['page']=0;
    		}
    		if(mysqli_num_rows($rslt1)!=0)
    		{
    			if($conn->query($sql))
    			{
    				?>
					<table class="set" align="center" style="width: 40%">
						<tr class="set" style="background-color: white">
							<th class="set" style="border-top: 1px solid LightSteelBlue;border-bottom: 1px solid LightSteelBlue;border-right: 1px solid LightSteelBlue;border-left: 1px solid LightSteelBlue;height: 5px;width: 20%;">Submission Id</th>
							<th class="set" style="border-top: 1px solid LightSteelBlue;border-bottom: 1px solid LightSteelBlue;border-right: 1px solid LightSteelBlue;border-left: 1px solid LightSteelBlue;height: 5px;width: 20%;">Problem Id</th>
							<th class="set" style="border-top: 1px solid LightSteelBlue;border-bottom: 1px solid LightSteelBlue;border-right: 1px solid LightSteelBlue;border-left: 1px solid LightSteelBlue;height: 5px;width: 20%;">Problem Name</th>
						</tr>
					<?php
					echo "<tr>
                          </tr>";
                    $cnt=0;
                    while($problem=mysqli_fetch_assoc($rslt1))
                    {
               	        if($cnt>=($_GET['page']*$unit) and $cnt<(($_GET['page']*$unit)+$unit))
                        {
                                echo "<tr class=$set>
                                       	<td class=$set>";echo "<a href='".$seecode."?id=".$problem['submission_id']."' style='color: black;text-decoration: none'>".$problem['submission_id']."</td>
                                       	<td class=$set>";echo "<a href='".$desc."?id=".$problem['problemId']."' style='color: black;text-decoration: none'>".$problem['problemId']."</td>
                                      	<td class=$set>";echo "<a href='".$desc."?id=".$problem['problemId']."' style='color: black;text-decoration: none'>".$problem['probname']."</td>";
                                      	echo "</tr>";
                        }
                        $cnt++;
                    }
           	    }
            }
            else
            {
            	?> <h3 align="center" style="font-family: Kunstler Script;font-style: Bold Oblique;font-size: 35px">You are yet to solve a problem</h3>
     			<?php
			}
		?>
    </table>
    <?php
      	if(($_GET['page']*$unit)+$unit<mysqli_num_rows($rslt1)){
         	echo"<h4><a class='a2' style='float: right' href='UserStatistics.php?";
            if(isset($_GET['page']))
            {
                $k=$_GET['page'];
                $k=$k+1;
                echo "page=$k;'";
            }
           	else echo "page=1;'";
            echo ">Next</a></h4>"; }
            if(isset($_GET['page']))
            {
                if($_GET['page']>0)
                {
                    echo"<h4><a class='a2' style='float:left' href='UserStatistics.php?";
                    $k=$_GET['page'];
                    $k=$k-1;
                    echo "page=$k;'";
                    echo ">Prev</a></h4>";
                }
            }
    ?>
    <br><br><br>
</div>
</center>
</body>
</html>