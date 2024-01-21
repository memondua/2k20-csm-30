<?php
	$user_name = $_COOKIE["user-name"];
?>
<!DOCTYPE html>
<html lang="en">
	<head>
		<meta charset="UTF-8" />
		<meta http-equiv="X-UA-Compatible" content="IE=edge" />
		<meta name="viewport" content="width=device-width, initial-scale=1.0" />
		<title>Home</title>
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
			<h1>This is home page</h1>
			<p>
				<?php if(isset($user_name)):?>
					Hello <?=$user_name?>
					(<a href="logout.php">Logout</a>)
				<?php endif;?>
			</p>
		</div>
	</body>
</html>
