<?php

	/*
		check if it's valid emp session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_emp_session.php";

	if(isset($_GET["lrid"]) && isset($_GET["lid"]) && isset($_GET["navid"]))
	{
		include "../PHP/config.php";	// connect to database

		include "../PHP/mysql_sanitize_input.php";

		// DELETE request from emp index

		$eid = $_SESSION['EMPLOYEE_ID'];

		$lrid = mysql_sanitize_input($link, $_GET["lrid"]);

		$lid = mysql_sanitize_input($link, $_GET["lid"]);

		$nav = $_GET["navid"];

		// deleting leave requests, only ones not seen by top manager

		$query = "delete from leave_request where eid = $eid and lid = $lid and lrid = $lrid and mg2_consent is null";

		/*
			deletion failure will cause 404 error
			(this is very unlikely to happen)
		*/

		if($link -> query($query) == false)
		{
			// Deletion failure

			header("HTTP/1.0 404 Not Found", true, 404);

			exit();
		}

		$link -> close();

		// redirect to the leave request right before the deleted quiz in index page

		header("location: index.php#navid" . ($nav - 1));
	}
	else
	{
		// no URL parameters provided
		
		header("location: index.php");
	}

?>