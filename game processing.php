<?php

	include "PHP/check_user_session.php";

	include "PHP/config.php";

	// current uid

	$uid = $_SESSION['USER_ID'];

	// getting current qid

	$qid = $_SESSION['QUIZ_ID'];

	$heading = "game.php";	// default location to head for if none of the buttons are clicked

	/*
		increments how many hints are used already
		returns false if no. of hints can't be incremented
	*/

	function increment_hints()
	{
		global $link;

		global $uid;

		global $qid;

		// hints_used can't be incremented beyond 5 (CHECK constraint)

		return $link -> query("UPDATE score SET hints_used = hints_used + 1 WHERE uid = $uid AND qid = $qid;");
	}

	/*
		Its a victory so make solved 1 and set course for "outcome init.php"
	*/

	function victory()
	{
		global $link;

		global $uid;

		global $qid;

		$link -> query("UPDATE score SET solved = 1 WHERE uid = $uid AND qid = $qid;");

		$_SESSION['can_play'] = false;

		global $heading;

		$heading = "outcome init.php?qid=$qid";
	}

	/*
		Its a defeat so make solved 0 and set course for "outcome init.php"
	*/

	function defeat()
	{
		global $link;

		global $uid;

		global $qid;

		$link -> query("UPDATE score SET solved = 0 WHERE uid = $uid AND qid = $qid;");

		$_SESSION['can_play'] = false;

		global $heading;

		$heading = "outcome init.php?qid=$qid";
	}

	// each if - else block selects certain file as final destination

	if(isset($_POST["reveal"]))
	{
		// reveal one more hint if possible

		increment_hints();

		// return to the game

		$heading = "game.php";
	}
	elseif(isset($_POST["confirm"]))
	{
		include "PHP/mysql_sanitize_input.php";

		// matching the answer given with actual answer

		$result = $link -> query("SELECT ans FROM quiz WHERE qid = $qid");

		if(strcasecmp(mysql_sanitize_input($link, $_POST["ans"]), $result -> fetch_assoc()['ans']) === 0)
		{
			// correct answer given (victory)

			victory();
		}
		elseif(!increment_hints())
		{
			// incorrect answer given and all hints exhausted (defeat)

			defeat();
		}
		else
		{
			// incorrect answer given but not all hints exhausted

			$heading = "game.php";
		}
	}
	elseif(isset($_POST["give_up"]))
	{
		// user accepts his defeat

		defeat();
	}

	// now we head for the selected file

	$link -> close();
	
	header("location: $heading");

?>