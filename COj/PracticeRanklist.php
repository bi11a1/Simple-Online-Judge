<!DOCTYPE html>

<html>
<head>
  <title>Problem Category List</title>
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
      width: 100%;
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
  tr:hover{background-color:#f5f5f5}
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
  <h1 style="text-align: center;">All Problems</h1>
  <div id="sections02">
    <table>
      <tr style="background-color: #f5f5f5">
        <th>Rank</th>
        <th>Name</th>
        <th>Total Solved</th>
      </tr>
      <?php
      $unit=30;
        $center="center";
        $set="set";
        $user="UserStatistics.php";
        $cnt=0;
        $rank=0;
        $solved=-1;
            include('Connection.php');
            $sql="SELECT Uname, COUNT(prbid) AS total
                  FROM solve
                  GROUP BY Uname
                  ORDER BY total DESC";
            $rslt1=mysqli_query($conn,$sql);
            if(!isset($_GET['page'])) 
            {
                $_GET['page']=0;
            }
            if($conn->query($sql)){
            if($rslt1->num_rows)
            {
                if($conn->query($sql))
                {
                    echo "<tr>
                          </tr>";
                    $cnt=0;
                    while($problem=mysqli_fetch_assoc($rslt1))
                    {
                        if($cnt>=($_GET['page']*$unit) and $cnt<(($_GET['page']*$unit)+$unit))
                        {
                          $currentsolve = $problem['total'];
                          if($solved!=$currentsolve)
                          {
                            $rank++;
                            $solved=$currentsolve;
                          }
                          else
                          { 
                            $solved=$currentsolve;
                          }
                          $query="INSERT INTO ranklist (uname,rank) VALUES ('".$problem['Uname']."',$rank)";
                          $conn->query($query); 
                          echo "<tr>
                                <td>";echo $rank;
                                echo"<td>";echo "<a href='".$user."?name=".$problem['Uname']."' style='color: black;text-decoration: none'>".$problem['Uname']."</a></td>
                                <td>".$problem['total']."</td>";           
                          echo "</tr>";
                        }
                        $cnt++;
                    }
                }
            }
        }
    ?>
  </table>
    </div>
   </div>
</center>
    <?php
                if(($_GET['page']*$unit)+$unit<mysqli_num_rows($rslt1)){
                echo"<h4><a class='a2' style='float: right' href='PracticeRanklist.php?";
                if(isset($_GET['page']))
                {
                    $k=$_GET['page'];
                    $k=$k+1;
                    echo "page=$k;'";
                }
                else echo "page=1;'";
                 echo ">Next</a></h4>"; }
                //else{ echo "></a><h4>";}
                if(isset($_GET['page']))
                {
                    if($_GET['page']>0)
                    {
                        echo"<h4><a class='a2' style='float:left' href='PracticeRanklist.php?";
                        $k=$_GET['page'];
                        $k=$k-1;
                        echo "page=$k;'";
                        echo ">Prev</a></h4>";
                    }
                }
                ?>
</body>
</html>