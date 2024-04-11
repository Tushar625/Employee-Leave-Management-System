<?php

	/*
		this function creates a new session variable, redirects to
		the same file once, and deletes that variable.

		this is used to clear previous inputs to the form while going
		back to an already submitted copy of that form, thus preventing
		possibility of resubmission of same data.
	*/

	function self_redirect_once($get_variables = '')
	{
		if(!isset($_SESSION['redirect_once']))
		{
			$_SESSION['redirect_once'] = true;

			header("location: " . $_SERVER['SCRIPT_NAME'] . $get_variables);

			exit();
		}
		else
		{
			unset($_SESSION['redirect_once']);
		}
	}

?>