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

		// here we use get variables to send error messages while redirecting to itself

		/*
			After each successful submission we redirect to the same form to display
			the error or success message. As the inputs submitted here will be stored
			into the login file, redirection is used to ensure that no resubmission
			error will be generated upon reload or back. (More about this error in the
			documentation)

			While redirecting we use get method to send the error or success message.
		*/

		// check if two password are different

		if($_POST['password'] != $_POST['password_reenter'])
		{
			$get = "pass_match=false";
		}

		// check if the email is new and valid or not

		$email = strtolower(mysql_sanitize_input($link, $_POST['email']));

		$result = $link -> query("SELECT * FROM login WHERE email = '$email'");

		if($result -> num_rows > 0)
		{
			// the email is already used

			if(isset($get))
			{
				$get = "$get&email_used=true";
			}
			else
			{
				$get = "email_used=true";
			}
		}
		elseif(!validate_email($email))
		{
			// the email is not used but not valid

			if(isset($get))
			{
				$get = "$get&email_valid=false";
			}
			else
			{
				$get = "email_valid=false";
			}
		}

		// check if the phone no. is valid or not

		if(strlen($_POST['phone']) != 10)
		{
			// the phone no. is not valid

			if(isset($get))
			{
				$get = "$get&phone_valid=false";
			}
			else
			{
				$get = "phone_valid=false";
			}
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

			$salt1 = "$#&^f";
			
			$salt2 = "$@gh^f";

			$password = hash("ripemd128", $salt1 . $pass . $salt2);
			
			$query = "INSERT INTO employee(name, email, phone, ranks) VALUES('$name', '$email', $phone, $ranks);";

			// fail check

			if($link -> query($query) === false)
			{
				die("Form submission failure, head back to <a href = '../index.php'> Home </a>");
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

			$get = "success=true";
		}

		$link -> close();

		$get = "?$get";

		// header("location: emp_input.php?");
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

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<label> Emp Name <input name = "name" maxlength = 30 required> </label>
			</li>

			<li>
				<label> Email <input type = "email" name = "email" maxlength = 50 required> </label>
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
				<label> Phone <input type = "tel" name = "phone" maxlength = 10 required> </label>
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
				<label> Password (For Profile) <input type = "password" name = "password" maxlength = 10 required> </label>
			</li>

			<li>
				<label> Reenter Password <input type = "password" name = "password_reenter" maxlength = 10 required> </label>
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