<?php

	/*
		perfect function to destroy a session and all its data
		including the cookie generated for it
	*/

	function destroy_session_and_data()
	{
		$_SESSION = [];

		setcookie(session_name(), "", time() - 3000000, '/');

		@session_destroy();	// disable warning for nonexistence session
	}

	/*
		returns false if the session is not secure
	*/

	function check_secure_session()
	{
		session_start();

		/*
			prevent session fixation by changing session id each time a
			session starts to make sure that attacker cannot use previously
			used session id to enter an unclosed session
		*/

		session_regenerate_id();

		/*
			preventing session hijacking by hashing the combination of client
			IP address and client's browser user agent string and storing it
			into the session (here we check for it), so that another person
			from another device cannot enter the session
		*/

		if(isset($_SESSION['validate']) == false || $_SESSION['validate'] != hash("ripemd128", $_SERVER["REMOTE_ADDR"] . $_SERVER['HTTP_USER_AGENT']))
		{
			destroy_session_and_data();

			return false;
		}

		return true;
	}

?>