<?php

	include "PHP/check_user_session.php";

	include "PHP/config.php";

	/*
		here we need three data, qid (to get pokemon name), hints_used and score

		we store them into session and outcome.php uses them to display its content

		this file can receive qid via get,
		
		the rest, we get them from the database. 

		this is done to prevent misuse of outcome page.
	*/

	$uid = $_SESSION['USER_ID'];

	$qid = '';

	if(isset($_GET['qid']))
	{
		$qid = $_GET['qid'];

		// from qid we get pokemon name

		$result = $link -> query("SELECT ans FROM quiz WHERE qid = $qid;");

		if($result -> num_rows == 0)
		{
			// no entry with this qid is found in quiz table

			die("Unknown error occurred, head to <a href = 'detailed history.php#quiz$qid'>History</a>");
		}

		$_SESSION['outcome']['pname'] = strtolower($result -> fetch_assoc()['ans']);
	}
	else
	{
		/*
			no qid is sent to this file hence we can't do anything
			but return to detailed history page
		*/

		$link -> close();

		header("location: detailed history.php");
	}
	
	// from qid and uid we get hints_used

	$result = $link -> query("SELECT hints_used, solved FROM score WHERE uid = $uid AND qid = $qid;");

	if($result -> num_rows == 0)
	{
		// no entry with this uid and qid is found in score table

		unset($_SESSION['outcome']);	// session variable outcome is not necessary by now

		die("Unknown error occurred, head to <a href = 'detailed history.php#quiz$qid'>History</a>");
	}

	$tuple = $result -> fetch_assoc();

	$_SESSION['outcome']['hints_used'] = $tuple['hints_used'];

	$_SESSION['outcome']['solved'] = $tuple['solved'];

	$link -> close();

	header("location: outcome.php?qid=$qid");	// sending qid to fecilitate return to exact box in history

?>