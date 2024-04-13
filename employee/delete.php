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

		// deleting leave requests

		$query = "delete from leave_request where eid = $eid and lid = $lid and lrid = $lrid";

		/*
			deletion failure will lead back to emp home
			page (this is very unlikely to happen)
		*/

		if($link -> query($query) === false)
		{
			die("Deletion failure, head back to <a href = 'index.php#navid$nav'> Home </a>");
		}

		$link -> close();

		// redirect to the leave request right before the deleted quiz

		header("location: index.php#navid" . ($nav - 1));
	}
	else
	{
		header("location: index.php");
	}

?>