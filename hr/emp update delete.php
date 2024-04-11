<?php

	/*
		check if it's valid hr session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_hr_session.php";

	include "../PHP/config.php";	// connect to database

	if(isset($_POST["delete_request"]) && isset($_POST["eid"]))
	{
		// DELETE request from emp display

		$eid = $_POST["eid"];

		$nav = $_POST["nav"];

		// deleting emp

		$query = "delete from employee where eid = $eid;";

		/*
			Quiz deletion failure will lead back to Quiz Display
			page (this is very unlikely to happen)

			note that in case of failure we don't set auto increment
			value because the last quiz remains intact
		*/

		if($link -> query($query) === false)
		{
			die("Deletion failure, head back to <a href = 'view_employees.php#emp$nav'> Display all Employees </a>");
		}

		$link -> close();

		// redirect to the quiz right before the deleted quiz

		header("location: view_employees.php#emp" . ($nav - 1));
	}
	elseif(isset($_POST["update_request"]) && isset($_POST["eid"]))
	{
		// update request from emp display

		$eid = $_POST["eid"];

		// extracting the tuple

		$result = $link -> query("SELECT * FROM employee WHERE eid = $eid;");

		if($result === false || $result -> num_rows == 0)
		{
			/*
				Query execution failure or no tuples found, this leads back to emp Display
				page (this is very unlikely to happen)
			*/

			die("Failed to load the employee details, head back to <a href = 'view_employees.php#emp$eid'> Display all Employees </a>");
		}

		/*
			loading a tuple as an associative array into session to
			be accessed from emp update form, to display current values
			of the emp
		*/

		$_SESSION["tuple"] = $result -> fetch_assoc();

		$link -> close();

		/*
			redirect to the form to accept inputs, also send the eid of
			the quiz to be updated to that form as get value, so that if
			anything goes wrong there we can head directly back to the
			emp details in emp display
		*/

		header("location: emp_update_form.php?eid=$eid");
	}
	elseif(isset($_POST["update"]))
	{
		// update request from emp update form

		/*
			Inputs will be sanitized and trimmed before adding them to SQL
			query to prevent SQL or HTML injection (sanitization)
		*/

		include "../PHP/mysql_sanitize_input.php";
		
		$eid = $_POST['eid'];

		// checking the email
		
		$email = strtolower(mysql_sanitize_input($link, $_POST['email']));

		$result = $link -> query("SELECT * FROM employee WHERE email = '$email' AND eid <> $eid");

		if($result -> num_rows > 0)
		{
			// the email is already used by anyone else

			die("Update failure (use different email), head back to <a href = 'view_employees.php#emp$eid'> Display all Employees </a>");
		}

		// check if the phone no. is valid or not

		if(strlen($_POST['phone']) != 10)
		{
			// invalid phone number

			die("Update failure (invalid phone number), head back to <a href = 'view_employees.php#emp$eid'> Display all Employees </a>");
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
			
			die("Update failure, head back to <a href = 'view_employees.php#emp$eid'> Display all Employees </a>");
		}

		$link -> close();

		header("location: view_employees.php#emp$eid");
	}
	else
	{
		// I don't know who wants to update, i.e., this file has been accessed illegally

		$link -> close();

		header("location: view_employees.php");
	}

?>