<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "PHP/check_admin_session.php";

	/*
		here we check for the session variable we created in "quiz
		update delete.php"

		If that thing doesn't exist we return to quiz display page
		at certain quiz

		If that thing exists we store its data into local variables
		and delete the session variable so that it can't be used
		again to reload this form
	*/

	if(!isset($_SESSION['tuple']))
	{
		/*
			this file receives qid of the quiz to be updated from
			"quiz update delete.php" so that we can return to proper
			quiz in quiz display page

			if no such get value is received we simply go back to the
			top of quiz display page
		*/
		
		// invalid access

		$id = (isset($_GET["qid"])) ? "#quiz" . $_GET["qid"] : "";

		die("Form initializing failure, head back to <a href = 'quiz display.php$id'> Quiz Display </a>");
	}
	else
	{
		$date = $_SESSION["tuple"]['date'];
		$qid = $_SESSION["tuple"]['qid'];
		$quiz1 = $_SESSION["tuple"]['quiz1'];
		$quiz2 = $_SESSION["tuple"]['quiz2'];
		$quiz3 = $_SESSION["tuple"]['quiz3'];
		$quiz4 = $_SESSION["tuple"]['quiz4'];
		$quiz5 = $_SESSION["tuple"]['quiz5'];
		$ans = $_SESSION["tuple"]['ans'];

		unset($_SESSION["tuple"]);
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>quiz_update</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include "PHP/admin_header.php";?>

		</header>

		<main>
			
		<form method = "post" action = "quiz update delete.php">

		<input type = 'hidden' name = "qid" value = <?php echo $qid;?>>
		
		<ul class = "main_box nice_shadow">

			<li>
				<div class = "info message">
					Update Quiz for <?php echo $date;?>
				</div>
			</li>

			<!-- Maxlength is set according to size of the fields in quiz table -->
			
			<li>
				<label> Quiz 1 <textarea name = "quiz1" maxlength = 256 required><?php echo $quiz1;?></textarea> </label>
			</li>
			
			<li>
				<label> Quiz 2 <textarea name = "quiz2" maxlength = 256 required><?php echo $quiz2;?></textarea> </label>
			</li>

			<li>
				<label> Quiz 3 <textarea name = "quiz3" maxlength = 256 required><?php echo $quiz3;?></textarea> </label>
			</li>

			<li>
				<label> Quiz 4 <textarea name = "quiz4" maxlength = 256 required><?php echo $quiz4;?></textarea> </label>
			</li>

			<li>
				<label> Quiz 5 <textarea name = "quiz5" maxlength = 256 required><?php echo $quiz5;?></textarea> </label>
			</li>

			<li>
				<label> Answer <input name = "ans" maxlength = 50 list = "poke_names" value = '<?php echo $ans;?>' required> </label>
			</li>

			<!--
				"poke_names_datalist.php" contains list of pokemon names to
				make it easier to enter pokemon names as answer
			-->

			<?php include "PHP/poke_names_datalist.php";?>

			<li>
				<div class = "error message">
					Update Quiz carefully. It can be modified in future, if needed.
				</div>
			</li>

			<li>
				<input class = "button" type = "submit" name = "update" value = "Update">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>