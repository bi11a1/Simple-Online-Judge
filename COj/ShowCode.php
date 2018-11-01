<?php 
    include('header.php');
    include('Options.php');
    include('connection.php');
    if(!isset($_GET['id'])){
        header("location: PracticeSubmission.php");
    }
    $subid = $_GET['id'];
    
?>
<!DOCTYPE html>
<html>
<head>
    <title>Code</title>
    <link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
</head>
<body>
    <center>
    <div class="div-main">
        <h1 align="center">See Code</h1>
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
            $sql = "SELECT * FROM practicecode WHERE submission_id='".$subid."'";
            $result = $conn->query($sql);
            $row = $result->fetch_assoc();
            if($row['U_name']!=$_SESSION['username']){
                header("location: PracticeSubmission.php");
            }
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
        ?>
        <table style="width: 80%" align="center">
            <tr>
                <th><br><h3 style="margin: 5px">Source Code</h3></th>
            </tr>
            <tr>
                <td>
                    <textarea style='width: 100%; height: 550px; border-top: 8px; border-bottom: 6px solid DarkSlateGrey; border-left: 5px; border-right: 3px solid DarkSlateGrey; border-style: inset; padding: 5px; font-size: 110%; color: black;' readonly='yes'><?php echo $row['source']; ?></textarea>
                </td>
            </tr>
        </table>
    </div>
    </center>

</body>
</html>