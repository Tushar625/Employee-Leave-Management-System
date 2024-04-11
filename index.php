<?php

	include "PHP/check_destroy_secure_session.php";

	destroy_session_and_data();	// destroy any existing session

	/*
		"PHP/poke_names_datalist.php" contains the source code for the datalist holding
		the pokemon names, it's created by "PHP/poke_names_datalist_init.php" file and
		stored permanently in 'PHP' folder and is used by the forms that receives pokemon
		names as inputs.

		If "PHP/poke_names_datalist.php" is missing it is created by "PHP/poke_names_datalist_init.php"
		else we don't use "PHP/poke_names_datalist_init.php".

		This is done to ensure that the forms using the list of pokemon names don't have
		to download list of pokemon names from poke api, each time they are used, only when
		"PHP/poke_names_datalist.php" is missing and "index.php" is opened, the list is
		downloaded from poke api by "PHP/poke_names_datalist_init.php"
	*/

	if(!file_exists("PHP/poke_names_datalist.php"))
	{
		include "PHP/poke_names_datalist_init.php";
	}

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