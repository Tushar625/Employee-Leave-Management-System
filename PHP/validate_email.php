<?php

	function validate_email($email)
	{
		// testing valid email syntax

		if(!filter_var($email, FILTER_VALIDATE_EMAIL))
		{
			return false;
		}

		// extracting domain name

		$domain = explode('@', $email)[1];

		/*
			check if the domain has any dns record, if it has then it's
			more probable that the email is valid
		*/

		return checkdnsrr($domain);

		/*
			these checks are not enough, to make it water tight we need
			to send a varification email
		*/
	}

?>