$(document).ready(function() {
	$(".login-button").on("click", function() {
		var username = $(".login-input.username").val();
		var password = $(".login-input.password").val();
		if($(".login-remember-button").hasClass("active")) {
			var remember = "true";
		}
		else {
			var remember = "false";
		}
		$.ajax({
			type: "POST",
			data: { action: "login", username: username, password: password, remember: remember },
			url: "./scripts/process.php", 
			success: function(data) {
				if(data == "done") {
					location.reload();
				}
				else {
					notify("Error...", data, "red", 4000);
				}
			}
		});
	});
	$(".login-remember-button").on("click", function() {
		if($(this).hasClass("active")) {
			$(this).removeClass("active");
		}
		else {
			$(this).addClass("active");
		}
	});
	function notify(title, description, color, duration) {
		var build = $('<div class="notification-wrapper noselect"><div class="notification-title-wrapper"><span class="notification-title">' + title + '</span></div><div class="notification-description-wrapper"><span class="notification-description">' + description + '</span></div></div>');
		$(".notification-area").show().append(build);
		$(build).show().css("right", "-600px").animate({right: 0}, 400);
		if(color == "red") {
			$(build).css("background", "rgb(230,60,60)");
		}
		else if(color == "blue") {
			$(build).css("background", "rgb(0,150,255)");
		}
		else if(color == "green") {
			$(build).css("background", "rgb(15,200,0)");
		}
		setTimeout(function() {
			$(build).animate({right: -600}, 400);
			setTimeout(function() {
				$(build).remove();
				if($(".notification-area").html().length == 0) {
					$(".notification-area").hide();
				}
			}, 400);
		}, duration);
	}
});