<?php

	/*
		check if it's valid mg session or not if not redirect
		to index or home page
	*/

	include_once "../PHP/check_mng_session.php";

	if($_SESSION["MANAGER_RANK"] == 1)
	{
		// check if mg1 accessing it, he can't access this page

		header("location: ../index.php");

		exit();
	}

	include_once "../PHP/config.php";

	include_once "../PHP/mysql_sanitize_input.php";

	$mrank = $_SESSION["MANAGER_RANK"];

	if(isset($_GET["lrid"]))
	{
		$lrid = mysql_sanitize_input($link, $_GET['lrid']);

		$consent = ($mrank == 1) ? "mg1_consent" : "mg2_consent";

		$comment = 'A';	// 'A' is stored as manager consent to reflect approval

		/*
			manager1 can enter his consent if mg1_consent and mg2_consent both are null

			manager2 can enter his consent if mg1_consent is not null but mg2_consent is null
		*/

		$mg1_consent = ($mrank == 1) ? "IS NULL" : "IS NOT NULL";

		$query = "UPDATE leave_request SET $consent = '$comment' WHERE lrid = $lrid  AND mg1_consent $mg1_consent AND mg2_consent IS NULL";

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