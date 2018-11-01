<?php
function SendEmail($to, $hash){

$subject = 'COj Email Verification';
$link = "http://localhost:7777/coj/ConfirmEmail.php?email=$to&hash=$hash";

$message = 'Hello from COj,

		Please Click on the link below to verify your email address for successfully registering into COj:
		'
.$link.
' 

Thank you :)';

$headers = "From: cuetonlinejudge@gmail.com\r\n".
			'X-Mailer: PHP/'.phpversion();
$success = mail($to, $subject, $message, $headers);

if ($success) {
   //echo "Success";
   return 1;
} else {
   //echo "Error";
   return 0;
}

}
?>