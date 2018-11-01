$('document').ready(function(){
 	var username_state = false;
 	var email_state = false;
 	var pass_state = false;
 	var match_pass_state = false;

 	$('#username').on('keyup', function(){

 		/*For Loading*/
		$('#username_val').removeClass();
		$('#username_val').addClass("loader");
		$('#username_val').html('<i class="fa fa-circle-o-notch fa-spin"></i>');

		var username = $('#username').val();
		if (username == '') {
			username_state = false;
			$('#username_val').removeClass();
			$('#username_val').addClass("error");
			$('#username_val').html('*Required');
			return;
		}
		$.ajax({
			url: 'FormValidation/RegisterValidation.php',
			type: 'post',
			data: {
				'username' : username,
			},
			success: function(response){
				if (response != 'valid' ) {
					username_state = false;
					$('#username_val').removeClass();
					$('#username_val').addClass("error");
					$('#username_val').html(response);
				}else if (response == 'valid') {
					username_state = true;
					$('#username_val').removeClass();
					$('#username_val').addClass("success");
					$('#username_val').html('&#10004');
				}
			}
		});
	});

	$('#email').on('keyup', function(){
		
		/*For Loading*/
		$('#email_val').removeClass();
		$('#email_val').addClass("loader");
		$('#email_val').html('<i class="fa fa-circle-o-notch fa-spin"></i>');

		var email = $('#email').val();
		if (email == '') {
			email_state = false;
			$('#email_val').removeClass();
			$('#email_val').addClass("error");
			$('#email_val').html('*Required');
			return;
		}
		$.ajax({
			url: 'FormValidation/RegisterValidation.php',
			type: 'post',
			data: {
				'email' : email,
			},
			success: function(response){
				if (response != 'valid' ) {
					email_state = false;
					$('#email_val').removeClass();
					$('#email_val').addClass("error");
					$('#email_val').html(response);
				}else if (response == 'valid') {
					email_state = true;
					$('#email_val').removeClass();
					$('#email_val').addClass("success");
					$('#email_val').html('&#10004');
				}
			}
		});
	});

	$('#pass').on('keyup', function(){
		var pass = $('#pass').val();
		if (pass == '') {
			pass_state = false;
			$('#pass_val').removeClass();
			$('#pass_val').addClass("error");
			$('#pass_val').html('*Required');
		}
		else {
			pass_state = true;
			$('#pass_val').removeClass();
			$('#pass_val').addClass("success");
			$('#pass_val').html('&#10004');
		}
		if (match_pass_state == true) {
			if(pass != matchPass){
				match_pass_state = false;
				$('#matchPass_val').removeClass();
				$('#matchPass_val').addClass("error");
				$('#matchPass_val').html('*Password does not match!');
			}
		}
	});

	$('#matchPass').on('keyup', function(){
		var pass = $('#pass').val();
		var matchPass = $('#matchPass').val();
		if (matchPass == '') {
			match_pass_state = false;
			$('#matchPass_val').removeClass();
			$('#matchPass_val').addClass("error");
			$('#matchPass_val').html('*Required');
			return;
		}
		else {
			if(pass != matchPass){
				match_pass_state = false;
				$('#matchPass_val').removeClass();
				$('#matchPass_val').addClass("error");
				$('#matchPass_val').html('*Password does not match!');
			}
			else{
				match_pass_state = true;
				$('#matchPass_val').removeClass();
				$('#matchPass_val').addClass("success");
				$('#matchPass_val').html('&#10004');	
			}
		}
	});

	$('#reg_btn').on('click', function(){
		if (username_state == false || email_state == false || pass_state == false || match_pass_state == false) {
			$.notify('Please fill up the form correctly!', 'error');
			return false;
		}else{
			$('#username').prop('readonly', true);
			$('#email').prop('readonly', true);
			$('#pass').prop('readonly', true);
			$('#matchPass').prop('readonly', true);
			return true;
		}
	 });

});