<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_hr_session.php";

	// Empdetails input

	if(isset($_POST['submit']))
	{
		include "../PHP/config.php";

		include "../PHP/mysql_sanitize_input.php";

		include "../PHP/validate_email.php";

		include "../PHP/form_input_check.php";

		// here we use get variables to send error messages while redirecting to itself

		/*
			After each successful submission we redirect to the same form to display
			the error or success message. As the inputs submitted here will be stored
			into the login file, redirection is used to ensure that no resubmission
			error will be generated upon reload or back. (More about this error in the
			documentation)

			While redirecting we use get method to send the error or success message.
		*/

		// ------------- Error Detection -------------

		// >>>> missing input parameter check

		if(elements_exist($_POST, ['name', 'email', 'phone', 'ranks', 'password', 'password_reenter']))
		{
			// >>>> name check

			/* nothimg to check yet */

			// >>>> password check

			// password length check

			if(max_length_check($_POST['password'], 10) == false)
			{
				$get = url_parameters($get, "pass_valid=0");
			}
			elseif($_POST['password'] != $_POST['password_reenter'])
			{
				// two password are different
				
				$get = url_parameters($get, "pass_match=0");
			}

			// >>>> email check

			// check if the email is new and valid or not

			$email = strtolower(mysql_sanitize_input($link, $_POST['email']));

			$result = $link -> query("SELECT * FROM employee WHERE email = '$email'");

			if($result -> num_rows > 0)
			{
				// the email is already used

				$get = url_parameters($get, "email_used=1");
			}
			elseif(!validate_email($email))
			{
				// the email is not used but not valid

				$get = url_parameters($get, "email_valid=0");
			}

			// >>>> phone check

			// check if the phone no. is valid or not

			if(length_check($_POST['phone'], 10) == false)
			{
				// the phone no. is not valid

				$get = url_parameters($get, "phone_valid=0");
			}
		}
		else
		{
			$get = "input_missing=1";
		}

		// if get is created there is a problem and we reload the file

		if(!isset($get))
		{
			/*
				no problem detected, hence we receive rest of the inputs
				and load them into login table
			*/

			$name = mysql_sanitize_input($link, $_POST['name']);

			$phone = mysql_sanitize_input($link, $_POST['phone']);

			$ranks = mysql_sanitize_input($link, $_POST['ranks']);

			$pass = $_POST['password'];

			$password = password_hash($pass, PASSWORD_DEFAULT);
			
			$query = "INSERT INTO employee(name, email, phone, ranks) VALUES('$name', '$email', $phone, $ranks);";

			// fail check

			if($link -> query($query) === false)
			{
				die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
			}

			// collecting his eid

			$eid = $link -> query("SELECT eid FROM employee WHERE email = '$email'") -> fetch_assoc()['eid'];

			// storing password in login table

			$query = "INSERT INTO login(eid, password) VALUES($eid, '$password');";

			// fail check

			if($link -> query($query) === false)
			{
				die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
			}

			$get = "success=1";
		}

		$link -> close();

		$get = "?$get";
	}
	else
	{
		$get = '';
	}

	// add self redirect once

	/*
		redirecting to itself once to:
		
		clear the past inputs to avoid resubmission of same data when
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

		<title>emp_input</title>

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
			
		<form method = "post" action = "emp_input.php">
		
		<ul class = "main_box nice_shadow">

			<!-- Indicate successfull Registration creation -->

			<?php if(isset($_GET['success'])) :?>

				<li>
					<div class = "info message">
						Profile created, Successfully
					</div>
				</li>

			<?php endif; ?>

			<?php if(isset($_GET['input_missing'])) :?>

				<li>
					<div class = "error message">
						Missing inputs ...
					</div>
				</li>

			<?php endif; ?>

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<label> Emp Name <input name = "name" maxlength = 30 placeholder = "30 letters max" required> </label>
			</li>

			<li>
				<label> Email <input type = "email" name = "email" maxlength = 50 placeholder = "50 characters max" required> </label>
			</li>

			<?php if(isset($_GET['email_used']) || isset($_GET['email_valid'])) :?>

				<!-- the email entered is either invalid or already used -->

				<li>
					<div class = "error message">
						Please use another Email.
					</div>
				</li>

			<?php endif; ?>

			<li>
				<label> Phone <input type = "tel" name = "phone" maxlength = 10 placeholder = "10 characters max" required> </label>
			</li>

			<?php if(isset($_GET['phone_valid'])) :?>

				<!-- the phone no. entered is invalid -->

				<li>
					<div class = "error message">
						Invalid Phone No.
					</div>
				</li>

			<?php endif; ?>

			<li>
				<label>
					Rank
					<select name = "ranks">
						<option value = 0> Employee </option>
						<option value = 1> Manager1 </option>
						<option value = 2> Manager2 </option>
						<option value = 3> HR </option>
					</select>
				</label>
			</li>
			
			<li>
				<label> Password (For Profile) <input type = "password" name = "password" maxlength = 10 placeholder = "10 characters max" required> </label>
			</li>

			<?php if(isset($_GET['pass_valid'])) :?>

				<!-- the password entered is invalid -->

				<li>
					<div class = "error message">
						Invalid password.
					</div>
				</li>

			<?php endif; ?>

			<li>
				<label> Re-enter Password <input type = "password" name = "password_reenter" maxlength = 10 placeholder = "10 characters max" required> </label>
			</li>

			<!-- Original and reentered passwords must match -->

			<?php if(isset($_GET['pass_match'])) :?>

				<li>
					<div class = "error message">
						Reentered Password doesn't match
					</div>
				</li>

			<?php endif; ?>

			<li>
				<div class = "error message">
					Enter Password carefully, It can't be modified in future.
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Create Profile">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>