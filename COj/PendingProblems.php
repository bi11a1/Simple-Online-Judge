<?php 
    include("header.php");
    include("options.php");
    include("connection.php");

    $page=1;
    if(isset($_GET['page'])) $page=$_GET['page'];
    if($page<=0) $page=1;
?>


<!DOCTYPE html>
<html>
<head>
    <title>Problem Set</title>
    <link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
    <style>
        a.next-prev:link, a.next-prev:visited{
            margin: 20px;
            background-color: #ddd;
            padding: 10px;
            box-shadow: 0px 2px 0px grey;
            text-decoration: none;
            color: black;
            position: relative;
        }
        a.next-prev:hover{
            text-decoration: none;
            background-color: lightgrey;
            color: black;
        }
        a.next-prev:active{
            box-shadow: none;
            text-decoration: none;
            color: black;
        }
    </style>
</head>
<body>
    <center>
    <div class="div-main">
        <h2 style="margin: 10px; padding:10px;" align="center">All Problems</h2>
        
        <div class="div-header" style="width: 10%; font-size: 120%;"><b>Problem Id</b></div>
        <div class="div-header" style="width: 45%; font-size: 120%;"><b>Name</b></div>
        <div class="div-header" style="width: 15%; font-size: 120%;"><b>Difficulty</b></div>
        <div class="div-header" style="width: 15%; font-size: 120%;"><b>Category</b></div>
        <div class="div-header" style="width: 15%; font-size: 120%;"><b>Author</b></div>

        <?php
            $sql = "SELECT * FROM problems WHERE hide = 1 ";
            $result = $conn->query($sql);

            $rowcount = 0;
            $unit = 30; // Maximum number of rows per page
            $found = 0;
            while ($row = $result->fetch_assoc()) {
                $rowcount++;
                if($rowcount>($page-1)*$unit && $rowcount<=$page*$unit){
                    $found = 1;
                    echo "<div class='div-description'>
                        <table style='width:100%; font-size: 110%; padding: 8px'>
                        <tr style='width:100%'>
                            <td style='width: 10%;'><a href='DescriptionPage.php?id=".$row['id']."' style='color:black; text-decoration: none'>".$row['id']."</a></td>
                            <td style='width: 45%;'><a href='DescriptionPage.php?id=".$row['id']."' style='color:black; text-decoration: none'>".$row['name']."</a></td>
                            <td style='width: 15%;'><a href='ProblemDifficultyList.php?id=".$row['difficulty']."' style='color:black; text-decoration: none'>".$row['difficulty']."</a></td>
                            <td style='width: 15%;'><a href='ProblemCategoryList.php?id=".$row['category']."' style='color:black; text-decoration: none'>".$row['category']."</a></td>
                            <td style='width: 15%;'><a href='UserStatistics.php?name=".$row['author']."' style='color:black; text-decoration: none'>".$row['author']."</a></td>
                        </tr>
                        </table>
                    </div>";
                }
            }
            if($found == 0 and $page != 1) header("location: ProblemSet.php");
        ?>

        <?php
            if($page != 1){
                echo '<a class="next-prev" style="float: left" title="Previous Page" href="ProblemSet.php?page='.($page-1).'">Prev</a>';
            }
            if($rowcount>0 && $page != ceil($rowcount/$unit)){
                echo '<a class="next-prev" style="float: right" title="Next Page" href="ProblemSet.php?page='.($page+1).'">Next</a>';
            }
        ?>
    </div>
    </center>
</body>
</html>