<?php

	/*
		check if it's valid hr session or not if not redirect
		to index or home page
	*/

	include_once "../PHP/check_hr_session.php";

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>HR_home</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

			@import url("../CSS/index styles.css");

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include_once "header.php";?>
		
		</header>

		<main>
		
		<ul class = "main_box">

			<li>

				<a href = "emp_input.php"><button class = "button"> Enlist an Employee </button></a>

			</li>

			<li>

				<a href = "leave_rules_input.php"><button class = "button"> Input a Leave Rule </button></a>

			</li>

			<li>

				<a href = "view.php?type=1"><button class = "button"> Display all Employees </button></a>

			</li>

			<li>

				<a href = "view.php?type=0"><button class = "button"> Display all Leave Rules </button></a>

			</li>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>