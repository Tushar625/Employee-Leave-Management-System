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

		$rank = mysql_sanitize_input($link, $_POST['ranks']);

		$result = $link -> query("select eid, name from employee natural join login WHERE email = '$email' AND password = '$password' AND ranks = $rank;");

		if($result === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		if($result -> num_rows === 1)
		{
			/*
				an entry is found in employee and login tabel so we start a session and store
				user name, user id 
			*/

			include "PHP/start_secure_session.php";

			include "PHP/emp_ranking_system.php";

			start_secure_session();

			$arr = $result -> fetch_assoc();

			$user = strtoupper(get_rank($rank));

			$_SESSION[$user . "_NAME"] = $arr['name'];

			$_SESSION[$user . "_ID"] = $arr['eid'];

			$link -> close();

			$user = strtolower($user);

			header("location: " . $user . "_index.php");	// entering user section
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
				<div class = "error message">
					Enter your credentials
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Login">
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