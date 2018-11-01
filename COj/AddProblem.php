<?php
	include('header.php');
	include('options.php');
	include('connection.php');
	include('notification.php');

	if ($_SERVER["REQUEST_METHOD"] == "POST") {
		$author = $_POST['author'];
		$problem_name = $_POST['problem_name'];
		$category = $_POST['category'];
		$difficulty = $_POST['difficulty'];

	    $sql = "INSERT INTO problems (name, hide, difficulty, category, author) VALUES ('".$problem_name."', 1, '".$difficulty."', '".$category."', '".$author."')";
	    if($conn->query($sql)){
			$problem_id = $conn->insert_id;

			$description_dir = 'Problems/'.$problem_id.'.pdf';
			$input_dir = 'Problems/'.$problem_id.'in.txt';
			$output_dir = 'Problems/'.$problem_id.'out.txt';

			if(move_uploaded_file($_FILES["description"]["tmp_name"], $description_dir)
				and move_uploaded_file($_FILES["input_file"]["tmp_name"], $input_dir)
					and move_uploaded_file($_FILES["output_file"]["tmp_name"], $output_dir)){
				$msg = 'New problem has been added! Problem Id: '.$problem_id;
				SetNotification($msg, 'success');
				header('Location: home.php');
			}
			else{
    			SetNotification('Something went wrong! :( ', 'error');
    			header("location: home.php");	
			}
	    }
    	else{
    		SetNotification('Something went wrong! :( ', 'error');
    		header("location: home.php");
    	}
	}
?>
<!DOCTYPE html>
<html>
<head>
	<title>Add Problem</title>
	<link rel="stylesheet" type="text/css" href="CSS/ShowInformation.css">
	<link rel="stylesheet" type="text/css" href="CSS/TableStyle.css">
	<script type="text/javascript" src="Notify/jquery-3.1.1.js"></script>
	<script type="text/javascript" src="Notify/notify.js"></script>
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

<center>
<div class="div-main">
	<form action="AddProblem.php" method="post" enctype="multipart/form-data">
	<h1>Add a new Problem:</h1>
	<table>
		<tr>
			<td class="title">Author : </td>
			<td class="title"><input type="text" class="text" style="background-color: lavender" name="author" readonly required value="<?php echo $_SESSION['username']; ?>"></td>
		</tr>
		<tr>
			<td class="title">Problem Name : </td>
			<td class="title"><input type="text" class="text" name="problem_name" maxlength="100" required></td>
		</tr>
		<tr>
			<td class="title">Category : </td>
			<td class="title"><input type="text" class="text" name="category" maxlength="100" required></td>
		</tr>
		<tr>
			<td class="title">Difficulty : </td>
			<td class="title">
				<select name="difficulty" class="select" style="width: 60%">
					<option value="Very Easy">Very Easy</option>
					<option value="Easy">Easy</option>
					<option value="Normal">Normal</option>
					<option value="Hard">Hard</option>
					<option value="Very Hard">Very Hard</option>
				</select>
			</td>
		</tr>
		<tr>
			<td class="title">Description : </td>
			<td class="title"><input type="file" class="file" name="description" id="description" required onChange="validate(this.value, description, ['pdf'], 10)">.pdf format</td>
		</tr>
		<tr>
			<td class="title">Input file : </td>
			<td class="title"><input type="file" class="file" name="input_file" id="input_file" required onChange="validate(this.value, input_file, ['txt'], 10)">.txt format</td>
		</tr>
		<tr>
			<td class="title">Output file : </td>
			<td class="title"><input type="file" class="file" name="output_file" id="output_file" required onChange="validate(this.value, output_file, ['txt'], 10)">.txt format</td>
		</tr>
	</table>
	<input type="submit" class="submit" value="Add" name="submit">
	</form>
</div>
</center>
</body>
</html>
