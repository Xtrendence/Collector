<!-- Copyright <?php echo date('Y'); ?> © Xtrendence -->
<!DOCTYPE html>
<html>
	<head>
		<link class="theme-css" rel="stylesheet" href="./source/css/login-style.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="./source/css/login-resize.css?<?php echo time(); ?>">
		<script src="./source/js/jquery.js"></script>
		<script src="./source/js/login.js?<?php echo time(); ?>"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="<?php echo $theme_color; ?>" class="navbar-theme-color">
		<title>Login</title>
		<?php echo $accent_color; ?>
		<?php echo $main_color; ?>
		<?php echo $text_color; ?>
	</head>
	<body id="<?php echo $device; ?>" data-page="login">
		<div class="login-wrapper">
			<button class="login-label">Welcome</button>
			<input class="login-input username" type="text" placeholder="Username...">
			<input class="login-input password" type="password" placeholder="Password...">
			<button class="login-remember-button">Remember Me</button>
			<button class="login-button">Login</button>
		</div>
		<div class="notification-area"></div>
	</body>
</html>