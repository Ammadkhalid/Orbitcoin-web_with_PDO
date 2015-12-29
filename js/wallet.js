$(document).ready(function () {
	$.ajax({
		type: "POST",
		url: "php/functions.php",
		data: {Username: "Display Username!"},
		success: function(data) {
			
			notif({
		type: "info",
		msg: "Welcome "+data,
		autohide: true,
		position: "bottom",
		opacity: 0.7,
		timeout: 5000,
		zindex: 0,
		offset: 0,
		fade: 100,
		bgcolor: "black"
			});
			
		}
	});
	
	$(".send_form #simple_address").on("keyup", function () {
		var orb_address = $(this).val();
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {Vali_orbitcoin_address: orb_address},
			success: function(data) {
				if(data == "Ok!") {
					$(".send_form input:first-child").css("color", "rgba(71, 139, 22, 0.76)");
					$(".send_form input:first-child:focus").css("border-right", "3px solid rgba(71, 139, 22, 0.76)");
				} else {
					$(".send_form input:first-child").css("color", "#F44336");
					$(".send_form input:first-child:focus").css("border-right", "3px solid #F44336");
				}
			}
		})
	});
	
	$(".send_form input[type=password]").on("keyup", function () {
		var orb_password = $(this).val();
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {orbitcoin_send_payment_password: orb_password},
			success: function(data) {
				if(data == "Ok!") {
					$(".send_form input[type=password]").css("color", "rgba(71, 139, 22, 0.76)");
					$(".send_form input[type=password]:focus").css("border-right", "3px solid rgba(71, 139, 22, 0.76)");
				} else {
					$(".send_form input[type=password]").css("color", "#F44336");
					$(".send_form input[type=password]:focus").css("border-right", "3px solid #F44336");
				}
			}
		})
	});
	
	$(".send_form #simple_amount").on("keyup", function () {
		var amount_chk = $(this).val();
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {Check_amount: amount_chk},
			success: function(data) {
				if(data == "Ok!") {
					$(".send_form #simple_amount").css("color", "rgba(71, 139, 22, 0.76)");
					$(".send_form #simple_amount:focus").css("border-right", "3px solid rgba(71, 139, 22, 0.76)");
				} else {
					$(".send_form #simple_amount").css("color", "#F44336");
					$(".send_form #simple_amount:focus").css("border-right", "3px solid #F44336");
				}
			}
		})
	});
	
	$(".send_form #coustom_amount").on("keyup", function () {
		var amount_chk = $(this).val();
		var input = $("#send_content #coustom_group input:first-child").map(function() {
		return $(this).val();
		}).get();
		var fee = $("#send_content #coustom_send #fee").val();
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {Check_amount: amount_chk},
			success: function(data) {
				if(data == "Ok!") {
					$(".send_form #coustom_amount").css("color", "rgba(71, 139, 22, 0.76)");
					$(".send_form #coustom_amount:focus").css("border-right", "3px solid rgba(71, 139, 22, 0.76)");
				} else {
					$(".send_form #coustom_amount").css("color", "#F44336");
					$(".send_form #coustom_amount:focus").css("border-right", "3px solid #F44336");
				}
			}
		});
		
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {for_send: amount_chk, user_input_adr: input, fee_show: fee},
			success: function (data) {
				$("#send_content #alert").show(1250);
				$("#send_content #alert").html(data);
			}
		});
		
	});
	
	$(".send_form #button_simple").on("click", function () {
		
		notif({
		type: "alert",
		msg: "Sending...",
		autohide: false,
		position: "bottom",
		opacity: 0.7,
		zindex: 0,
		bgcolor: "yellow",
		offset: 0,
		fade: 100,
		color: "black",
			});
		
		var orbitcoin_address = $(".send_form input:first-child").val();
		var amount = $(".send_form #simple_amount").val();
		var password = $(".send_form input[type=password]").val();
		
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {orb_address: orbitcoin_address, payment: amount, account_pass: password},
			success: function(data) {
				if(data != "Please Enter Orbitcoin To send payment!" && data != "Please Enter Amount!" && data != "Please Enter Account Password!" && data != "ERROR! Reload page" && data != "Invalid Orbitcoin Address" && data != "Invalid Orbitcoin Address" && data != "Invalid Amount" && data != "No fee to spend" && data != "Incorrect Password!") {
					$("#send_content #simple_send .send_form #error").html("<p>"+data+"</p>");
			notif({
			type: "success",
			msg: "Send!",
			autohide: false,
			position: "bottom",
			opacity: 0.7,
			zindex: 0,
			bgcolor: "#478B16",
			offset: 0,
			fade: 100,
			});
			
					var audio = document.createElement("audio");
					audio.setAttribute("src", "mp3/send.mp3");
					audio.setAttribute("autoplay", "autoplay");
					audio.play();
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
		})
	});
	
	$("#add_more").on("click", function () {
		$('<div id="coustom_group"> <input type="text" placeholder="Orbitcoin Address"><br /> <input type="text" placeHolder="Amount" id="coustom_amount"><br /> </div>').prependTo('#coustom_send .send_form');
		var count = $("#send_content #coustom_group").size();
		if(count > 1) {
			$("#send_content #remove_more").fadeIn(450);
		}
	});
	
	$("#remove_more").on("click", function () {
		var count = $("#send_content #coustom_group").size();
		if(count > 1) {
			$("#send_content #coustom_group:first-child").remove();
			if(count < 1) {
				alert(0)
			}
		} else {
			$(this).fadeOut(450);
		}
	});
	
	$("#send_content #button_coustom").on("click", function () {
		var orbitcoin_address = $("#send_content #coustom_group input:first-child").map(function() {
		return $(this).val();
		}).get();
		var coustom_amount = $("#send_content #coustom_group #coustom_amount").map(function () {
			return $(this).val();
		}).get();
		var tx_comment = $("#send_content #coustom_send #comment_tx").val();
		var password_many = $("#send_content #coustom_send input[type='Password']").val();
		var fees = $("#send_content #coustom_send #fee").val();
		
		notif({
		type: "alert",
		msg: "Sending...",
		autohide: false,
		position: "bottom",
		opacity: 0.7,
		zindex: 0,
		bgcolor: "yellow",
		offset: 0,
		fade: 100,
		color: "black",
			});
		
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {Orb_addresses: orbitcoin_address, coustom_amount: coustom_amount, tx_comments: tx_comment, coustom_pass: password_many, fee: fees},
			success: function(data) {
				if(data != "Insufficient Balance!" && data != "Please Enter Orbitcoin Address to send payment!" && data != "Please Enter Amount!" && data != "Please Enter Orbitcoin To send payment!" && data != "Please Enter Password!" && data != "ERROR! Reload page" && data != "Invalid Miner Fee!" && data != "Mini Miner Fee is 0.001 ORB" && data != "Invalid Amount's" && data != "Please Enter Amount's" && data != "Invalid Orbitcoin address!" && data != "Please Enter Orbitcoin To send payment!") {
					notif({
					type: "success",
					msg: "Send!",
					autohide: false,
					position: "bottom",
					opacity: 0.7,
					zindex: 0,
					bgcolor: "#478B16",
					offset: 0,
					fade: 100,
					});
					
					var audio = document.createElement("audio");
					audio.setAttribute("src", "mp3/send.mp3");
					audio.setAttribute("autoplay", "autoplay");
					audio.play();
					$("#send_content #alert").html(data);
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
		})
		
	});
	
	$("#send_content #coustom_send .send_form #add_more").tooltipster({
		theme: "tooltipster-shadow"
	});
	
	$("#send_content #coustom_send .send_form #remove_more").tooltipster({
		theme: "tooltipster-shadow"
	});
	
	$("#c_adr").on("click", function () {
		
		notif({
		type: "alert",
		msg: "Creating New Address....",
		autohide: false,
		position: "bottom",
		opacity: 0.7,
		zindex: 0,
		bgcolor: "yellow",
		offset: 0,
		fade: 100,
		color: "black",
			});
		
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {generate_new: "Please Create New Address"},
			success: function (data) {
				if(data != "ERROR! Reload page") {
					notif({
					type: "success",
					msg: "Successfully Created!",
					autohide: false,
					position: "bottom",
					opacity: 0.7,
					zindex: 0,
					bgcolor: "#478B16",
					offset: 0,
					fade: 100,
					});
					
					$(data).appendTo("#receive_content table tbody");
					
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
		})
	});
	
	$("#account_content #simple_send #change_simple").on("click", function () {
		var pass  = $("#account_content #password").val();
		var email  = $("#account_content #email").val();
		var passr = $("#account_content #apass").val();
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {Email: email, Password: passr, New_pass: pass},
			success: function (data) {
				if(data != "Email And Password Change Successfully!" && data != "Password Successfully Changed!" && data !="Email has been Change Successfully!") {
					
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
					
				} else {
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
					
				}
			}
		})
	});
	
	$("#account_content #import_send #dump_adr").on("click", function () {
		var option = $("#account_content #import_send #adr_option option:selected").val();
		var pass = $("#account_content #import_send #password").val();
		notif({
		type: "alert",
		msg: "Dumping Address....",
		autohide: false,
		position: "bottom",
		opacity: 0.7,
		zindex: 0,
		bgcolor: "yellow",
		offset: 0,
		fade: 100,
		color: "black",
			});
		$.ajax({
			type: "POST",
			url: "php/functions.php",
			data: {Dump_address: option, Dump_password: pass},
			success: function (data) {
				if(data != "ERROR! Reload page" && data != "Please Select Orbitcoin For Dumpinging Address!" && data != "Please Enter Password To dump address!" && data != "Incorrect Account Password!" && data != "ERROR! Invalid Address!" && data != "") {
					notif({
					type: "success",
					msg: "Successfully Dumped!",
					position: "bottom",
					opacity: 0.7,
					zindex: 0,
					bgcolor: "#478B16",
					offset: 0,
					fade: 100,
					});
					$("#account_content #import_send #dump_keys").val(data);
					
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
		})
	});
	
	$("#tx_content span").on("click", function () {
		if($("#tx_content #s_tx").css("display") == "none") {
		$("#tx_content #s_tx").show();
		$("#tx_content #r_txs").hide();
		} else {
			$("#tx_content #r_txs").show();
			$("#tx_content #s_tx").hide();
		}
	});
	
	$(".topbar #refresher").on("click", function () {
		$(this).addClass("fa-spin-hover");
		$(".topbar #con").addClass("con");
		
		$.ajax({
			url: "php/functions.php",
			type: "POST",
			data: {Balance: "balance"},
			success: function(data) {
				if(data != "ERROR! Reload page") {
					$("#home_content #balance").html("Balance: "+data+" ORB");
					$(".topbar #top_balance p").html(data + " ORB");
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
				
				$(".topbar #refresher").removeClass("fa-spin-hover");
					$(".topbar #con").removeClass("con");
			}
		});
		
		$.ajax({
			url: "php/functions.php",
			type: "POST",
			data: {total_receive: "GET"},
			success: function(data) {
				if(data != "ERROR! Reload page") {
					$("#home_content #total_b").html("Total Receive: "+data+" ORB");
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
					
					$(".topbar #refresher").removeClass("fa-spin-hover");
					$(".topbar #con").removeClass("con");
				}
			}
		});
		
		$.ajax({
			url: "php/functions.php",
			data: {receive_txs: "Alert_receive_txs"},
			type: "POST",
			success: function(data) {
				if(data != "ERROR! Reload page") {
					$("#tx_content #receive_txs").html(data);
					
					$(".topbar #refresher").removeClass("fa-spin-hover");
					$(".topbar #con").removeClass("con");
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
				
				$(".topbar #refresher").removeClass("fa-spin-hover");
					$(".topbar #con").removeClass("con");
				}
		});
		
		$.ajax({
			url: "php/functions.php",
			type: "POST",
			data: {Send_txs: "Send_txs"},
			success: function(data) {
				$("#tx_content #send_txs").html(data);
			}
		});
		
	});
	
	$("#tx").on("click", function () {
		$("#home").removeClass("home_active");
		$("#send").removeClass("send_active");
		$("#rx").removeClass("rx_active");
		$("#account").removeClass("account_active");
		$(this).addClass("tx_active");
		$("#home_content").css("display", "none");
		$("#send_content").css("display", "none");
		$("#receive_content").css("display", "none");
		$("#account_content").css("display", "none");
		$("#tx_content").css("display", "block");
	});
	
	$("#home").on("click", function () {
		$("#tx").removeClass("tx_active");
		$("#send").removeClass("send_active");
		$("#rx").removeClass("rx_active");
		$("#account").removeClass("account_active");
		$(this).addClass("home_active");
		$("#tx_content").css("display", "none");
		$("#receive_content").css("display", "none");
		$("#account_content").css("display", "none");
		$("#send_content").css("display", "none");
		$("#home_content").css("display", "block");
	});
	
	$("#send").on("click", function () {
		$("#home").removeClass("home_active");
		$("#tx").removeClass("tx_active");
		$("#rx").removeClass("rx_active");
		$("#account").removeClass("account_active");
		$(this).addClass("send_active");
		$("#tx_content").css("display", "none");
		$("#receive_content").css("display", "none");
		$("#account_content").css("display", "none");
		$("#home_content").css("display", "none");
		$("#send_content").css("display", "block");
	});
	
	$("#rx").on("click", function () {
		$("#home").removeClass("home_active");
		$("#tx").removeClass("tx_active");
		$("#send").removeClass("send_active");
		$("#account").removeClass("account_active");
		$(this).addClass("rx_active");
		$("#tx_content").css("display", "none");
		$("#home_content").css("display", "none");
		$("#account_content").css("display", "none");
		$("#send_content").css("display", "none");
		$("#receive_content").css("display", "block");
	});
	
	$("#account").on("click", function () {
		$("#home").removeClass("home_active");
		$("#tx").removeClass("tx_active");
		$("#send").removeClass("send_active");
		$("#rx").removeClass("rx_active");
		$(this).addClass("account_active");
		$("#tx_content").css("display", "none");
		$("#home_content").css("display", "none");
		$("#receive_content").css("display", "none");
		$("#send_content").css("display", "none");
		$("#account_content").css("display", "block");
	});
	
	$("#send_content #send_menu ul li:first-child").on("click", function () {
		var id = $("#send_content #send_menu ul li:nth-child(2)").attr("id");
		$("#send_content #coustom_send").fadeOut(400, function() {
			$("#send_content #simple_send").fadeIn(400);
		});
		if(id == "active_menu") {
			$("#send_content #send_menu ul li:first-child").attr("id", "active_menu");
			$("#send_content #send_menu ul li:nth-child(2)").removeAttr("id");
		}
	});
	
	$("#send_content #send_menu ul li:nth-child(2)").on("click", function () {
		var id = $("#send_content #send_menu ul li:first-child").attr("id");
		$("#send_content #simple_send").fadeOut(400, function() {
			$("#send_content #coustom_send").fadeIn(400);
		});
		if(id == "active_menu") {
			$("#send_content #send_menu ul li:nth-child(2)").attr("id", "active_menu");
			$("#send_content #send_menu ul li:first-child").removeAttr("id");
		}
	});
	
	$("#account_content #account_menu ul li:nth-child(2)").on("click", function () {
		var id = $("#account_content #account_menu ul li:first-child").attr("id");
		$("#account_content #simple_send").fadeOut(400, function() {
			$("#account_content #import_send").fadeIn(400);
		});
		if(id == "active_menu") {
			$("#account_content #account_menu ul li:nth-child(2)").attr("id", "active_menu");
			$("#account_content #account_menu ul li:first-child").removeAttr("id");
		}
	});
	
	$("#account_content #account_menu ul li:first-child").on("click", function () {
		var id = $("#account_content #account_menu ul li:nth-child(2)").attr("id");
		$("#account_content #import_send").fadeOut(400, function() {
			$("#account_content #simple_send").fadeIn(400);
		});
		if(id == "active_menu") {
			$("#account_content #account_menu ul li:first-child").attr("id", "active_menu");
			$("#account_content #account_menu ul li:nth-child(2)").removeAttr("id");
		}
	})
	
});