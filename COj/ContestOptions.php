<?php
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['username'])){
        header("location: login.php");
    }
    if(!isset($_SESSION['cid'])){
        header("location: contest.php");
    }
    // To know about which page is currently active
    // e.g: /coj/home.php is converted into home and stored into $curPage   
    $tmp=strtolower($_SERVER['REQUEST_URI']);
    if(!isset($_SESSION)){
        session_start();
    }
    if(!isset($_SESSION['username'])){
        header("location: login.php");
    }
    $curPage="";
    for ($i=0; $i < strlen($tmp); $i++) { 
        if($tmp[$i]=="/") $curPage="";
        else if(substr($tmp, $i, 4)==".php") break;
        else $curPage=$curPage.$tmp[$i];
    }
?>

<!DOCTYPE html>
<html>
<head>
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style>
        .sticky-div{
            width: 100%;
            position: -webkit-sticky; /*For safari*/
            position: sticky;
            top: 0;
            box-shadow: 3px 3px 3px #888888;
        }

        ul.options{
            width: 100%;
            list-style-type: none;
            margin: 0px;
            padding: 0;
            background-color: white;
            overflow: hidden;
            box-shadow: 3px 3px 3px #888888;
        }
        li.options{
            float: left;
        }

        li a.options{
            display: block;
            font-size: 125%;
            color: black;
            text-align: center;
            padding: 10px 10px;
            text-decoration: none;
        }
        li a.options:hover{
            background-color: lavender;
        }

        li a.active{   
            background-color: rgb(214, 215, 231);
            display: block;
            font-size: 125%;
            color: black;
            text-align: center;
            padding: 10px 10px;
            text-decoration: none;
        }
        li a.active:hover{
            background-color: rgb(214, 215, 231);
        }

        /*----For Dropdown Design----*/
        .dropdown:hover {
            background-color: lavender;
        }

        .dropdown {
            display: inline-block;
        }

        .dropdown-content {
            display: none;
            position: absolute;
            background-color: #f9f9f9;
            box-shadow: 0px 8px 16px #888888;
            border: 1px solid lightblue;
            z-index: 1;
        }

        .dropdown-content a {
            color: black;
            padding: 10px 14px;
            text-decoration: none;
            display: block;
            text-align: left;
        }

        .dropdown-content a:link {
            font-size: 105%;
            background-color: white;
            align-content: left;
        }
        .dropdown-content a:hover {
            background-color: lavender;
        }

        .dropdown-left:hover .dropdown-content-left {
            display: block;
        }
        .dropdown-right:hover .dropdown-content-right {
            display: block;
            left: auto;
            right: 0px;
        }
    </style>
</head>
<body>
    <div class="sticky-div">
        <ul class="options">
            <li class="options"><a class="options<?php if($curPage == 'home') print(' active'); ?>" href="home.php" >Home</a></li>
            <li class="options"><a class="options<?php if($curPage == 'runningcontest') print(' active'); ?>" href="RunningContest.php" >Problems</a></li>
            <li class="options dropdown dropdown-left"><a class="options<?php if($curPage == 'mycontestsubmissions' or $curPage == 'contestsubmissions') print(' active'); ?>">Submissions</a>
                <div class="dropdown-content dropdown-content-left">
                    <a href="MyContestSubmissions.php">My Submissions</a>
                    <a href="ContestSubmissions.php">All Submissions</a>
                </div>
            </li>
            <li class="options"><a class="options<?php if($curPage == 'conteststandings') print(' active'); ?>" href="ContestStandings.php">Ranklist</a></li>
            <li class="options dropdown dropdown-right" style="float: right; padding-right: 10px;"><a class="options"><i style="font-size: 105%; margin: 0px; padding: 0px;" class="fa">&#xf2be;</i>&nbsp&nbsp<?php echo $_SESSION['username']; ?></a>
                 <div class="dropdown-content dropdown-content-right">
                    <a href="MyInformation.php">My Info</a>
                    <a href="EditProfile.php">Edit Profile</a>
                    <a href="Logout.php"><span style="color: darkgreen;">Logout</span></a>
                </div>   
            </li>
        </ul>
    </div>

</body>
</html>
