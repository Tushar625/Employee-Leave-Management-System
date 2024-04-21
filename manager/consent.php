<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_mng_session.php";

	include "../PHP/config.php";

	include "../PHP/mysql_sanitize_input.php";

	include "../PHP/emp_ranking_system.php";

	$mrank = $_SESSION["MANAGER_RANK"];

	if(isset($_GET["lrid"]))
	{
		$lrid = mysql_sanitize_input($link, $_GET['lrid']);

		// collecting consent of junior manager and reason for leave

		$data = ($mrank == 1) ? "reason" : "reason, mg1_consent";

		// mg2 or mg1 can see the reason and the consent of mg1 only before he gives his consent

		$consent_check = ($mrank == 1) ? "mg1_consent IS NULL" : "mg1_consent IS NOT NULL AND mg2_consent IS NULL";

		$query = "SELECT $data from leave_request WHERE lrid = $lrid AND $consent_check";

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
			$row = $result -> fetch_assoc();
			
			$reason = "Reason: " . $row["reason"];

			if($mrank == 2)
			{
				// if mg2 is watching

				$consent = get_rank($mrank - 1) . ": " . $row["mg1_consent"];
			}
		}
		else
		{
			// nothing to display hence, page not found

			header("HTTP/1.0 404 Not Found", true, 404);

			exit();
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
				<div class = "message info"><?php echo $reason?></div>
			</li>	
			
			<?php if(isset($consent)):?>

				<li>
					<div class = "message error"><?php echo $consent?></div>
				</li>

			<?php endif?>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>