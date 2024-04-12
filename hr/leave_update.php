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

		$lid = mysql_sanitize_input($link, $_POST["lid"]);

		// check if max days are beyond the range

		if($MIN_DAYS > $_POST['days'] || $_POST['days'] > $MAX_DAYS)
		{
			die("Update failure (days beyond the range), head back to <a href = 'view.php?type=0#id$lid'> Display all Leave Rules </a>");
		}

		$name = mysql_sanitize_input($link, $_POST['name']);
		$days = mysql_sanitize_input($link, $_POST['days']);
		$need_doc = mysql_sanitize_input($link, $_POST['need_doc']);

		$query = "UPDATE leave_rule SET name = '$name', days = $days, need_doc = $need_doc WHERE lid = $lid;";

		if($link -> query($query) === false)
		{
			/*
				leave update failure will lead back to emp Display page (this
				is very unlikely to happen).
			*/
			
			die("Update failure, head back to <a href = 'view.php?type=0#id$lid'> Display all Leave Rules </a>");
		}

		$link -> close();

		header("location: view.php?type=0#id$lid");
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

			die("Form initializing failure, head back to <a href = 'view.php?type=0$id'> Display all Leave Rules </a>");
		}
		else
		{
			$lid = $_SESSION["tuple"]['lid'];
			$name = $_SESSION["tuple"]['name'];
			$days = $_SESSION["tuple"]['days'];
			$need_doc = $_SESSION["tuple"]['need_doc'];

			unset($_SESSION["tuple"]);
		}
	}
?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>leave_update</title>

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
			
		<form method = "post" action = "leave_update.php">

		<input type = 'hidden' name = "lid" value = <?php echo $lid;?>>
		
		<ul class = "main_box nice_shadow">

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<label> Leave Name <input name = "name" maxlength = 30 value = "<?php echo $name;?>" required> </label>
			</li>

			<li>
				<label> Max Days <input type = "number" name = "days" min = <?php echo $MIN_DAYS?> max = <?php echo $MAX_DAYS?> value = "<?php echo $days;?>" required> </label>
			</li>

			<li>
				<label>
					Need Documnent
					<select name = "need_doc">
						<option value = 0 <?php if($need_doc == 0) echo 'selected';?>> No </option>
						<option value = 1 <?php if($need_doc == 1) echo 'selected';?>> Yes </option>
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