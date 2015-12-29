[].map.call(document.querySelectorAll('.profile'), function(el) {
    el.classList.toggle('profile--open');
  });
$(".btn").on("click", function () {
	notif({
		type: "alert",
		msg: "Processing...",
		bgcolor: "rgba(255, 235, 59, 0.81)",
		autohide: false,
		opacity: 0.7,
		position: "bottom"
	});
	var code = $("#fieldUser").val();
	var password = $("#fieldPassword").val();
	var cpassword = $("#fieldcPassword").val();
	$.ajax({
		type: "POST",
		url: "php/reset_process.php",
		data: {Password: password, Cpassword: cpassword, Code: code},
		success: function(data) {
			if(data == "Thanks password has been reset please login now") {
				notif({
					type: "success",
					msg: data,
					bgcolor: "#478B16",
					autohide: false,
					opacity: 0.7,
					position: "bottom"
				});
				$(".forgot").fadeIn(850);
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