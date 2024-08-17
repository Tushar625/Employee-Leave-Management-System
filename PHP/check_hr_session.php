<?php

	// check if current session is a valid admin session else open index.php

	include_once "check_destroy_secure_session.php";

	if(!(check_secure_session() && isset($_SESSION['HR_NAME'])))
	{
		// invalid admin session

		header("location: ../index.php");

		exit();
	}

?>