[].map.call(document.querySelectorAll('.profile'), function(el) {
    el.classList.toggle('profile--open');
  });
$(document).ready(function () {
	$(".btn").on("click", function () {
		notif({
		type: "alert",
		msg: "Processing...",
		autohide: false,
		position: "bottom",
		opacity: 0.7,
		zindex: 0,
		bgcolor: "yellow",
		offset: 0,
		fade: 100,
	});
	
	
	var email = $("#fieldUser").val();
	var username = $("#fieldPassword").val();
	
	$.ajax({
		type: "POST",
		url: "php/forgot_process.php",
		data: {Email: email, Username: username},
		success: function(data) {
			if (data != "Email Send! Please Check!" && data != "Please Confirm Your Email" && data != "Username With Email is not found!" && data != "Email Not Found!" && data != "Invalid Email Address" && data != "Please Enter Email" && data != "Please Enter Username") {
				notif({
					type: "success",
					msg: data,
					autohide: false,
					position: "bottom",
					opacity: 0.7,
					zindex: 0,
					bgcolor: "#478B16",
					offset: 0,
					fade: 100,
				});
			} else {
				notif({
					type: "fail",
					msg: data,
					autohide: false,
					position: "bottom",
					color: "white",
					opacity: 0.7,
					zindex: 0,
					bgcolor: "red",
					offset: 0,
					fade: 100,
				});
			}
		}
	});
	
	});
});