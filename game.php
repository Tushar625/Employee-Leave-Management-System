<?php

	include "PHP/check_user_session.php";

	/*
		can we play or not (if the user has finished the game of today
		he will not be able to play again today)
	*/

	if(!$_SESSION['can_play'])
	{
		header("location: user profile.php");
	}

	// redirect once is used to clear previous inputs (pokemon names)

	include "PHP/self_redirect_once.php";

	self_redirect_once("");

	include "PHP/config.php";

	// current uid

	$uid = $_SESSION['USER_ID'];

	// getting current qid

	$current_qid = $_SESSION['QUIZ_ID'];

	// reading the hints from quiz table

	$result = $link -> query("SELECT * FROM quiz WHERE qid = $current_qid");

	if($result -> num_rows == 0)
	{
		// quiz doesn't exist

		die("The quiz is not uploaded yet. Please come back later.<br><br>For now, return to <a href = 'user profile.php'>Profile</a>");
	}

	// the quiz is found and loading it inside an array

	$quiz_tuple = $result -> fetch_array(MYSQLI_NUM);

	// calculating no. of hints to be used by this player

	$result = $link -> query("SELECT hints_used FROM score WHERE qid = $current_qid AND uid = $uid");

	$hints = 1;

	if($result -> num_rows == 0)
	{
		/*
			no entry is found in score table so creating new entry.

			by default no. of hints used is set to 1
		*/

		$link -> query("INSERT INTO score(uid, qid) VALUES($uid, $current_qid);");
	}
	else
	{
		/*
			entry is found in quiz table hence we read no. of hints used
		*/
		
		$hints = $result -> fetch_assoc()['hints_used'];
	}

	// collecting necessary hints

	/*
		this array will store the hints to be displayed and dash for the hidden quizes
	*/

	$quiz = [];

	$quiz[0] = $quiz[1] = $quiz[2] = $quiz[3] = $quiz[4] = "------------------------";

	for($i = 0; $i < $hints; $i++)
	{
		$quiz[$i] = $quiz_tuple[$i + 1];
	}

	$link -> close();

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>game</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

			@import url("CSS/list styles.css");

			.message
			{
				text-align: center;
			}

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include "PHP/user_header.php";?>

		</header>

		<main>

		<form method = "post" action = "game processing.php">

		<ul class = "main_box blue_box">

			<?php for($i = 0; $i < 5; $i++):?>

				<li>
					<div class = "message">
						<?php echo $quiz[$i]?>
					</div>
				</li>

			<?php endfor;?>

			<li>
				<input class = "button" type = "submit" name = "reveal" value = "Reveal Next" <?php if($hints == 5) echo "disabled";?>>
			</li>

		</ul>

		<ul class = "main_box blue_box">

			<li>
				<input name = "ans" placeholder= "Guess the Pokemon" list = "poke_names">
			</li>

			<?php include "PHP/poke_names_datalist.php";?>
			
			<li>
				<input class = "button" type = "submit" name = "confirm" value = "Confirm">
			</li>

			<li>
				<input class = "button" type = "submit" name = "give_up" value = "Give Up">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>