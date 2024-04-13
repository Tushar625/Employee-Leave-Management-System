<?php

	include_once "../PHP/check_emp_session.php";

	// display support document for certain leave request

	if(isset($_GET["lrid"]) && isset($_GET["lid"]))
	{
		include_once "../php/config.php";

		include_once "../PHP/mysql_sanitize_input.php";

		include_once "../PHP/doc_display.php";

		// DELETE request from emp or leave display

		$eid = $_SESSION['EMPLOYEE_ID'];

		$lrid = mysql_sanitize_input($link, $_GET["lrid"]);

		$lid = mysql_sanitize_input($link, $_GET["lid"]);

		$query = "select ftype, support_doc from leave_request where eid = $eid and lid = $lid and lrid = $lrid";

		// read the file contents corresponding to the id from database

		$result = $link -> query($query);

		if($result === false)
		{
			die("Document not found, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		$row = $result -> fetch_assoc();

		doc_display($row['support_doc'], $row['ftype']);
	}
	else
	{
		header("location: index.php");
	}

?>