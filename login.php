<?php

	// check if any session exists

	session_start();

	if(!empty($_SESSION))
	{
		// if non empty session found head back to home or index

		header("location: index.php");
	}

	// destroy any session that might get created here

	session_destroy();

	if(isset($_POST['submit']))
	{
		// form has been submitted

		// linking with database

		include "PHP/config.php";

		include "PHP/mysql_sanitize_input.php";

		$email = strtolower(mysql_sanitize_input($link, $_POST['email']));

		$pass = $_POST['password'];

		$salt1 = "$#&^f";
		
		$salt2 = "$@gh^f";

		$password = hash("ripemd128", $salt1 . $pass . $salt2);

		$result = $link -> query("SELECT uid, uname FROM login WHERE email = '$email' AND password = '$password'");

		if($result === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		if($result -> num_rows === 1)
		{
			/*
				an entry is found in login tabel so we start a session and store
				user name, user id, qid of current quiz and if the player can play
				or not in the session to be used through out the user section 
			*/

			include "PHP/start_secure_session.php";

			start_secure_session();

			$arr = $result -> fetch_assoc();

			$_SESSION['USER_NAME'] = $arr['uname'];

			$_SESSION['USER_ID'] = $arr['uid'];

			// calculating current quiz id

			include "PHP/start_date_current_qid.php";

			$_SESSION['QUIZ_ID'] = $current_qid;

			// can we play or not

			include "PHP/can_play.php";

			$_SESSION['can_play'] = can_play($link, $_SESSION['USER_ID'], $_SESSION['QUIZ_ID']);

			$link -> close();

			header("location: user profile.php");	// entering user section
		}
		else
		{
			/*
				no entry is found hence, this login process fails, to indicate this
				we redirect to this page and set the get variable fail

				which is checked to indicate failure
			*/

			$link -> close();

			header("location: login.php?fail=true");
		}
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>login</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

		</style>

	</head>
	
	<body>

		<!--
			We don't keep any return to home button here to discourage
			user from accidentally return to home page, I want login
			to his profile and play
		-->
		
		<header></header>

		<main>
			
		<form method = "post" action = "login.php">
		
		<ul class = "main_box nice_shadow">

			<!-- indicates failed to login -->
			
			<?php if(isset($_GET['fail'])) :?>

				<li>
					<div class = "error message">
						Invalid credentials. Try again.
					</div>
				</li>

			<?php endif; ?>

			<!-- Maxlength is set according to size of uname field in login table -->
			
			<li>
				<label> Email <input type = "email" name = "email" maxlength = 50 required> </label>
			</li>
			
			<li>
				<label> Password (For Profile) <input type = "password" name = "password" maxlength = 10 required> </label>
			</li>

			<li>
				<div class = "error message">
					New around here! <a href = "register.php">Create your profile here</a>
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Open Profile">
			</li>

			<li>
				<span ></span>
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>