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

		$aname = mysql_sanitize_input($link, $_POST['aname']);

		$apass = $_POST['password'];	// no need to sanitize the pass word as we are hashing it

		$salt1 = "$#&^f";
		
		$salt2 = "$@gh^f";

		$password = hash("ripemd128", $salt1 . $apass . $salt2);

		$result = $link -> query("SELECT * FROM admin WHERE aid = '$aname' AND password = '$password'");

		if($result === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		if($result -> num_rows === 1)
		{
			/*
				an entry is found in login tabel so we start a session and store
				admin name in the session to be used through out the admin section 
			*/

			include "PHP/start_secure_session.php";

			start_secure_session();

			$_SESSION['ADMIN_NAME'] = $aname;

			header("location: admin index.php");
		}
		else
		{
			/*
				no entry is found hence, this login process fails, to indicate this
				we redirect to this page and set the get variable fail

				which is checked to indicate failure
			*/

			header("location: admin login.php?fail=true");
		}
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>admin_login</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

		</style>

	</head>
	
	<body>

		<!--
			We don't keep any return to home button here to make this page
			less user friendly, to keep ordinary users out
		-->
		
		<header></header>

		<main>
			
		<form method = "post" action = "admin login.php">

		<!-- Maxlength is set according to size of uname field in login table -->
		
		<ul class = "main_box nice_shadow">
			
			<li>
				<label> Admin Name <input name = "aname" maxlength = 10 required> </label>
			</li>
			
			<li>
				<label> Password <input type = "password" name = "password" maxlength = 10 required> </label>
			</li>

			<!-- Very little error message is shown to make this page not user friendly -->

			<!-- GET variable fail is used to indicate failure -->

			<?php if(isset($_GET['fail'])) :?>

			<li>
				<div class = "error message">
					Access denied.
				</div>
			</li>

			<?php endif; ?>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Login">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>