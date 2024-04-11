<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "PHP/check_admin_session.php";

	if(isset($_POST['submit']))
	{
		// quiz is submitted

		include "PHP/config.php";	// connect to database

		include "PHP/mysql_sanitize_input.php";

		$quiz1 = mysql_sanitize_input($link, $_POST['quiz1']);
		$quiz2 = mysql_sanitize_input($link, $_POST['quiz2']);
		$quiz3 = mysql_sanitize_input($link, $_POST['quiz3']);
		$quiz4 = mysql_sanitize_input($link, $_POST['quiz4']);
		$quiz5 = mysql_sanitize_input($link, $_POST['quiz5']);
		$ans = mysql_sanitize_input($link, $_POST['ans']);

		// checking if current quiz exists or not

		include "PHP/start_date_current_qid.php";

		if($current_qid > 0 && $link -> query("SELECT qid FROM quiz WHERE qid = $current_qid") -> num_rows == 0)
		{
			// no quiz exists for today and starting date was in past, hence set auto increment value to current qid

			$link -> query("ALTER TABLE quiz AUTO_INCREMENT = $current_qid;");
		}

		$query = "INSERT INTO quiz(quiz1, quiz2, quiz3, quiz4, quiz5, ans) VALUES('$quiz1', '$quiz2', '$quiz3', '$quiz4', '$quiz5', '$ans');";

		// fail check

		if($link -> query($query) === false)
		{
			/*
				If the input quiz can’t be entered into the quiz
				table, user will be forced to head back to Admin
				Index page (this is annoying but easy to make,
				apart from that it’s very unlikely that quiz input
				will fail).
			*/
			
			die("Form submission failure, head back to <a href = 'admin index.php'> admin_home </a>");
		}

		$link -> close();

		$success = '?success=true';	// get variables to indicate successful form submission
	}
	else
	{
		$success = '';	// no get variables to send
	}

	/*
		redirecting to itself once to:
		
		clear the past inputs to avoid resubmission of same quiz when
		user returns back to previous input form

		and to avoid form resubmission error on reload (more about this
		in the documentation)
	*/

	include "PHP/self_redirect_once.php";

	self_redirect_once($success);

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>quiz_input</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include "PHP/admin_header.php";?>

		</header>

		<main>
			
		<form method = "post" action = "quiz input.php">
		
		<ul class = "main_box nice_shadow">

			<!-- indicate successful form submission -->

			<?php if(isset($_GET['success'])) :?>

				<li>
					<div class = "info message">
						Quiz entered successfully, Enter a new one if you wish.
					</div>
				</li>

			<?php endif; ?>

			<!-- Maxlength is set according to size of the field in quiz table -->
			
			<li>
				<label> Quiz 1 <textarea name = "quiz1" maxlength = 256 required></textarea> </label>
			</li>
			
			<li>
				<label> Quiz 2 <textarea name = "quiz2" maxlength = 256 required></textarea> </label>
			</li>

			<li>
				<label> Quiz 3 <textarea name = "quiz3" maxlength = 256 required></textarea> </label>
			</li>

			<li>
				<label> Quiz 4 <textarea name = "quiz4" maxlength = 256 required></textarea> </label>
			</li>

			<li>
				<label> Quiz 5 <textarea name = "quiz5" maxlength = 256 required></textarea> </label>
			</li>

			<li>
				<label> Answer <input name = "ans" maxlength = 50 list = "poke_names" required> </label>

				<!--
					"poke_names_datalist.php" contains list of pokemon names to
					make it easier to enter pokemon names as answer
				-->

				<?php include "PHP/poke_names_datalist.php";?>

			</li>

			<li>
				<div class = "error message">
					Enter Quiz carefully, It can be modified in future, if needed.
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Enter">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>