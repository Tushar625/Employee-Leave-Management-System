<?php

	/*
		check if it's valid mg session or not if not redirect
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
		// now we store manager consent

		$lrid = mysql_sanitize_input($link, $_POST['lrid']);

		$comment = mysql_sanitize_input($link, $_POST['comment']);

		$consent = ($mrank == 1) ? "mg1_consent" : "mg2_consent";

		/*
			manager1 can enter his consent if mg1_consent and mg2_consent both are null

			manager2 can enter his consent if mg1_consent is not null but mg2_consent is null
		*/

		$mg1_consent = ($mrank == 1) ? "IS NULL" : "IS NOT NULL";

		$query = "UPDATE leave_request SET $consent = '$comment' WHERE lrid = $lrid  AND mg1_consent $mg1_consent AND mg2_consent IS NULL";

		// fail check

		if($link -> query($query) == false)
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
		
		<header>
			<?php include "header.php";?>
		</header>

		<main>
			
		<form method = "post" action = "comment.php">

		<input name = "lrid" type = hidden value = <?php echo $lrid?> required>
		
		<ul class = "main_box nice_shadow">

			<!-- input the comment or consent -->

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