<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_mng_session.php";

	if($_SESSION["MANAGER_RANK"] == 1)
	{
		// check if mg1 accessing it, he can't access it

		header("location: ../index.php");

		exit();
	}

	include "../PHP/config.php";

	include "../PHP/mysql_sanitize_input.php";

	$mrank = $_SESSION["MANAGER_RANK"];

	if(isset($_GET["lrid"]))
	{
		$lrid = mysql_sanitize_input($link, $_GET['lrid']);

		$consent = ($mrank == 1) ? "mg1_consent" : "mg2_consent";

		$comment = 'A';

		$query = "UPDATE leave_request SET $consent = '$comment' WHERE lrid = $lrid";

		// fail check

		if($link -> query($query) === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		header("location: index.php");
	}
	else
	{
		header("location: index.php");
	}

?>