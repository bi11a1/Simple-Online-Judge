<!DOCTYPE html>

<html>
<head>
    <link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
  <title>Problem Category List</title>
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
      width: 33%;
   }
   td 
   {
      padding: 8px;
      text-align: center;
      border-bottom: 1px solid #ddd;
      font-size: 120%;
      width: 33%;
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
    include('Connection.php');
    if(!isset($_SESSION))
    {
      session_start();
    }
    $sql = "SELECT * FROM problems WHERE category= '".$_GET['id']."'";
    $result = $conn->query($sql);
    $row = $result->fetch_assoc();

    $cname = $row['category'];
  ?>
  <center>
    <div class="div-main">
    <h1 style="text-align: center;">Category: <?php echo $cname; ?> </h1>
  <div id="sections02">
    <table>
      <tr style="background-color: #f5f5f5">
        <th>Problem Id</th>
        <th>Problem Name</th>
        <th>Solved</th>
      </tr>
      <?php
        $unit=30;
            $center="center";
            $desc="DescriptionPage.php";
            include('Connection.php');
            $sql="SELECT id, name, hide
                  FROM problems
                  WHERE category= '".$_GET['id']."'";
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
                            if($problem['hide']==0)
                            {
                                echo "<tr>
                                        <td>";echo "<a href='".$desc."?id=".$problem['id']."' style='color: black;text-decoration: none'>".$problem['id']."</td>
                                      <td>";echo "<a href='".$desc."?id=".$problem['id']."' style='color: black;text-decoration: none'>".$problem['name']."</a></td>";
                                      $sql1="SELECT Uname, prbid
                                            FROM solve
                                            WHERE Uname='".$_SESSION['username']."' and prbid='".$problem['id']."'";
                                      $rslt2=mysqli_query($conn,$sql1);
                                      if($conn->query($sql1))
                                      {
                                        if($rslt2->num_rows)
                                        {
                                          echo "<td style='color: green'>&#10004</td>";
                                        }
                                        else
                                        {
                                          echo "<td style='color: red'>&#10008</td>";
                                        }
                                      }
                                      
                                echo "</tr>";
                            }
                            else
                            {
                                continue;
                            }
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
                echo"<h4><a class='a2' style='float: right' href='ProblemCategoryList.php?";
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
                        echo"<h4><a class='a2' style='float:left' href='ProblemCategoryList.php?";
                        $k=$_GET['page'];
                        $k=$k-1;
                        echo "page=$k;'";
                        echo ">Prev</a></h4>";
                    }
                }
                ?>
</body>
</html>