<?php

	/*
		check if it's valid mg session or not if not redirect
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

		/*
			manager1 can see only if mg1_consent and mg2_consent both are null

			manager2 can see only if mg1_consent is not null but mg2_consent is null
		*/

		$mg1_consent = ($mrank == 1) ? "IS NULL" : "IS NOT NULL";

		$query = "SELECT $data from leave_request WHERE lrid = $lrid AND mg1_consent $mg1_consent AND mg2_consent IS NULL";

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
			
			$reason = $row["reason"];

			if($mrank == 2)
			{
				// only mg2 sees mg1 consent

				$consent = $row["mg1_consent"];
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

		<title>Consent</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

		</style>

	</head>
	
	<body>
		
		<header>
			<?php include "header.php";?>
		</header>

		<main>
		
		<ul class = "main_box nice_shadow">
			
			<li>
				Reason<div class = "message"><?php echo $reason?></div>
			</li>	
			
			<?php if(isset($consent)):?>

				<li>
					<?php echo get_rank($mrank - 1)?><div class = "message error"><?php echo $consent?></div>
				</li>

			<?php endif?>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>