<?php

	/*
		check if it's valid hr session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_hr_session.php";

	if(isset($_GET["id"]) && isset($_GET["navid"]) && isset($_GET["type"]))
	{
		include "../PHP/config.php";	// connect to database

		include "../PHP/mysql_sanitize_input.php";

		// DELETE request from emp or leave display

		$id = mysql_sanitize_input($link, $_GET["id"]);

		$nav = $_GET["navid"];

		$type = $_GET["type"];

		// deleting emp or leave

		$query = ($type == true) ? "delete from employee where eid = $id" : "delete from leave_rule where lid = $id";

		/*
			Quiz deletion failure will lead back to Quiz Display
			page (this is very unlikely to happen)

			note that in case of failure we don't set auto increment
			value because the last quiz remains intact
		*/

		if($link -> query($query) === false)
		{
			die("Deletion failure, head back to <a href = 'view.php?type=$type#navid$nav'> Display </a>");
		}

		$link -> close();

		// redirect to the quiz right before the deleted quiz

		header("location: view.php?type=$type#navid" . ($nav - 1));
	}
	else
	{
		header("location: index.php");
	}

?>