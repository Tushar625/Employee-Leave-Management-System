<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_mng_session.php";

	include "../PHP/config.php";

	include "../PHP/mysql_sanitize_input.php";

	$mrank = $_SESSION["MANAGER_RANK"];

	if(isset($_GET["lrid"]))
	{
		$lrid = mysql_sanitize_input($link, $_GET['lrid']);

		$link -> close();
	}
	else if(isset($_POST['submit']))
	{
		// here we use get variables to send error messages while redirecting to itself

		/*
			After each successful submission we redirect to the same form to display
			the error or success message. As the inputs submitted here will be stored
			into the login file, redirection is used to ensure that no resubmission
			error will be generated upon reload or back. (More about this error in the
			documentation)

			While redirecting we use get method to send the error or success message.
		*/

		/*
			no problem detected, hence we receive rest of the inputs
			and load them into leave rules table
		*/

		$lrid = mysql_sanitize_input($link, $_POST['lrid']);

		$comment = mysql_sanitize_input($link, $_POST['comment']);

		$consent = ($mrank == 1) ? "mg1_consent" : "mg2_consent";

		$query = "UPDATE leave_request SET $consent = '$comment' WHERE lrid = $lrid";

		// fail check

		if($link -> query($query) === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		header("location: index.php");
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
			
		<form method = "post" action = "comment.php">

		<input name = "lrid" type = hidden value = <?php echo $lrid?> required>
		
		<ul class = "main_box nice_shadow">

			<!-- Indicate successfull Registration creation -->

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<textarea name = "comment" maxlength = 500 required></textarea>
			</li>

			<li>
				<div class = "error message">
					Enter your comment carefully, it can't be modified in future.
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Comment">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>