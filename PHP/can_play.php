<?php

	/*
		user can play because,

			either,
				he doesn't have an entry in the score table
			or
				he has an account with solved = -1
	*/

	function can_play($link, $uid, $qid)
	{
		$result = $link -> query("SELECT * FROM score WHERE solved <> -1 AND qid = $qid AND uid = $uid;");

		// if there is any record of this uid and qid where solved is 1 or 0 we can't play

		return $result -> num_rows == 0;
	}

?>