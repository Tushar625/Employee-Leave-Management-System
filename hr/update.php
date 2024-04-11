<?php

	/*
		check if it's valid hr session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_hr_session.php";

	if(isset($_GET["id"]) && isset($_GET["type"]))
	{
		include "../PHP/config.php";	// connect to database

		include "../PHP/mysql_sanitize_input.php";

		// update request from emp display

		$id = mysql_sanitize_input($link, $_GET["id"]);

		$type = $_GET["type"];

		// extracting the tuple

		$query = ($type == true) ? "SELECT * FROM employee WHERE eid = $id" : "SELECT * FROM leave_rule WHERE lid = $id";

		$result = $link -> query($query);

		if($result === false || $result -> num_rows == 0)
		{
			/*
				Query execution failure or no tuples found, this leads back to emp Display
				page (this is very unlikely to happen)
			*/

			die("Failed to load the records, head back to <a href = 'view.php?type=$type#navid$nav'> Display </a>");
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

		if($type == true)
		{
			header("location: emp_update.php?id=$id");
		}
		else
		{
			header("location: leave_update.php?id=$id");
		}
	}
?>