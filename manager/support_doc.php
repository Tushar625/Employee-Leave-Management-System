<?php

	include_once "../PHP/check_mng_session.php";

	// display support document for certain leave request

	if(isset($_GET["lrid"]))
	{
		include_once "../php/config.php";

		include_once "../PHP/mysql_sanitize_input.php";

		include_once "../PHP/doc_display.php";

		$lrid = mysql_sanitize_input($link, $_GET["lrid"]);

		$query = "select ftype, support_doc from leave_request where lrid = $lrid";

		// read the file contents corresponding to the id from database

		$result = $link -> query($query);

		if($result === false)
		{
			die("Document not found, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		$row = $result -> fetch_assoc();

		// sending the file content and type to a function to print or display it

		doc_display($row['support_doc'], $row['ftype']);
	}
	else
	{
		header("location: index.php");
	}

?>