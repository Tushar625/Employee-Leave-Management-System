<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	// include "PHP/check_admin_session.php";

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>HR_home</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

			@import url("CSS/index styles.css");

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include "PHP/admin_header.php";?>
		
		</header>

		<main>
		
		<ul class = "main_box">

			<li>

				<a href = "emp_input.php"><button class = "button"> Enlist an Employee </button></a>

			</li>

			<li>

				<a href = "view_employees.php"><button class = "button"> Display all Employee </button></a>

			</li>

			<li>

				<a href = "leave_rules_input.php"><button class = "button"> Input a Leave Rule </button></a>

			</li>

			<li>

				<a href = "view_leave_rules.php"><button class = "button"> Display all Leave Rules </button></a>

			</li>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>