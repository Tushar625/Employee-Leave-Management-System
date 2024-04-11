<?php

	// disable the runtime exceptions due to failed queries

	mysqli_report(MYSQLI_REPORT_OFF);

	// establishing link to the server

	$link = @new mysqli("localhost", "root", "td9940433@", "ELMS");

	if($link -> connect_error)
	{
		die("Failure ($link -> connect_error)");
	}

?>