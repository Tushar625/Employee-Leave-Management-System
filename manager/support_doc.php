<?php

	include_once "../PHP/check_mng_session.php";

	// display support document for certain leave request

	if(isset($_GET["lrid"]))
	{
		include_once "../php/config.php";

		include_once "../PHP/mysql_sanitize_input.php";

		include_once "../PHP/doc_display.php";

		$lrid = mysql_sanitize_input($link, $_GET["lrid"]);

		$query = "select ftype, support_doc from leave_request where lrid = $lrid and support_doc is not null and ftype is not null";

		// read the file contents corresponding to the id from database

		$result = $link -> query($query);

		if($result === false || $result -> num_rows == 0)
		{
			// Document not found hence page not found error
			
			header("HTTP/1.0 404 Not Found", true, 404);

			exit();
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