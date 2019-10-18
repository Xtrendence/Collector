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
	
	if($action == "login") {
		if(!empty($_POST['username']) && !empty($_POST['password'])) {			
			$username = strtolower($_POST['username']);
			$password = $_POST["password"];
			$remember = $_POST["remember"];
			
			if(strtolower($username) == strtolower($valid_username)) {
				if(password_verify($password, $valid_password)) {
					$_SESSION['Username'] = $valid_username;
					
					$account["token"]["key"] = time() . "-" . str_shuffle(hash("sha256", md5(random_int(00000000, 99999999))));
					
					if($remember == "true") {
						$account["token"]["time"] = time();
						setcookie("collector-remember-me", $account["token"]["key"], time() + 604800, "/");
					}
					else {
						$account["token"]["time"] = "";
						setcookie("collector-remember-me", null, -1, "/");
					}
					
					$json = json_encode($account);
					$write = file_put_contents("../source/cfg/account.config", $json);
					echo "done";
				}
				else {
					echo "Invalid password.";
				}
			}
			else {
				echo "Invalid username.";
			}
		}
		else {
			echo "Please fill out both input fields.";
		}
	}
	if($action == "logout") {
		session_start();
		session_destroy();
		$_SESSION = array();
		$account["token"]["key"] = time() . "-" . str_shuffle(hash("sha256", md5(random_int(00000000, 99999999))));
		$account["token"]["time"] = "";
		setcookie("collector-remember-me", null, -1, "/");
		$json = json_encode($account);
		$write = file_put_contents("../source/cfg/account.config", $json);
		if(empty($_SESSION)) {
			echo "done";	
		}
	}
	
	if($logged_in) {
		// Imgur Client ID for API Access
		$client_id = "YOUR_CLIENT_ID";
		
		if($action == "add-item") {
			$title = "Undefined Title";
			$image = "no-image-icon";
			$file = $image;
			$catalogue = true;
			$delete_hash = "local";
			if(!empty(trim($_POST['title']))) {
				$title = $_POST['title'];
			}
			if(!empty(trim($_POST['image']))) {
				$image = $_POST['image'];
				$file = $image;
			}
			if(empty($_POST['catalogue']) or $_POST['catalogue'] == "false") {
				$catalogue = false;
			}
			
			$current_user_items = json_decode(file_get_contents("../source/cfg/user_items.config"), true);
			
			if(!$catalogue && !empty($_POST['image'])) {
				$image_base64 = preg_replace('#^data:image/[^;]+;base64,#', '', $_POST['image']);
				$post_fields = array("image" => $image_base64);
				$timeout = 30;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
				curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
				curl_setopt($curl, CURLOPT_POST, 1);
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
				$output = curl_exec($curl);
				curl_close($curl);
				$result = json_decode($output, true);
				$file = $result["data"]["link"];
				$delete_hash = $result["data"]["deletehash"];
			}
			
			$item_id = time() . "-" . md5(str_shuffle(time()));
			
			$new_item = array("title" => $title, "image" => $file, "delete-hash" => $delete_hash);
			
			$current_user_items[$item_id] = $new_item;
			
			$write = file_put_contents("../source/cfg/user_items.config", json_encode($current_user_items));
			if($write) {
				echo "done";
			}
		}
		if($action == "delete-item") {
			$id = $_POST["id"];
			$current_user_items = json_decode(file_get_contents("../source/cfg/user_items.config"), true);
			
			if($current_user_items[$id]["delete-hash"] != "local") {
				$timeout = 30;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image/' . $current_user_items[$id]["delete-hash"]);
				curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($curl);
				curl_close($curl);
				if($output["success"]) {
					unset($current_user_items[$id]);
					$write = file_put_contents("../source/cfg/user_items.config", json_encode($current_user_items));
					if($write) {
						echo "done";
					}
				}
			}
			else {
				unset($current_user_items[$id]);
				$write = file_put_contents("../source/cfg/user_items.config", json_encode($current_user_items));
				if($write) {
					echo "done";
				}
			}
		}
		if($action == "rename-item") {
			$id = $_POST["id"];
			$title = "Undefined Title";
			if(!empty($_POST['title'])) {
				$title = $_POST['title'];
			}
			
			$current_user_items = json_decode(file_get_contents("../source/cfg/user_items.config"), true);
			
			$current_user_items[$id]["title"] = $title;
			
			$write = file_put_contents("../source/cfg/user_items.config", json_encode($current_user_items));
			if($write) {
				echo "done";
			}
		}
		
		if($action == "change-image") {
			$id = $_POST["id"];
			
			$current_user_items = json_decode(file_get_contents("../source/cfg/user_items.config"), true);
			
			$file = "no-image-icon";
			$delete_hash = "local";
			$local = true;
			
			$timeout = 30;
			
			if($current_user_items[$id]["delete-hash"] != "local") {
				$local = false;
				$curl = curl_init();
				curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image/' . $current_user_items[$id]["delete-hash"]);
				curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
				curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
				curl_setopt($curl, CURLOPT_CUSTOMREQUEST, 'DELETE');
				curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
				$output = curl_exec($curl);
				curl_close($curl);
				if($output["success"]) {
					$deleted = true;
				}
			}
			if($local or $deleted) {
				if(!empty($_POST["image"])) {
					$image_base64 = preg_replace('#^data:image/[^;]+;base64,#', '', $_POST['image']);
					$post_fields = array("image" => $image_base64);
					$curl = curl_init();
					curl_setopt($curl, CURLOPT_URL, 'https://api.imgur.com/3/image.json');
					curl_setopt($curl, CURLOPT_TIMEOUT, $timeout);
					curl_setopt($curl, CURLOPT_HTTPHEADER, array('Authorization: Client-ID ' . $client_id));
					curl_setopt($curl, CURLOPT_POST, 1);
					curl_setopt($curl, CURLOPT_RETURNTRANSFER, 1);
					curl_setopt($curl, CURLOPT_POSTFIELDS, $post_fields);
					$output = curl_exec($curl);
					curl_close($curl);
					$result = json_decode($output, true);
					$file = $result["data"]["link"];
					$delete_hash = $result["data"]["deletehash"];
				}
				
				$current_user_items[$id]["image"] = $file;
				$current_user_items[$id]["delete-hash"] = $delete_hash;
				
				$write = file_put_contents("../source/cfg/user_items.config", json_encode($current_user_items));
				if($write) {
					echo "done";
				}
			}
		}
		if($action == "reset-settings") {
			$default_settings = array("theme" => "light", "accent-color" => "green", "page-sharing" => "enabled");
			$write = file_put_contents("../source/cfg/settings.config", json_encode($default_settings));
			if($write) {
				echo "done";
			}
		}
		if($action == "change-settings") {
			$current_config = json_decode(file_get_contents("../source/cfg/settings.config"), true);
			$new_config = $_POST['config'];
			if(!empty(trim($new_config))) {
				$write = file_put_contents("../source/cfg/settings.config", $new_config);
				if($write) {
					echo $new_config;
				}
			}
		}
		if($action == "change-username") {
			if(!empty($_POST['username']) && !empty($_POST['password'])) {
				$username = $_POST['username'];
				$password = $_POST['password'];
				if(password_verify($password, $valid_password)) {
					if(ctype_alnum($username)) {
						$account["user"]["username"] = $username;
						$json = json_encode($account);
						file_put_contents("../source/cfg/account.config", $json);
						echo "done";
					}
					else {
						echo "Username can only have letters and numbers.";
					}
				}
				else {
					echo "Wrong password.";
				}
			}
			else {
				echo "Please fill out both fields.";
			}
		}
		if($action == "change-password") {
			if(!empty($_POST['current_password']) && !empty($_POST['new_password'])) {
				$current_password = $_POST['current_password'];
				$new_password = $_POST['new_password'];
				if(password_verify($current_password, $valid_password)) {
					$hashed = password_hash($new_password, PASSWORD_BCRYPT);
					$account["user"]["password"] = $hashed;
					$json = json_encode($account);
					file_put_contents("../source/cfg/account.config", $json);
					echo "done";
				}
				else {
					echo "Wrong password.";
				}
			}
			else {
				echo "Please fill out all fields.";
			}
		}
	}
?>