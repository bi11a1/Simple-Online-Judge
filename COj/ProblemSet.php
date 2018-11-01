<?php 
    include("header.php");
    include("options.php");
    include("connection.php");
    include("notification.php");
    ShowNotification();

    $page=1;
    if(isset($_GET['page'])) $page=$_GET['page'];
    if($page<=0) $page=1;
?>


<!DOCTYPE html>
<html>
<head>
    <title>Problem Set</title>
    <link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
    <link rel="stylesheet" type="text/css" href="CSS/TableStyle.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
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
        .search{
            width: 200px;
            border-right: 0;
            border-radius: 4px 0 0 4px;
        }
    </style>
</head>
<body>
    <center>
    <div class="div-main">
        <h2 style="margin: 10px 10px 0 10px; padding:10px;" align="center">
            All Problems
        </h2>

        <div style="margin-bottom: 20px">
            <form method="GET" action="ProblemSet.php">
                <input type="text" class="text search" placeholder="Search..." required name="search">
                <button type="submit" class="text" style="width: 35px; margin-left: -5px; border-radius: 0 4px 4px 0;"><i class="fa fa-search"></i></button>
            </form>
        </div>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td class="table-header" style="width: 10%"><b>Problem Id</b></td>
                <td class="table-header" style="width: 45%"><b>Name</b></td>
                <td class="table-header" style="width: 15%"><b>Difficulty</b></td>
                <td class="table-header" style="width: 15%"><b>Category</b></td>
                <td class="table-header" style="width: 15%"><b>Author</b></td>
            </tr>
        </table>

        <?php
            if(isset($_GET['search'])){
                $search = mysql_real_escape_string($_GET['search']);
                $sql = "SELECT * FROM problems WHERE hide = 0 AND (
                                id LIKE '"."%".$search."%"."'  OR
                                name LIKE '"."%".$search."%"."'  OR
                                difficulty LIKE '"."%".$search."%"."'  OR
                                category LIKE '"."%".$search."%"."'  OR
                                author LIKE '"."%".$search."%"."'
                            )";
                $result = $conn->query($sql);
            }
            else{
                $sql = "SELECT * FROM problems WHERE hide = 0 ";
                $result = $conn->query($sql);
            }

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