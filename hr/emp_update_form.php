<?php

	/*
		check if it's valid hr session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_hr_session.php";

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

		$id = (isset($_GET["eid"])) ? "#emp" . $_GET["eid"] : "";

		die("Form initializing failure, head back to <a href = 'view_employees.php$id'> Display all Employees </a>");
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
		
		<header>

			<?php include "header.php";?>

		</header>

		<main>
			
		<form method = "post" action = "emp update delete.php">

		<input type = 'hidden' name = "eid" value = <?php echo $eid;?>>
		
		<ul class = "main_box nice_shadow">

			<li>
				<div class = "info message">
					Update Employee Details
				</div>
			</li>

			<!-- Maxlength is set according to size of the fields in quiz table -->

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
				<input class = "button" type = "submit" name = "update" value = "Update">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>