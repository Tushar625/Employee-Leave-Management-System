<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_mng_session.php";

	if($_SESSION["MANAGER_RANK"] == 1)
	{
		// check if mg1 accessing it, he can't access it

		header("location: ../index.php");

		exit();
	}

	include "../PHP/config.php";

	include "../PHP/mysql_sanitize_input.php";

	include "../PHP/emp_ranking_system.php";

	$mrank = $_SESSION["MANAGER_RANK"];

	if(isset($_GET["lrid"]))
	{
		$lrid = mysql_sanitize_input($link, $_GET['lrid']);

		// collecting consent of junior manager

		$consent = ($mrank == 2) ? "mg1_consent" : "mg2_consent";

		$query = "SELECT $consent from leave_request WHERE lrid = $lrid";

		$result = $link -> query($query);

		$link -> close();

		// fail check

		if($result === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		// Prepare Proper consent string to display

		if($result -> num_rows != 0)
		{
			$consent = get_rank($mrank - 1) . ": " . $result -> fetch_assoc()["$consent"];
		}
		else
		{
			$consent = "No consent from " . get_rank($mrank);
		}
	}
	else
	{
		header("location: index.php");
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>Comments</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

		</style>

	</head>
	
	<body>

		<!--
			We don't keep any return to home button here to discourage
			user from accidentally return from registration form, I want
			him to create an account successfully and then login to his
			profile and play
		-->
		
		<header>
			<?php include "header.php";?>
		</header>

		<main>
		
		<ul class = "main_box nice_shadow">
			<li>
				<div class = "message info"><?php echo $consent?></div>
			</li>
		</ul>

		</main>

		<footer></footer>

	</body>

</html>