<?php
$users = array(
    array(
        "user-name" => "user1",
        "user-pass" => "pass1"
    ),
    array(
        "user-name" => "user2",
        "user-pass" => "pass2"
    ),
    array(
        "user-name" => "user3",
        "user-pass" => "pass3"
    ),
);

if(isset($_GET['user-name'])) {
	$user_name = $_GET['user-name'];
	$user_pass = $_GET['user-pass'];

	$login_success = false;

	foreach($users as $user) {
		// Destructure the array (the element $user is also an array)
		['user-name'=>$db_user_name, 'user-pass'=>$db_user_pass] = $user;
		if($user_name === $db_user_name and $user_pass === $db_user_pass) {
			$login_success = true;
			break;
		}
	}

	if($login_success) {
		setcookie("user-name", $user_name);
		header("Location: home.php");
	}
}
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Main</title>
	</head>
	<body>
		<nav>
			<ul style="column-count: 3; list-style: none">
				<li><a href="home.php">Home</a></li>
				<li><a href="about.php">About</a></li>
				<li><a href="contact.php">Contact</a></li>
			</ul>
		</nav>
		<div style="text-align: center">
			<h1>Login Form</h1>
			<form method="GET" action="<?=$_SERVER['PHP_SELF']?>">
				<div>
					<label
						>User Name
						<input type="text" name="user-name" value="<?=$user_name?>" required />
					</label>
				</div>
				<div>
					<label
						>User Password
						<input type="password" name="user-pass" required />
					</label>
				</div>
				<div>
					<input type="submit" value="Submit" />
				</div>
			</form>
		</div>
	</body>
</html>
