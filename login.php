<?php

	// check if any session exists and destroy it

	include_once "PHP/check_destroy_secure_session.php";

	session_start();

	destroy_session_and_data();	// destroy any existing session

	if(isset($_POST['submit']))
	{
		// form has been submitted

		// linking with database

		include_once "PHP/config.php";

		include_once "PHP/mysql_sanitize_input.php";

		$email = strtolower(mysql_sanitize_input($link, $_POST['email']));

		$pass = $_POST['password'];

		$rank = mysql_sanitize_input($link, $_POST['ranks']);

		if($rank == 0)
		{
			// wants to login as an employee (every employee is allowed)

			$query = "select eid, name, ranks, password from employee natural join login WHERE email = '$email'";
		}
		else
		{
			// wants to login with his own rank (a manager can't enter as HR and vice versa)

			$query = "select eid, name, ranks, password from employee natural join login WHERE email = '$email' AND ranks = $rank";
		}

		$result = $link -> query($query);

		if($result === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		// getting first record

		$arr = $result -> fetch_assoc();

		if($result -> num_rows === 1 && password_verify($pass, $arr['password']))
		{
			/*
				an entry is found in employee and login tabel so we start a session and store
				user name, user id 
			*/

			include_once "PHP/start_secure_session.php";

			include_once "PHP/emp_ranking_system.php";

			start_secure_session();

			// getting name of the directory where the section for this user is stored

			$user_section = get_rank_section($rank);

			$user = strtoupper($user_section);

			$_SESSION[$user . "_RANK"] = $arr['ranks'];

			$_SESSION[$user . "_NAME"] = $arr['name'];

			$_SESSION[$user . "_ID"] = $arr['eid'];

			$link -> close();

			header("location: " . $user_section . "/index.php");	// entering user section
		}
		else
		{
			/*
				no entry is found hence with proper password, this login process fails, to indicate this
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

			label
			{
				margin-top: -0.5em;
			}

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
				<input class = "button" type = "submit" name = "submit" value = "Login">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>