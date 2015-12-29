  [].map.call(document.querySelectorAll('.profile'), function(el) {
    el.classList.toggle('profile--open');
  });
$(document).ready(function () {
	$(".btn").on("click", function () {
		notif({
		type: "alert",
		msg: "Processing...",
		bgcolor: "rgba(255, 235, 59, 0.81)",
		autohide: false,
		opacity: 0.7,
		position: "bottom"
		});
		var username = $("#fieldUser").val();
		var password = $("#fieldPassword").val();
		var cpassword = $("#fieldCPassword").val();
		var email = $("#fieldEmail").val();
		$.ajax({
			type: "POST",
			url: "php/Creating_wallet.php",
			data: {Username: username, Password: password, Cpassword: cpassword, Email: email},
			success: function(data) {
				if(data != "Email Already taken!" && data != "Invalid Email Address!" && data != "Password is not match!" && data != "Enter Minimum 6 Characters In Password!" && data != "Username already taken!" && data != "Username Must be in alphabet and numbers") {
					notif({
				type: "success",
				msg: data,
				bgcolor: "#478B16",
				autohide: false,
				opacity: 0.7,
				position: "bottom"
					});
				} else {
					notif({
				type: "success",
				msg: data,
				bgcolor: "red",
				opacity: 0.7,
				position: "bottom"
					});
				}
			}
		})
	});
	
});