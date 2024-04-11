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

		$eid = mysql_sanitize_input($link, $_POST["eid"]);

		// check if the email is new and valid or not

		$email = strtolower(mysql_sanitize_input($link, $_POST['email']));

		$result = $link -> query("SELECT * FROM employee WHERE email = '$email' AND eid <> $eid");

		if($result -> num_rows > 0 || !validate_email($email))
		{
			// the email is already used

			die("Update failure (use different email), head back to <a href = 'view.php?type=1#id$eid'> Display all Employees </a>");
		}

		// check if the phone no. is valid or not

		if(strlen($_POST['phone']) != 10)
		{
			// the phone no. is not valid

			die("Update failure (invalid phone number), head back to <a href = 'view.php?type=1#id$eid'> Display all Employees </a>");
		}

		$name = mysql_sanitize_input($link, $_POST['name']);
		$phone = mysql_sanitize_input($link, $_POST['phone']);
		$ranks = mysql_sanitize_input($link, $_POST['ranks']);

		$query = "UPDATE employee SET name = '$name', email = '$email', phone = $phone, ranks = $ranks WHERE eid = $eid;";

		if($link -> query($query) === false)
		{
			/*
				emp update failure will lead back to emp Display page (this
				is very unlikely to happen).
			*/
			
			die("Update failure, head back to <a href = 'view.php?type=1#id$eid'> Display all Employees </a>");
		}

		$link -> close();

		header("location: view.php?type=1#id$eid");
	}
	else
	{
		/*
			here we check for the session variable we created in "emp
			update delete.php"

			If that thing doesn't exist we return to emp display page
			at certain emp detail

			If that thing exists we store its data into local variables
			and delete the session variable so that it can't be used
			again to reload this form
		*/

		if(!isset($_SESSION['tuple']))
		{
			/*
				this file receives qid of the quiz to be updated from
				"quiz update delete.php" so that we can return to proper
				quiz in quiz display page

				if no such get value is received we simply go back to the
				top of quiz display page
			*/
			
			// invalid access

			$id = (isset($_GET["id"])) ? "#id" . $_GET["id"] : "";

			die("Form initializing failure, head back to <a href = 'view.php?type=1$id'> Display all Employees </a>");
		}
		else
		{
			$eid = $_SESSION["tuple"]['eid'];
			$name = $_SESSION["tuple"]['name'];
			$email = $_SESSION["tuple"]['email'];
			$phone = $_SESSION["tuple"]['phone'];
			$ranks = $_SESSION["tuple"]['ranks'];

			unset($_SESSION["tuple"]);
		}
	}
?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>emp_update</title>

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
			
		<form method = "post" action = "emp_update.php">

		<input type = 'hidden' name = "eid" value = <?php echo $eid;?>>
		
		<ul class = "main_box nice_shadow">

			<li>
				<div class = "info message">
					Update Employee Details
				</div>
			</li>

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<label> Emp Name <input name = "name" maxlength = 30 value = "<?php echo $name;?>" required> </label>
			</li>

			<li>
				<label> Email <input type = "email" name = "email" maxlength = 50 value = "<?php echo $email;?>" required> </label>
			</li>

			<li>
				<label> Phone <input type = "tel" name = "phone" maxlength = 10 value = "<?php echo $phone;?>" required> </label>
			</li>

			<li>
				<label>
					Rank
					<select name = "ranks">
						<option value = 0 <?php if($ranks == 0) echo 'selected';?>> Employee </option>
						<option value = 1 <?php if($ranks == 1) echo 'selected';?>> Manager1 </option>
						<option value = 2 <?php if($ranks == 2) echo 'selected';?>> Manager2 </option>
						<option value = 3 <?php if($ranks == 3) echo 'selected';?>> HR </option>
					</select>
				</label>
			</li>
			
			<li>
				<div class = "error message">
					Invalid Inputs will Fail Update Process
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Update">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>