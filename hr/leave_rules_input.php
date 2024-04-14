<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_hr_session.php";

	$MIN_DAYS = 1;

	$MAX_DAYS = 365;

	// Empdetails input

	if(isset($_POST['submit']))
	{
		include "../PHP/config.php";

		include "../PHP/mysql_sanitize_input.php";

		// here we use get variables to send error messages while redirecting to itself

		/*
			After each successful submission we redirect to the same form to display
			the error or success message. As the inputs submitted here will be stored
			into the login file, redirection is used to ensure that no resubmission
			error will be generated upon reload or back. (More about this error in the
			documentation)

			While redirecting we use get method to send the error or success message.
		*/

		// check if max days are beyond the range

		if($MIN_DAYS > $_POST['days'] || $_POST['days'] > $MAX_DAYS)
		{
			$get = "?days_valid=false";
		}

		// if get is created there is a problem and we reload the file

		if(!isset($get))
		{
			/*
				no problem detected, hence we receive rest of the inputs
				and load them into leave rules table
			*/

			$type = mysql_sanitize_input($link, $_POST['type']);

			$days = mysql_sanitize_input($link, $_POST['days']);

			$need_doc = mysql_sanitize_input($link, $_POST['need_doc']);
			
			$query = "INSERT INTO leave_rule(type, days, need_doc) VALUES('$type', $days, $need_doc);";

			// fail check

			if($link -> query($query) === false)
			{
				die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
			}

			$get = '?success=true';
		}

		$link -> close();
	}
	else
	{
		$get = '';
	}

	// add selfe redirect once

	/*
		redirecting to itself once to:
		
		clear the past inputs to avoid resubmission of same quiz when
		user returns back to previous input form

		and to avoid form resubmission error on reload (more about this
		in the documentation)
	*/

	include "../PHP/self_redirect_once.php";

	self_redirect_once($get);

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>leave_rules</title>

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
			
		<form method = "post" action = "leave_rules_input.php">
		
		<ul class = "main_box nice_shadow">

			<!-- Indicate successfull Registration creation -->

			<?php if(isset($_GET['success'])) :?>

				<li>
					<div class = "info message">
						Leave rule added, Successfully
					</div>
				</li>

			<?php endif; ?>

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<label> Leave Name <input name = "type" maxlength = 30 required> </label>
			</li>

			<li>
				<label> Max Days <input type = "number" name = "days" min = <?php echo $MIN_DAYS?> max = <?php echo $MAX_DAYS?> required> </label>
			</li>

			<?php if(isset($_GET['days_valid'])) :?>

				<!-- the max days entered is invalid -->

				<li>
					<div class = "error message">
						<?php echo "Max Days minimum: $MIN_DAYS maximum: $MAX_DAYS."?>
					</div>
				</li>

			<?php endif; ?>

			<li>
				<label>
					Need Documnent
					<select name = "need_doc">
						<option value = 0> No </option>
						<option value = 1> Yes </option>
					</select>
				</label>
			</li>

			<li>
				<div class = "error message">
					Enter leave rules carefully, though they can be modified in future.
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Create Leave Rule">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>