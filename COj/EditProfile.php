<?php
    include("connection.php");
    include("header.php");
    include("options.php");

    $sql = "SELECT * FROM user WHERE username = '".$_SESSION['username']."' ";
	$result = $conn->query($sql);
	$row = $result->fetch_assoc();
	$username = $row['username'];
	$email = $row['email'];
	$institution = $row['institution'];
	
	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		if(isset($_POST['newPassInput']) && $_POST['newPassInput']!=''){
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
		    $hash = hashPassword($_POST['newPassInput']);
		    $sql = "UPDATE user SET pass = '".$hash."' WHERE username = '".$_SESSION['username']."' ";
		    $conn->query($sql);
        }
        if($_POST['institution'] != $institution){
        	$sql = "UPDATE user SET institution = '".$_POST['institution']."' WHERE username = '".$_SESSION['username']."' ";
        	$conn->query($sql);
        }
        if(is_uploaded_file($_FILES["image"]["tmp_name"])){
			$image_dir = "images/".$_SESSION['username'].".jpg";
			unlink($image_dir);
			if(move_uploaded_file($_FILES["image"]["tmp_name"], $image_dir)){
				/*echo "success";*/
			}
		}
        include("Notification.php");
        SetNotification("Your profile has been updated.", "success");
        header("location: MyInformation.php");
        
    }
?>

<!DOCTYPE html>
<html>
<head>
    <title>Edit Profile</title>
    <script type="text/javascript" src="Notify/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="Notify/notify.js"></script>
	<script src="FormValidation/UpdateProfile.js"></script>

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
    </style>
</head>
<body>

	<!--For checking the file extension
	allowedExtensions can be an array: like ['jpeg', 'jpg', 'png']
	It will also clear the field (i.e: the id) if a wrong extension is encountered 
	maxSize is in MB -->
	<script type="text/javascript">
		function validate(filename, id, allowedExtensions, maxSize) {
		    var ext = filename.split(".");
		    var fileSize = Math.ceil( ($(id)[0].files[0].size)/(1024*1024) );
		    ext = ext[ext.length-1].toLowerCase();
		    if (allowedExtensions.lastIndexOf(ext) == -1) {
		    	var msg = 'Only .' + allowedExtensions[0];
		    	for (var i = 1; i < allowedExtensions.length; i++) {
		    		msg = msg + ', .' + allowedExtensions[i];
		    	}
		    	if(i == 1) msg = msg + ' is allowed';
		    	else msg = msg + ' are allowed'
		        $.notify(msg, 'error');
		        $(id).val("");
		    }
		    else if( fileSize > maxSize ){
		    	var msg = 'Your file is '+fileSize+' MB, File size must be less than '+maxSize+' MB';
		    	$.notify(msg, 'error');
		    	$(id).val("");
		    }
		}
	</script>

	<script type="text/javascript">
		$('document').ready(function(){
			$('#submit_btn').on('click', function(){
				var pass = $('#newPassInput').val();
				var matchPass = $('#matchPassInput').val();
				if (pass != matchPass) {
					$('#matchPass').removeClass();
					$('#matchPass').addClass("error");
					$('#matchPass').html("*Password doesnt match!");
					return false;
				}
				return true;
			});
		});
	</script>

	<center>
    <div class="div-main">
    	<h2 style="margin: 40px 0">Edit Profile</h2>
    	<div style="width: 40%; display: inline-block;">
	        <form method="post" enctype="multipart/form-data" action="EditProfile.php">
	        	<div class="category">UserName:</div>
				<input type="text" class="text" style="background-color: lavender" readonly value="<?php echo $username; ?>">

	        	<div class="category">E-mail:</div>
				<input type="email" class="text" style="background-color: lavender" readonly value="<?php echo $email; ?>">

				<div class="category">New Password:<span id="newPass"></span></div>
				<input type="Password" class="text" name="newPassInput" id="newPassInput" maxlength="50" title="Ignore if password is same" onmousedown="this.type='text'" onmouseup="this.type='password'" onmousemove="this.type='password'">

				<div class="category">Confirm New Password:<span id="matchPass"></span></div>
				<input type="Password" class="text" id="matchPassInput" maxlength="50" title="Ignore if password is same" onmousedown="this.type='text'" onmouseup="this.type='password'" onmousemove="this.type='password'">

				<div class="category">Institution:</div>
				<input type="text" class="text" name="institution" maxlength = "300" value="<?php echo $institution; ?>">

				<div class="category">Change Photo:</div>
				<input type="file" class="text" name="image" id="image" onChange="validate(this.value, image, ['jpg', 'jpeg', 'png'], 5)">

				<input type="submit" class="button" id="submit_btn" value="Update" name="submit">
			</form>

		</div>
    </div>
	</center>
</body>
</html>