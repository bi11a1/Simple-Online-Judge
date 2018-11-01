<?php
	include("header.php");
    include("connection.php");
    include("Notification.php");
	ShowNotification();

    if(!isset($_SESSION)){
    	session_start();
    }
	if(isset($_SESSION['username'])){
        header("location: home.php");
   	}

    $username = $pass = "";
    $error = "";

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $pass = mysql_real_escape_string($_POST['pass']);

        $sql = "SELECT * FROM user WHERE username='".$username."'";
        $result = $conn->query($sql);
        if($result->num_rows){
        	$row = $result->fetch_assoc();
        	if($row['usertype'] == 0){
        		$error = "*Please verify your account first.";
        	}
        	else if(password_verify($pass, mysql_real_escape_string($row['pass']))){
        		$_SESSION['username'] = $username;
        		$_SESSION['usertype'] = $row['usertype'];
        		$msg = 'Welcome, '.$_SESSION['username'];
        		SetNotification($msg, "success");
            	header("location: home.php");	
        	}
        	else $error = "*Wrong Password";
        }
        else $error = "*Invalid Username";
    }
?>

<!DOCTYPE html>
<html>
<head>
	<title>Login</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowPassword.css">
	<script type="text/javascript" src="JS/ToggleEye.js"></script>
	<script type="text/javascript" src="JS/jquery-3.1.1.js"></script>

	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<link rel="stylesheet" type="text/css" href="CSS/ShowPassword.css">
	<link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
	<style>
		.error {
		    color: red;
		    float: right; 
		    margin-right: 20px;
		}
		.success {
			color: green;
		    float: right; 
		    margin-right: 20px;
		    font-size: 80%;
		}
    	.category {
    		 width: 100%; 
    		 text-align: left; 
    		 font-size: 120%;
    	}
		.text {
		    width: 100%;
		    padding: 10px;
		    margin: 6px 0 10px 0;
		    font-size: 100%;
		    display: inline-block;
		    border: 1px solid #ccc;
		    border-radius: 4px;
		    box-sizing: border-box;
		}
		.button {
		    width: auto;
		    background-color: darkslategrey;
		    color: white;
		    margin: 10px;
		    padding: 10px;
		    font-size: 100%;
		    border: 2px solid black;
		    border-radius: 10px;
		    cursor: pointer;
		}
		.button:hover {
		    background-color: lavender;
		    color: black;
		    -webkit-transition-duration: 0.5s; /* Safari */
		    transition-duration: 0.5s;
		    opacity: 0.8;
		    box-shadow: 3px 3px 5px grey;
		}
		.button:focus {outline:0;}
	</style>
</head>
<body>
	<center>
	<div class="div-main">
		<h2 style="margin: 50px 0 30px 0">User Login</h2>
    	<div style="width: 30%; display: inline-block;">
		<form method="post" action="login.php">
			<b style="color: red"><?php echo $error; ?><br></b>
			<div class="category"><i class="fa fa-user"></i> UserName:</div>
			<input type="text" class="text" name="username" maxlength="50" placeholder="" required value="<?php echo $username; ?>">
			<br>

			<div class="category"><i class="fa">&#xf023;</i> Password:</div>
			<input type="password" class="pass" id="pass" name="pass" maxlength="50" placeholder="" required value="<?php echo $pass; ?>">
			<div class="eye-div" onclick="toggle_eye('pass', 'eye')" style="">
				<i class="fa fa-eye-slash" id="eye" style="font-size: 20px; padding: 9px 10px;"></i>
			</div>

			<br>
			<input type="submit" class="button" style="font-size: 120%" value="Login">
		</form>
		</div>

		<h3 align="center">Not yet registered? You can<a href="Registration.php" style="color: blue; text-decoration: none;"> Register</a> here.</h3>
	</div>
	</center>
</body>
</html>

<?php
	include('footer.php');
	/*include("Footer/footer-distributed-with-address-and-phones.php");*/
?>