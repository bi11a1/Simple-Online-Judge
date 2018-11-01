<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<title>Problem Category</title>
	<style>
	#sections02 
    {
      	background-color: white;
        width:100%;
        float:left;
    }
    table.user 
    {
    	border-collapse: collapse;
    	width: 100%;
	}
	th.user, td.user 
	{
    	padding: 8px;
    	text-align: center;
    	border-bottom: 1px solid #ddd;
    	font-size: 120%;
	}
	tr.user:hover{background-color:#f5f5f5}
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
	include("header.php");
	include("options.php");
	if(!isset($_SESSION))
	{
		session_start();
	}
?>
    <center>
    <div class="div-main">
    <h2 style="text-align: center;">All Category List</h2>
    	<div id="sections02">
        <table class="user">
      		<tr class="user" style="background-color: #f5f5f5">
        		<th class="user">Category List</th>
                <th class="user">Total Problems</th>
        		<th class="user">Total Solved</th>
      		</tr>
      		<?php
      			$unit=30;
      			$use="user";
              $center="center";
              $pcat="ProblemCategoryList.php";
              include('Connection.php');
              $sql="SELECT DISTINCT category
                    FROM problems";
              $rslt1=mysqli_query($conn,$sql);
              if(!isset($_GET['page'])) 
              {
                $_GET['page']=0;
              }
              if($conn->query($sql))
              {
                if($rslt1->num_rows)
                {
                  if($conn->query($sql))
                  {
                    echo "<tr class=$use>
                            </tr>";
                      $cnt=0;
                      while($problem=mysqli_fetch_assoc($rslt1))
                      {
                   	    if($cnt>=($_GET['page']*$unit) and $cnt<(($_GET['page']*$unit)+$unit))
                        {
                           echo "<tr class=$use>
                                    <td class=$use>";echo "<a href='".$pcat."?id=".$problem['category']."' style='color: black;text-decoration: none'>".$problem['category']."</td>";
                            $sql="SELECT COUNT(id) AS total_prob,hide
                                  FROM problems
                                  WHERE category='".$problem['category']."'and hide=0";
                                  $rslt2=mysqli_query($conn,$sql);
                                  if($rslt2->num_rows)
                                  {
                                    if($conn->query($sql))
                                    {
                                      while($probe=mysqli_fetch_assoc($rslt2))
                                      {
                                        echo "<td class=$use>";echo "".$probe['total_prob']."</td>";
                                      }
                                    }
                                  }
                              }
                              $sql="SELECT COUNT(id) AS total_prb,hide
                               FROM problems,Solve
                               WHERE category='".$problem['category']."' and hide=0 and Uname='".$_SESSION['username']."' and id=prbid";
                               $rslt2=mysqli_query($conn,$sql);
                              if($rslt2->num_rows)
                              {
                                if($conn->query($sql))
                                {
                                  while($probe=mysqli_fetch_assoc($rslt2))
                                  {
                                    echo "<td class=$use>";echo "".$probe['total_prb']."</td>";
                                  }
                                }
                              }
                            echo "</tr>";
                        }
                        $cnt++;
               	    }
                }
            }
        ?>
    	</table>
      </div>
        <?php
        if(($_GET['page']*$unit)+$unit<mysqli_num_rows($rslt1))
        {
          echo"<h4><a class='a2' style='float: right' href='ProblemCategory.php?";
          if(isset($_GET['page']))
          {
            $k=$_GET['page'];
            $k=$k+1;
            echo "page=$k;'";
          }
          else echo "page=1;'";
          echo ">Next</a></h4>"; 
        }
        if(isset($_GET['page']))
        {
          if($_GET['page']>0)
          {
            echo"<h4><a class='a2' style='float:left' href='ProblemCategory.php?";
            $k=$_GET['page'];
            $k=$k-1;
            echo "page=$k;'";
            echo ">Prev</a></h4>";
          }
        }
        ?>
    </div>
    </center>
</body>
</html>