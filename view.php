<?php
	include "./assets/function_icons.php";
	
	include "./scripts/detect_device.php";
	if($user_agent_mobile) {
		$device = "mobile";
	}
	else {
		$device = "desktop";
	}
	
	$config = json_decode(file_get_contents("./source/cfg/settings.config"), true);
	if($config["page-sharing"] == "enabled") {
?>
<!-- Copyright <?php echo date('Y'); ?> © Xtrendence -->
<!DOCTYPE html>
<html>
	<head>
		<link class="theme-css" rel="stylesheet" href="./source/css/view.css?<?php echo time(); ?>">
		<link rel="stylesheet" href="./source/css/resize.css?<?php echo time(); ?>">
		<script src="./source/js/jquery.js"></script>
		<script src="./source/js/lazysizes.js"></script>
		<meta name="viewport" content="width=device-width, initial-scale=1.0, user-scalable=no">
		<meta name="mobile-web-app-capable" content="yes">
		<meta name="theme-color" content="#ebebeb" class="navbar-theme-color">
		<title>View Collection</title>
	</head>
	<body id="<?php echo $device; ?>" data-page="view">
		<div class="collection-items-wrapper noselect">
			<?php
				$items = array_reverse(json_decode(file_get_contents("./source/cfg/user_items.config"), true));
				if(!empty($items)) {
					foreach($items as $item_id => $item_info) {
						if($item_info["image"] == "no-image-icon") {
							$image = '<div class="collection-item-placeholder-image-wrapper">' . $placeholder_icon . '</div>';
						}
						else {
							$image = '<img class="collection-item-image lazyload" src="./images/placeholder.jpg" data-src="' . $item_info["image"] . '">';
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