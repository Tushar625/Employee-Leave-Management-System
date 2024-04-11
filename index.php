<?php

	include "PHP/check_destroy_secure_session.php";

	destroy_session_and_data();	// destroy any existing session

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>home</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

			@import url("CSS/index styles.css");

		</style>

	</head>
	
	<body>
		
		<header>

			<ul class = "header_list">
				
				<img src = "images/poke ball.png">
			
				<li id = "title">Shuckle Quiz</li>

				<li class = "left_most header_button"><a href = "admin login.php">Admin</a></li>

			</ul>
		
		</header>

		<main>
		
		<ul class = "main_box">

			<li>

				<a href = "register.php"><button class = "button"> Create New Profile </button></a>

			</li>

			<li>

				<a href = "login.php"><button class = "button"> Open Your Profile </button></a>

			</li>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>