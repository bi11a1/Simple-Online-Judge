function toggle_eye(pass, eye){
	var eyeId = document.getElementById(eye);
	var passId = document.getElementById(pass);
	$(eyeId).toggleClass("fa-eye-slash fa-eye");
	if ($(passId).attr("type") == "password") {
		$(passId).attr("type", "text");
	} else {
		$(passId).attr("type", "password");
	}
}