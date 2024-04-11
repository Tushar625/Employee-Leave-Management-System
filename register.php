<?php

	if(isset($_POST['submit']))
	{
		include "PHP/config.php";

		include "PHP/mysql_sanitize_input.php";

		include "PHP/validate_email.php";

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
				$get = "$get&email_valid=true";
			}
			else
			{
				$get = "email_valid=true";
			}
		}

		// if get is created there is a problem and we reload the file

		if(isset($get))
		{
			$link -> close();

			header("location: register.php?$get");
		}

		/*
			no problem detected, hence we receive rest of the inputs
			and load them into login table
		*/

		$uname = mysql_sanitize_input($link, $_POST['uname']);

		$pass = $_POST['password'];

		$salt1 = "$#&^f";
		
		$salt2 = "$@gh^f";

		$password = hash("ripemd128", $salt1 . $pass . $salt2);
		
		$query = "INSERT INTO login(uname, email, password) VALUES('$uname', '$email', '$password');";

		// fail check

		if($link -> query($query) === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		header("location: register.php?success=true");
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>create_profile</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

		</style>

	</head>
	
	<body>

		<!--
			We don't keep any return to home button here to discourage
			user from accidentally return from registration form, I want
			him to create an account successfully and then login to his
			profile and play
		-->
		
		<header></header>

		<main>
			
		<form method = "post" action = "register.php">
		
		<ul class = "main_box nice_shadow">

			<!-- Indicate successfull Registration creation -->

			<?php if(isset($_GET['success'])) :?>

				<li>
					<div class = "info message">
						Profile created, Want to <a href = "login.php">Log in</a> now?
					</div>
				</li>

			<?php endif; ?>

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<label> User Name <input name = "uname" maxlength = 30 required> </label>
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