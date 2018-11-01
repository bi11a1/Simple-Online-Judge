<?php
    include("header.php");
    include("connection.php");
    include("SendEmail.php");
    include("Notification.php");
    ShowNotification();

    if(!isset($_SESSION)) session_start();
    if(isset($_SESSION['username'])) header("location: home.php");

    /*---------------HASHING PASSWORD--------------*/
    function hashPassword($pass_string){
        $options = array(
            'salt' => mcrypt_create_iv(22, MCRYPT_DEV_URANDOM),
            'cost' => 12,
        );
        $hash_value = password_hash($pass_string, PASSWORD_BCRYPT, $options);
        return $hash_value;
    }
    /*-----------------END---------------------*/

    if ($_SERVER["REQUEST_METHOD"] == "POST") {
        $username = $_POST['username'];
        $email = $_POST['email'];
        $pass = $_POST['pass'];
        $hash = mysql_real_escape_string(hashPassword($pass));
        if(SendEmail($email, $hash)){
            $sql = "INSERT INTO user(username, email, pass, usertype) VALUES ('".$username."', '".$email."', '".$hash."', 0)" ;
            if($conn->query($sql)) {
                SetNotification("Please verify your email to complete registration.", "info");
                header("location: login.php");
            }
        }
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Registration</title>
    <script type="text/javascript" src="JS/ToggleEye.js"></script>
	<link rel="stylesheet" type="text/css" href="CSS/ShowPassword.css">

    <script type="text/javascript" src="Notify/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="Notify/notify.js"></script>
	<script src="FormValidation/RegisterValidation.js"></script>

    <link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
    <link rel="stylesheet" href="https://cdnjs.cloudflare.com/ajax/libs/font-awesome/4.7.0/css/font-awesome.min.css">
    <style type="text/css">
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
		.loader {
		    color: grey;
		    float: right;
		    margin-right: 20px;
		    font-size: 80%;
		    margin-bottom: 0px;
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
    	<h2 style="margin: 40px 0">User Registration</h2>
    	<div style="width: 40%; display: inline-block;">
	        <form action="Registration.php" method="post">
	        	<div class="category"><i class="fa fa-user"></i> UserName:<span id="username_val"></span></div>
				<input type="text" class="text" name="username" id="username" maxlength="50" required placeholder="">

	        	<div class="category"><i class="fa">&#xf0e0;</i> E-mail:<span id="email_val"></span></div>
				<input type="email" class="text" name="email" id="email" maxlength="50" required placeholder="">

				<div class="category"><i class="fa">&#xf023;</i> Password:<span id="pass_val"></span></div>
				<input type="password" class="pass" name="pass" id="pass" maxlength="50" required placeholder="">
				<div class="eye-div" onclick="toggle_eye('pass', 'eye')" style="">
					<i class="fa fa-eye-slash" id="eye" style="font-size: 20px; padding: 9px 10px;"></i>
				</div>

				<div class="category"><i class="fa">&#xf023;</i> Confirm-Password:<span id="matchPass_val"></span></div>
				<input type="password" class="pass" name="matchPass" id="matchPass" maxlength="50" required placeholder="">
				<div class="eye-div" onclick="toggle_eye('matchPass', 'matchEye')" style="">
					<i class="fa fa-eye-slash" id="matchEye" style="font-size: 20px; padding: 9px 10px;"></i>
				</div>

				<input type="submit" class="button" id="reg_btn" value="Register" name="submit">
			</form>

		</div>
		<div style="width: 100%">
        	<h3>Already registered? You can<a href="login.php" style="color: blue; text-decoration: none; "> login</a> here.</h3>
        </div>
    </div>
	</center>
</body>
</html>

<?php
	include('footer.php');
?>