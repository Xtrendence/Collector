<?php
	session_start();
	$username = $_SESSION['Username'];
	$account = json_decode(file_get_contents("./source/cfg/account.config"), true);
	if(!empty($account["token"]["time"]) && time() - $account["token"]["time"] > 604800) {
		$key = time() . "-" . str_shuffle(hash("sha256", md5(random_int(00000000, 99999999))));
		$account["token"]["key"] = $key;
		$account["token"]["time"] = "";
		$json = json_encode($account);
		$write = file_put_contents("./source/cfg/account.config", $json);
		setcookie("collector-remember-me", null, -1, "/");
	}
	if(isset($_COOKIE['collector-remember-me']) && $_COOKIE['collector-remember-me'] == $account["token"]["key"] && !empty($account["token"]["time"])) {
		$token_valid = true;
	}
	$valid_username = $account["user"]["username"];
	$valid_password = $account["user"]["password"];
	if(strtolower($username) == strtolower($valid_username) or $token_valid) {
		$logged_in = true;
	}
	
	include "./assets/function_icons.php";
	
	include "./scripts/detect_device.php";
	if($user_agent_mobile) {
		$device = "mobile";
	}
	else {
		$device = "desktop";
	}
	
	$config = json_decode(file_get_contents("./source/cfg/settings.config"), true);
	$theme = $config["theme"];
	$color = $config["accent-color"];
	
	if($theme == "light") {
		$theme_color = "#ebebeb";
		$main_color = '<style class="main-color-css">:root { --main-light:250,250,250; --main-dark:245,245,245; --main-darker:235,235,235; }</style>';
		$text_color = '<style class="text-color-css">:root { --text-light:125,125,125; --text-dark:75,75,75; }</style>';
	}
	elseif($theme == "dark") {
		$theme_color = "#3c3c3c";
		$main_color = '<style class="main-color-css">:root { --main-light:60,60,60; --main-dark:40,40,40; --main-darker:30,30,30; }</style>';
		$text_color = '<style class="text-color-css">:root { --text-light:250,250,250; --text-dark:240,240,240; }</style>';
	}
	
	if($color == "green") {
		$accent_color = '<style class="accent-color-css">:root { --accent-light:100,200,40; --accent-dark:100,185,40; --accent-darker:75,175,40; }</style>';
	}
	elseif($color == "blue") {
		$accent_color = '<style class="accent-color-css">:root { --accent-light:0,150,255; --accent-dark:0,130,240; --accent-darker:0,115,230; }</style>';
	}
	elseif($color == "purple") {
		$accent_color = '<style class="accent-color-css">:root { --accent-light:160,60,210; --accent-dark:140,50,190; --accent-darker:120,40,170; }</style>';
	}
	elseif($color == "red") {
		$accent_color = '<style class="accent-color-css">:root { --accent-light:220,20,60; --accent-dark:200,10,50; --accent-darker:180,0,40; }</style>';
	}
	elseif($color == "pink") {
		$accent_color = '<style class="accent-color-css">:root { --accent-light:230,170,180; --accent-dark:215,155,170; --accent-darker:200,140,160; }</style>';
	}
	elseif($color == "orange") {
		$accent_color = '<style class="accent-color-css">:root { --accent-light:255,200,0; --accent-dark:255,180,0; --accent-darker:255,160,0; }</style>';
	}
	
	if(!$logged_in) {
		include "./assets/login.php";
	}
	else {
?>
<!-- Copyright <?php echo date('Y'); ?> © Xtrendence -->
<!DOCTYPE html>
<html>
	<head>
		<link class="theme-css" rel="stylesheet" href="./source/css/style.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="./source/css/resize.css?<?php echo time(); ?>">
		<script src="./source/js/jquery.js"></script>
		<script src="./source/js/lazysizes.js"></script>
		<script src="./source/js/main.js?<?php echo time(); ?>"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo $theme_color; ?>" class="navbar-theme-color">
		<title>Collector</title>
		<?php echo $accent_color; ?>
		<?php echo $main_color; ?>
		<?php echo $text_color; ?>
	</head>
	<body id="<?php echo $device; ?>" data-page="index">
		<?php include "./assets/navbar.php"; ?>
		<div class="collection-items-wrapper noselect">
			<div class="toolbar-wrapper">
				<div class="search-wrapper">
					<input class="search-bar" type="text" placeholder="Search...">
				</div>
			</div>
			<div class="add-item-pane">
				<div class="add-item-icon-wrapper">
					<div class="add-item-icon"><?php echo $add_icon; ?></div>
				</div>
				<span class="add-item-title">Add Item</span>
			</div>
			<div class="collection-items-dynamic"></div>
		</div>
		<?php include "./assets/ui_elements.php"; } ?>
	</body>
</html>