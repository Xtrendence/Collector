$(document).ready(function() {
	if($("body").attr("id") == "mobile") {
		var mobile = true;
		var desktop = false;
	}
	else {
		var desktop = true;
		var mobile = false;
	}
	get_items(0);
	var page = $("body").data("page");
	if(page == "index") {
		$(".menu-my-collection").addClass("active");
	}
	else if(page == "catalogue") {
		$(".menu-catalogue").addClass("active");
	}
	$(".main-navbar-menu-button").on("click", function() {
		if($(this).hasClass("active")) {
			close_menu();
		}
		else {
			open_menu();
		}
	});
	$(".search-bar").on("keyup", function() {
		var search = $(this).val().toLowerCase();
		if(search.trim().length != 0) {
			$(".collection-item-pane, .catalogue-item-pane").filter(function() {
				$(this).toggle($(this).text().toLowerCase().indexOf(search) > -1);
			});
		}
		else {
			$(".collection-item-pane, .catalogue-item-pane").show();
		}
	});
	$(".menu-item.help").on("click", function() {
		$(".help-page-overlay").show();
		close_menu();
	});
	$(".help-page-top .close-icon").on("click", function() {
		$(".help-page-overlay").hide();
	});
	$(".add-item-icon-wrapper, .add-item-title").on("click", function() {
		var intent = "add-item";
		var action = "add-item";
		open_user_prompt(intent, action);
	});
	$(".collection-items-dynamic").delegate(".collection-item-image-wrapper", "click", function() {
		var intent = "edit-item";
		var action = "edit-item";
		var data = {title: $(this).data("title"), image: $(this).find(".catalogue-item-image").attr("src"), id: $(this).attr("id")};
		open_user_prompt(intent, action, data);
	});
	$(".collection-items-dynamic").delegate(".collection-item-title", "click", function() {
		$(".collection-item-image-wrapper").trigger("click");
	});
	$(".catalogue-item-image-wrapper").on("click", function() {
		var intent = "catalogue-item";
		var action = "catalogue-item";
		var data = {title: $(this).data("title"), image: $(this).find(".catalogue-item-image").attr("src")};
		open_user_prompt(intent, action, data);
	});
	$(".catalogue-item-title").on("click", function() {
		$(".catalogue-item-image-wrapper").trigger("click");
	});
	
	$(".user-prompt-add .user-prompt-action.upload-image").on("click", function() {
		$(".user-prompt-add .user-prompt-input.image").trigger("click");
	});
	
	$(".user-prompt-edit-image .user-prompt-action.upload-image").on("click", function() {
		$(".user-prompt-edit-image .user-prompt-input.image").trigger("click");
	});
	
	$(".user-prompt-top .close-icon").on("click", function() {
		close_user_prompt();
	});
	$(".user-prompt-add .user-prompt-action.submit").on("click", function() {
		var action = $(".user-prompt-wrapper").data("action");
		if(action == "add-item") {
			var image = $(".user-prompt-placeholder-image").attr("src");
			var title = $(".user-prompt-add .user-prompt-input.title").val();
			$.ajax({
				type: "POST",
				data: { action: "add-item", title: title, image: image, catalogue: "false" },
				url: "./scripts/process.php", 
				success: function(data) {
					if(data == "done") {
						close_user_prompt();
						notify("Added...", "Your item has been added to your collection.", "blue", 4000);
						get_items(150);
					}
					else {
						notify("Error...", "Something went wrong.", "red", 4000);
					}
				}
			});
		}
	});
	$(".user-prompt-catalogue .user-prompt-action.add").on("click", function() {
		var image = $(".user-prompt-catalogue-image").attr("src");
		var title = $(".user-prompt-catalogue .user-prompt-input.title").val();
		$.ajax({
			type: "POST",
			data: { action: "add-item", title: title, image: image, catalogue: "true" },
			url: "./scripts/process.php", 
			success: function(data) {
				if(data == "done") {
					close_user_prompt();
					notify("Added...", "The item has been added to your collection.", "blue", 4000);
				}
				else {
					notify("Error...", "Something went wrong.", "red", 4000);
				}
			}
		});
	});
	$(".user-prompt-edit .user-prompt-action.delete").on("click", function() {
		$(".user-prompt-edit").hide();
		$(".user-prompt-edit-delete").show();
	});
	$(".user-prompt-edit .user-prompt-action.rename").on("click", function() {
		$(".user-prompt-edit").hide();
		$(".user-prompt-edit-rename").show();
		$(".user-prompt-edit-rename .user-prompt-input.rename").val($(".user-prompt-wrapper").attr("data-title"));
	});
	$(".user-prompt-edit .user-prompt-action.change-image").on("click", function() {
		$(".user-prompt-edit").hide();
		$(".user-prompt-edit-image").show();
		$(".user-prompt-change-image").attr("src", "").hide();
		$(".user-prompt-change-image-overlay").show();
		reset_input(".user-prompt-input.image");
	});
	$(".user-prompt-edit-delete .user-prompt-action.cancel").on("click", function() {
		$(".user-prompt-edit").show();
		$(".user-prompt-edit-delete").hide();
	});
	$(".user-prompt-edit-rename .user-prompt-action.cancel").on("click", function() {
		$(".user-prompt-edit").show();
		$(".user-prompt-edit-rename").hide();
	});
	$(".user-prompt-edit-image .user-prompt-action.cancel").on("click", function() {
		$(".user-prompt-edit").show();
		$(".user-prompt-edit-image").hide();
	});
	$(".user-prompt-edit-delete .user-prompt-action.confirm").on("click", function() {
		var id = $(".user-prompt-wrapper").attr("id");
		$.ajax({
			type: "POST",
			data: { action: "delete-item", id: id },
			url: "./scripts/process.php", 
			success: function(data) {
				if(data == "done") {
					close_user_prompt();
					notify("Deleted...", "The item has been deleted from your collection.", "blue", 4000);
					get_items(150);
				}
				else {
					notify("Error...", "Something went wrong.", "red", 4000);
				}
			}
		});
	});
	$(".user-prompt-edit-rename .user-prompt-action.confirm").on("click", function() {
		var id = $(".user-prompt-wrapper").attr("id");
		var title = $(".user-prompt-edit-rename .user-prompt-input.rename").val();
		$.ajax({
			type: "POST",
			data: { action: "rename-item", id: id, title: title },
			url: "./scripts/process.php",
			success: function(data) {
				if(data == "done") {
					close_user_prompt();
					notify("Renamed...", "The item has been renamed.", "blue", 4000);
					get_items(150);
				}
				else {
					notify("Error...", "Something went wrong.", "red", 4000);
				}
			}
		});
	});
	$(".user-prompt-edit-image .user-prompt-action.confirm").on("click", function() {
		var image = $(".user-prompt-change-image").attr("src");
		var id = $(".user-prompt-wrapper").attr("id");
		$.ajax({
			type: "POST",
			data: { action: "change-image", image: image, id: id },
			url: "./scripts/process.php", 
			success: function(data) {
				if(data == "done") {
					close_user_prompt();
					notify("Image Changed...", "The image of your item has been changed.", "blue", 4000);
					get_items(150);
				}
				else {
					notify("Error...", "Something went wrong.", "red", 4000);
				}
			}
		});
	});
	$(".user-prompt-add .user-prompt-input.image").change(function() {
		image_preview(this, ".user-prompt-placeholder-image");
		$(".user-prompt-placeholder-image-overlay").hide();
	});
	$(".user-prompt-edit-image .user-prompt-input.image").change(function() {
		image_preview(this, ".user-prompt-change-image");
		$(".user-prompt-change-image-overlay").hide();
	});
	$(".menu-item.settings").on("click", function() {
		open_settings();
		close_menu();
	});
	$(".settings-page-top .close-icon").on("click", function() {
		close_settings();
	});
	$(".settings-page-button.menu-button").on("click", function() {
		$(".settings-pane").hide();
		$(".settings-page-button.menu-button").removeClass("active");
		if($(this).hasClass("appearance")) {
			$(this).addClass("active");
			$(".settings-pane.appearance-pane").show();
		}
		if($(this).hasClass("options")) {
			$(this).addClass("active");
			$(".settings-pane.options-pane").show();
		}
		if($(this).hasClass("account")) {
			$(this).addClass("active");
			$(".settings-pane.account-pane").show();
		}
		if($(this).hasClass("backup")) {
			$(this).addClass("active");
			$(".settings-pane.backup-pane").show();
		}
	});
	$(".settings-page-button.action-button.action-view").on("click", function() {
		location.href = "./view.php";
	});
	$(".settings-page-button.action-button.action-backup").on("click", function() {
		location.href = "./backup.php";
	});
	$(".settings-page-button.action-button.action-reset").on("click", function() {
		$.ajax({
			type: "POST",
			data: { action: "reset-settings" },
			url: "./scripts/process.php", 
			success: function(data) {
				if(data == "done") {
					location.reload();
				}
				else {
					notify("Error...", "Something went wrong.", "red", 4000);
				}
			}
		});
	});
	$(".settings-page-button.action-button.action-logout").on("click", function() {
		logout();
	});
	$(".settings-page-button.action-button.action-username").on("click", function() {
		var password = $(".settings-page-input.password").val();
		var username = $(".settings-page-input.username").val();
		$.ajax({
			type: "POST",
			data: { action: "change-username", password: password, username: username },
			url: "./scripts/process.php",
			success: function(data) {
				if(data == "done") {
					logout();
				}
				else {
					notify("Error...", data, "red", 4000);
				}
			}
		});
	});
	$(".settings-page-button.action-button.action-password").on("click", function() {
		var current_password = $(".settings-page-input.current-password").val();
		var new_password = $(".settings-page-input.new-password").val();
		var repeat_password = $(".settings-page-input.repeat-password").val();
		if(new_password == repeat_password) {
			$.ajax({
				type: "POST",
				data: { action: "change-password", current_password: current_password, new_password: repeat_password },
				url: "./scripts/process.php",
				success: function(data) {
					if(data == "done") {
						logout();
					}
					else {
						notify("Error...", data, "red", 4000);
					}
				}
			});
		}
		else {
			notify("Error...", "Passwords don't match.", "red", 4000);
		}
	});
	$(".settings-page-button.choice-button").on("click", function() {
		if(!$(this).hasClass("active")) {
			$(this).parent().find(".settings-page-button.choice-button").removeClass("active");
			$(this).addClass("active");
			var config = JSON.parse($(".settings-page-wrapper").attr("data-config"));
			if($(this).hasClass("action-light-theme")) {
				config["theme"] = "light";
			}
			else if($(this).hasClass("action-dark-theme")) {
				config["theme"] = "dark";
			}
			else if($(this).hasClass("action-accent-green")) {
				config["accent-color"] = "green";
			}
			else if($(this).hasClass("action-accent-blue")) {
				config["accent-color"] = "blue";
			}
			else if($(this).hasClass("action-accent-purple")) {
				config["accent-color"] = "purple";
			}
			else if($(this).hasClass("action-accent-red")) {
				config["accent-color"] = "red";
			}
			else if($(this).hasClass("action-accent-pink")) {
				config["accent-color"] = "pink";
			}
			else if($(this).hasClass("action-accent-orange")) {
				config["accent-color"] = "orange";
			}
			else if($(this).hasClass("action-sharing-enable")) {
				config["page-sharing"] = "enabled";
			}
			else if($(this).hasClass("action-sharing-disable")) {
				config["page-sharing"] = "disabled";
			}
			var new_config = JSON.stringify(config);
			$(".settings-page-saving-overlay").show();
			$.ajax({
				type: "POST",
				data: { action: "change-settings", config: new_config },
				url: "./scripts/process.php", 
				success: function(data) {
					var data = JSON.parse(data);
					if(data["theme"] == "light") {
						set_theme("light");
						success = true;
					}
					else if(data["theme"] == "dark") {
						set_theme("dark");
						success = true;
					}
					if(data["accent-color"] == "green") {
						accent_color("green");
						success = true;
					}
					else if(data["accent-color"] == "blue") {
						accent_color("blue");
						success = true;
					}
					else if(data["accent-color"] == "purple") {
						accent_color("purple");
						success = true;
					}
					else if(data["accent-color"] == "red") {
						accent_color("red");
						success = true;
					}
					else if(data["accent-color"] == "pink") {
						accent_color("pink");
						success = true;
					}
					else if(data["accent-color"] == "orange") {
						accent_color("orange");
						success = true;
					}
					if(data["page-sharing"] != config["page-sharing"]) {
						location.reload();
						success = true;
					}
					if(success) {
						$(".settings-page-wrapper").attr("data-config", new_config);
					}
					else {
						notify("Error...", "Something went wrong.", "red", 4000);
					}
					setTimeout(function() {
						$(".settings-page-saving-overlay").hide();
					}, 1000);
				}
			});
		}
	});
	function close_settings() {
		reset_settings();
		$(".settings-page-overlay").hide();
	}
	function open_settings() {
		reset_settings();
		$(".settings-page-overlay").show();
		$(".settings-pane.appearance-pane").show();
		$(".settings-page-button.menu-button.appearance").addClass("active");
	}
	function reset_settings() {
		$(".settings-pane").hide();
		$(".settings-page-button.menu-button").removeClass("active");
		$(".settings-page-input").val("");
		$(".settings-page-button.choice-button").removeClass("active");
		$(".settings-pane.appearance-pane").show();
		$(".settings-page-button.menu-button.appearance").addClass("active");
		get_config();
		setTimeout(function() {
			var config = JSON.parse($(".settings-page-wrapper").attr("data-config"));
			if(config["theme"] == "light") {
				$(".action-light-theme").addClass("active");
			}
			else if(config["theme"] == "dark") {
				$(".action-dark-theme").addClass("active");
			}
			if(config["accent-color"] == "green") {
				$(".action-accent-green").addClass("active");
			}
			else if(config["accent-color"] == "blue") {
				$(".action-accent-blue").addClass("active");
			}
			else if(config["accent-color"] == "purple") {
				$(".action-accent-purple").addClass("active");
			}
			else if(config["accent-color"] == "red") {
				$(".action-accent-red").addClass("active");
			}
			else if(config["accent-color"] == "pink") {
				$(".action-accent-pink").addClass("active");
			}
			else if(config["accent-color"] == "orange") {
				$(".action-accent-orange").addClass("active");
			}
			if(config["page-sharing"] == "enabled") {
				$(".action-sharing-enable").addClass("active");
			}
			else if(config["page-sharing"] == "disabled") {
				$(".action-sharing-disable").addClass("active");
			}
		}, 250);
	}
	function set_theme(theme) {
		if(theme == "light") {
			$("style.main-color-css").html(":root { --main-light:250,250,250; --main-dark:245,245,245; --main-darker:235,235,235; }");
		}
		else if(theme == "dark") {
			$("style.main-color-css").html(":root { --main-light:60,60,60; --main-dark:40,40,40; --main-darker:30,30,30; }");
		}
	}
	function accent_color(color) {
		if(color == "green") {
			$("style.accent-color-css").html(":root { --accent-light:100,200,40; --accent-dark:100,185,40; --accent-darker:75,175,40; }");
		}
		if(color == "blue") {
			$("style.accent-color-css").html(":root { --accent-light:0,150,255; --accent-dark:0,130,240; --accent-darker:0,115,230; }");
		}
		if(color == "purple") {
			$("style.accent-color-css").html(":root { --accent-light:160,60,210; --accent-dark:140,50,190; --accent-darker:120,40,170; }");
		}
		if(color == "red") {
			$("style.accent-color-css").html(":root { --accent-light:220,20,60; --accent-dark:200,10,50; --accent-darker:180,0,40; }");
		}
		if(color == "pink") {
			$("style.accent-color-css").html(":root { --accent-light:230,170,180; --accent-dark:215,155,170; --accent-darker:200,140,160; }");
		}
		if(color == "orange") {
			$("style.accent-color-css").html(":root { --accent-light:255,200,0; --accent-dark:255,180,0; --accent-darker:255,160,0; }");
		}
	}
	function logout() {
		$.ajax({
			type: "POST",
			data: { action: "logout" },
			url: "./scripts/process.php",
			success: function(data) {
				if(data == "done") {
					location.reload();
				}
				else {
					notify("Error...", "Something went wrong.", "red", 4000);
				}
			}
		});
	}
	function close_menu() {
		$(".menu-wrapper").hide().css({"left":"-255px"});
		$(".main-navbar-menu-button").removeClass("active");
	}
	function open_menu() {
		$(".menu-wrapper").show().animate({left:"0"}, 250);
		$(".main-navbar-menu-button").addClass("active");
	}
	function close_user_prompt() {
		$(".user-prompt-overlay").hide();
		$(".user-prompt-wrapper").data("action", "").attr("id", "");
		$(".user-prompt-action.submit").hide();
		$(".user-prompt-section").hide();
		$(".user-prompt-action.submit").hide();
		$(".user-prompt-placeholder-image-overlay").show();
		$(".user-prompt-placeholder-image").attr("src", "").hide();
		$(".user-prompt-edit-rename .user-prompt-input.rename").val("");
		$(".user-prompt-add .user-prompt-input.title").val("");
		reset_input(".user-prompt-input.image");
	}
	function open_user_prompt(intent, action, data) {
		$(".user-prompt-overlay").show();
		$(".user-prompt-wrapper").data("action", action).attr({"id":"", "data-title":""});
		$(".user-prompt-placeholder-image").attr("src", "").hide();
		$(".user-prompt-placeholder-image-overlay").show();
		$(".user-prompt-add .user-prompt-input.title").val("");
		$(".user-prompt-section").hide();
		if(intent == "add-item") {
			$(".user-prompt-add").show();
			$(".user-prompt-action.submit").show().css("display", "block");
		}
		else if(intent == "edit-item") {
			$(".user-prompt-edit").show();
			$(".user-prompt-wrapper").attr({"id":data["id"], "data-title":data["title"]});
		}
		else if(intent == "catalogue-item") {
			$(".user-prompt-catalogue").show();
			$(".user-prompt-catalogue-image").attr("src", data["image"]);
			$(".user-prompt-input.title").val(data["title"]);
		}
	}
	function get_items(delay) {
		setTimeout(function() {
			$.ajax({
				type: "POST",
				data: { action: "get-items" },
				url: "./scripts/api.php", 
				success: function(data) {
					if(data.length != 0 && data != "empty") {
						$(".collection-items-dynamic").html(data);
					}
					if(data.length == 0 && data != "empty") {
						notify("Error...", data, "red", 4000);
					}
					if(data.length != 0 && data == "empty") {
						$(".collection-items-dynamic").html("");
					}
				}
			});
		}, delay);
	}
	function get_config() {
		$.ajax({
			type: "POST",
			data: { action: "get-config" },
			url: "./scripts/api.php", 
			success: function(data) {
				if(data.length != 0) {
					$(".settings-page-wrapper").attr("data-config", data);
				}
				else {
					notify("Error...", "Something went wrong.", "red", 4000);
				}
			}
		});
	}
	function image_preview(input, element) {
		if(input.files && input.files[0]) {
			var reader = new FileReader();
			reader.onload = function(e) {
				$(element).attr('src', e.target.result).show();
			}
			reader.readAsDataURL(input.files[0]);
		}
	}
	function ucfirst(string) {
		return string.charAt(0).toUpperCase() + string.slice(1);
	}
	function epoch() {
		var date = new Date();
		var time = Math.round(date.getTime() / 1000);
		return time;
	}
	function reset_input(element) {
		$(element).wrap('<form>').closest('form').get(0).reset();
		$(element).unwrap();
	}
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
	$(document).mouseup(function(e) {
		var container = new Array();
		container.push($('.menu-wrapper'));

		$.each(container, function(key, value) {
			if(!$(value).is(e.target) && $(value).has(e.target).length === 0) {
				$(value).hide();
			}
		});
		
		setTimeout(function() {
			if($(".menu-wrapper").is(":hidden")) {
				close_menu();
			}
		}, 5);
	});
	$(".collection-items-wrapper, .catalogue-items-wrapper").on("scroll", function() {
		close_menu();
		if($(this).scrollTop() > 15) {
			$(".main-navbar").css({"opacity":"0.85"});
		}
		else {
			$(".main-navbar").css({"opacity":"1"});
		}
	});
});