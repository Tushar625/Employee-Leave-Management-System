<?php

	/*
		check if it's valid hr session or not if not redirect
		to index or home page (with updated error management system)
	*/

	include_once "../PHP/check_hr_session.php";

	include_once "../PHP/message_box.php";

	// Empdetails input

	if(isset($_POST['submit']))
	{
		include_once "../PHP/config.php";
		
		include_once "../PHP/mysql_sanitize_input.php";

		include_once "../PHP/validate_email.php";

		$eid = mysql_sanitize_input($link, $_POST["eid"]);

		// check if the email is new and valid or not

		$email = strtolower(mysql_sanitize_input($link, $_POST['email']));

		$result = $link -> query("SELECT * FROM employee WHERE email = '$email' AND eid <> $eid");

		if($result -> num_rows > 0 || !validate_email($email))
		{
			// the email is already used

			// here an error message can be displayed along with a OK button (attached to a url)

			message_box("Update failure (use different email)", "hr/update.php?id=$eid&type=1", true);
		}

		// check if the phone no. is valid or not

		if(strlen($_POST['phone']) != 10)
		{
			// the phone no. is not valid

			message_box("Update failure (invalid phone number)", "hr/update.php?id=$eid&type=1", true);
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

			message_box("Update failure (unknown reason), click <b>OK</b> to head back to <b>Display all Employees</b>", "hr/view.php?type=1#id$eid", true);
		}

		$link -> close();

		header("location: view.php?type=1#id$eid");
	}
	else
	{
		/*
			here we check for the session variable we created in "
			update.php"

			If that thing doesn't exist we return to emp display page
			at certain emp detail

			If that thing exists we store its data into local variables
			and delete the session variable so that it can't be used
			again to reload this form
		*/

		if(!isset($_SESSION['tuple']))
		{
			/*
				this file receives eid of the employee to be updated from
				"update php" so that we can return to proper
				emp in emp display page

				if no such get value is received we simply go back to the
				top of emp display page
			*/
			
			// invalid access

			$id = (isset($_GET["id"])) ? "#id" . $_GET["id"] : "";

			message_box("Form initializing failure, click <b>OK</b> to head back to <b>Display all Employees</b>", "hr/view.php?type=1#id$eid", true);
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
		
		<header>
			<?php include_once "header.php";?>
		</header>

		<main>
			
		<form method = "post" action = "emp_update.php">

		<input type = 'hidden' name = "eid" value = <?php echo $eid;?>>
		
		<ul class = "main_box nice_shadow">

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