<?php
	session_start();
	$username = $_SESSION['Username'];
	$account = json_decode(file_get_contents("../source/cfg/account.config"), true);
	if(!empty($account["token"]["time"]) && time() - $account["token"]["time"] > 604800) {
		$key = time() . "-" . str_shuffle(hash("sha256", md5(random_int(00000000, 99999999))));
		$account["token"]["key"] = $key;
		$account["token"]["time"] = "";
		$json = json_encode($account);
		$write = file_put_contents("../source/cfg/account.config", $json);
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
	
	$action = $_POST['action'];
	
	if($logged_in) {
		if($action == "get-items") {
			include "../assets/function_icons.php";
			$items = array_reverse(json_decode(file_get_contents("../source/cfg/user_items.config"), true));
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
				echo "empty";
			}
		}
		if($action == "get-config") {
			$config = file_get_contents("../source/cfg/settings.config");
			echo $config;
		}
	}
?>