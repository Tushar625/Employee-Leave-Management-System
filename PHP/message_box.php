<?php

	// uses message.php to display an error message along with a OK button (attached to a url)

	function message_box($msg, $url, $error)
	{
		$_SESSION['msg'] = $msg;

		$_SESSION['url'] = $url;

		$_SESSION['error'] = $error;

		header("location: ../message.php");

		exit(0);
	}

?>