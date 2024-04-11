<?php
	function mysql_sanitize_input($link, $string)
	{
		return trim(htmlentities($link -> real_escape_string($string)));
	}
?>