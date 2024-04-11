<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "PHP/check_admin_session.php";

	include "PHP/start_date_current_qid.php";

	include "PHP/config.php";	// connect to database

	if(isset($_POST["delete_last"]) && isset($_POST["qid"]))
	{
		// DELETE (last quiz) request from quiz display

		$qid = $_POST["qid"];

		// deleting last quiz

		$query = "delete from quiz order by qid desc limit 1;";

		/*
			Quiz deletion failure will lead back to Quiz Display
			page (this is very unlikely to happen)

			note that in case of failure we don't set auto increment
			value because the last quiz remains intact
		*/

		if($link -> query($query) === false)
		{
			die("Deletion failure, head back to <a href = 'quiz display.php#quiz$qid'> Quiz Display </a>");
		}

		/*
			After deleting the last quiz we set qid of last quiz as
			auto increment value of qid, so that the quiz entered next
			gets the qid of last deleted quiz
		*/

		$link -> query("ALTER TABLE quiz AUTO_INCREMENT = $qid;");

		$link -> close();

		// redirect to the quiz right before the deleted quiz

		header("location: quiz display.php#quiz" . ($qid - 1));
	}
	elseif(isset($_POST["update_request"]) && isset($_POST["qid"]) && $_POST["qid"] >= $current_qid)	// getting qid of the quiz to be updated which must not be past quiz
	{
		// update request from quiz display

		$qid = $_POST["qid"];

		// extracting the tuple

		$result = $link -> query("SELECT * FROM quiz WHERE qid = $qid;");

		if($result === false || $result -> num_rows == 0)
		{
			/*
				Query execution failure or no tuples found, this leads back to Quiz Display
				page (this is very unlikely to happen)
			*/

			die("Failed to load the quiz, head back to <a href = 'quiz display.php#quiz$qid'> Quiz Display </a>");
		}

		/*
			loading a tuple as an associative array into session to
			be accessed from quiz input form, to display current values
			of the quiz
		*/

		$_SESSION["tuple"] = $result -> fetch_assoc();

		$_SESSION["tuple"]["date"] = $_POST['date'];

		$link -> close();

		/*
			redirect to the form to accept inputs, also send the qid of
			the quiz to be updated to that form as get value, so that if
			anything goes wrong there we can head directly back to the
			quiz in quiz display
		*/

		header("location: quiz update form.php?qid=$qid");
	}
	elseif(isset($_POST["update"]))
	{
		// update request from quiz update form

		/*
			Inputs will be sanitized and trimmed before adding them to SQL
			query to prevent SQL or HTML injection (sanitization)
		*/

		include "PHP/mysql_sanitize_input.php";
		
		$qid = $_POST['qid'];
		$quiz1 = mysql_sanitize_input($link, $_POST['quiz1']);
		$quiz2 = mysql_sanitize_input($link, $_POST['quiz2']);
		$quiz3 = mysql_sanitize_input($link, $_POST['quiz3']);
		$quiz4 = mysql_sanitize_input($link, $_POST['quiz4']);
		$quiz5 = mysql_sanitize_input($link, $_POST['quiz5']);
		$ans = mysql_sanitize_input($link, $_POST['ans']);

		$query = "UPDATE quiz SET quiz1 = '$quiz1', quiz2 = '$quiz2', quiz3 = '$quiz3', quiz4 = '$quiz4', quiz5 = '$quiz5', ans = '$ans' WHERE qid = $qid;";

		if($link -> query($query) === false)
		{
			/*
				Quiz update failure will lead back to Quiz Display page (this
				is very unlikely to happen).
			*/
			
			die("Update failure, head back to <a href = 'quiz display.php#quiz$qid'> Quiz Display </a>");
		}

		$link -> close();

		header("location: quiz display.php#quiz$qid");
	}
	else
	{
		// I don't know who wants to update, i.e., this file has been accessed illegally

		$link -> close();

		header("location: quiz display.php");
	}

?>