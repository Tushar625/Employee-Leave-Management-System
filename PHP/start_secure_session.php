<?php

	function start_secure_session()
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
			into the session, so that another person from another device cannot
			enter the session
		*/

		$_SESSION['validate'] = hash("ripemd128", $_SERVER["REMOTE_ADDR"] . $_SERVER['HTTP_USER_AGENT']);
	}

?>