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
	
	if($logged_in) {
?>
<!-- Copyright <?php echo date('Y'); ?> © Xtrendence -->
<!DOCTYPE html>
<html>
	<head>
		<link class="theme-css" rel="stylesheet" href="./source/css/backup.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="./source/css/resize.css?<?php echo time(); ?>">
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="#ebebeb" class="navbar-theme-color">
		<title>Backup</title>
	</head>
	<body id="<?php echo $device; ?>" data-page="backup">
		<div class="collection-items-wrapper noselect">
			<?php
				$items = array_reverse(json_decode(file_get_contents("./source/cfg/user_items.config"), true));
				if(!empty($items)) {
					foreach($items as $item_id => $item_info) {
						if($item_info["image"] == "no-image-icon") {
							$image = '<div class="collection-item-placeholder-image-wrapper">' . $placeholder_icon . '</div>';
						}
						else {
							$image = '<img class="collection-item-image" src="' . $item_info["image"] . '">';
						}
						echo '<div class="collection-item-pane"><div class="collection-item-image-wrapper" data-title="' . $item_info["title"] . '" id="' . $item_id . '">' . $image . '</div><span class="collection-item-title" data-title="' . $item_info["title"] . '">' . $item_info["title"] . '</span></div>';
					}
				}
				else {
					echo '<div class="empty-overlay"><button class="empty-label">No Items Found</button></div>';
				}
			?>
		</div>
	</body>
</html>
<?php } else { echo "Access Denied."; } ?>