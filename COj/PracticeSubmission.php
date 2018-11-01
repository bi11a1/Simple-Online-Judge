<?php 
    include("header.php");
    include("options.php");
    include("connection.php");
    include('notification.php');
    ShowNotification();

    $page=1;
    if(isset($_GET['page'])) $page=$_GET['page'];
    if($page<=0) $page=1;
?>


<!DOCTYPE html>
<html>
<head>
    <title>My Submissions</title>
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
        <h2 style="margin: 10px; padding:10px;" align="center">My Submissions</h2>

        <table style="width: 100%; border-collapse: collapse;">
            <tr>
                <td class="table-header" style="width: 15%"><b>Submission Id</b></td>
                <td class="table-header" style="width: 40%"><b>Problem</b></td>
                <td class="table-header" style="width: 15%"><b>Time</b></td>
                <td class="table-header" style="width: 15%"><b>Language</b></td>
                <td class="table-header" style="width: 15%"><b>Verdict</b></td>
            </tr>
        </table>

        <?php
            $sql = "SELECT * FROM practicecode WHERE U_name = '".$_SESSION['username']."' ORDER BY submissiontime DESC ";
            $result = $conn->query($sql);

            $rowcount=0;
            $unit = 30; // Maximum number of rows per page
            $found = 0;
            while ($row = $result->fetch_assoc()) {
                $rowcount++;
                if($rowcount>($page-1)*$unit && $rowcount<=$page*$unit){
                    $found = 1;
                    $time = new DateTime($row['submissiontime']);
                    $date = $time->format('d-m-Y');
                    $time = $time->format('H:i:s');
                    if($row['verdict']=="Accepted") $col="green";
                    else $col="red";
                    echo "<div class='div-description'>
                        <table style='width:100%'>
                        <tr style='width:100%'>
                            <td style='width: 15%;'><a href='ShowCode.php?id=".$row['submission_id']."' style='color:black; text-decoration: none'>".$row['submission_id']."</a></td>
                            <td style='width: 40%;'><a href='DescriptionPage.php?id=".$row['problemId']."' style='color:black; text-decoration: none'>".$row['problemId'].". ".$row['probname']."</a></td>
                            <td style='width: 15%;'>".$date."<br>".$time."</td>
                            <td style='width: 15%;'>".$row['language']."</td>
                            <td style='width: 15%; color: ".$col."'>".$row['verdict']."</td>
                        </tr>
                        </table>
                    </div>";
                }
            }
            if($found == 0 and $page != 1) header("location: PracticeSubmission.php");
        ?>

        <?php
            if($page != 1){
                echo '<a class="next-prev" style="float: left" title="Previous Page" href="PracticeSubmission.php?page='.($page-1).'">Prev</a>';
            }
            if($rowcount>0 && $page != ceil($rowcount/$unit)){
                echo '<a class="next-prev" style="float: right" title="Next Page" href="PracticeSubmission.php?page='.($page+1).'">Next</a>';
            }
        ?>
    </div>
    </center>
</body>
</html>