<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "PHP/check_admin_session.php";

	include "PHP/config.php";	// connect to database

	/*
		Here we keep each quiz inside a box and	use the color of
		the box-shadow to indicate past, current and future quizes
	*/
	
	function shadow_selection($qid, $current_qid)
	{
		if($qid == $current_qid)
		{
			return "current_box";	// current quiz
		}
		elseif($qid < $current_qid)
		{
			return "previous_box";	// past quiz
		}
		else
		{
			return "next_box";	// future quiz
		}
	}

	$hint_num = 5;	// no. of hints per quiz

	// getting time stamp of start date and current_qid

	include "PHP/start_date_current_qid.php";

	// all quizes in quiz table

	$result = $link -> query("SELECT * FROM quiz;");

	$link -> close();

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>quiz_display</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

			@import url("CSS/list styles.css");

			.main_box li .button
			{
				border: none;
			}

		</style>

	</head>
	
	<body>
		
		<header id = 'first'>

			<?php include "PHP/admin_header.php";?>
			
		</header>

		<main>

		<!-- Displaying the quizes -->

		<!-- 10 quizes between 2 navigation boxes -->

		<?php $nav_interval = 10; $nav_index = 0;?>

		<!-- getting the tuples in quiz table -->

		<?php for(;$row = $result -> fetch_assoc(); $nav_index++): /* index of the quiz read */?>

		<!-- navigation box to reduce time to nevigate entire quiz list -->

		<?php if(($nav_index % $nav_interval) === 0):?>

			<ul id = <?php echo "menu$nav_index";?> class = <?php echo "'main_box " . shadow_selection($row['qid'], $current_qid) . "'"; ?>>

				<!-- for first menu bar these two not necessary -->

				<?php if($nav_index != 0):?>

					<!-- go to previous navigation box -->

					<li>
						<a href = "<?php echo "quiz display.php#menu" . ($nav_index - $nav_interval)?>"><button class = 'button'> Previous </button></a>
					</li>

					<!-- go to top of the page -->

					<li>
						<a href = "quiz display.php#first"><button class = 'button'> Top </button></a>
					</li>

				<?php endif; ?>

				<!-- go to current quiz -->

				<li>
					<a href = "<?php echo "quiz display.php#quiz$current_qid"?>"><button class = 'button'> Current Quiz </button></a>
				</li>

				<!-- go to bottom of the page -->

				<li>
					<a href = "quiz display.php#last"><button class = 'button'> Bottom </button></a>
				</li>

				<!-- go to next navigation box -->

				<?php if($nav_index + $nav_interval < $result -> num_rows):?>

					<li>
						<a href = "<?php echo "quiz display.php#menu" . ($nav_index + $nav_interval)?>"><button class = 'button'> Next </button></a>
					</li>

				<?php else:?>

					<!--
						for last navigation box next button is same as Bottom button
						as we keep a last navigation box at the bottom of the page
					-->

					<li>
						<a href = "<?php echo "quiz display.php#last"?>"><button class = 'button'> Next </button></a>
					</li>

				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<!--
			calculating date for this quiz (time stamp of today + no. of seconds
			in a day * (terget qid - 1))
		-->

		<?php $dated = date("Y-m-d", $start_date + (24 * 3600) * ($row['qid'] - 1));?>

		<!-- Making a form (necessary for update) and a box -->

		<form method = "post" action = "quiz update delete.php">

		<!-- hidden form elements qid, date for update and delete -->

		<input type = 'hidden' name = "qid" value = <?php echo $row['qid'];?>>

		<input type = 'hidden' name = "date" value = <?php echo $dated;?>>

		<!-- the quiz box -->

		<ul id = <?php echo "quiz" . $row['qid'];?> class = <?php echo "'main_box " . shadow_selection($row['qid'], $current_qid) . "'"; ?>>

			<!-- the date of display -->
			
			<li>
				<div class = "message">
					<?php echo "Quiz #" . $row['qid'] . " " . $dated;?>
				</div>
			</li>

			<!-- the hints -->

			<?php for($i = 1; $i <= $hint_num; $i++):?>

				<li>
					<div class = "message">
						<?php echo $row["quiz" . $i]?>
					</div>
				</li>

			<?php endfor; ?>

			<!-- the answer -->

			<li>
				<div class = "error message">
					<?php echo $row['ans']?>
				</div>
			</li>

			<!-- update buttom (only for the current and upcoming quizes) -->

			<li>
				<input class = "button" type = "submit" name = "update_request" value = "Update" <?php if($row['qid'] < $current_qid) echo "disabled"?>>
			</li>

			<!-- delete buttom (only for last quiz and it must not be current or past quiz) -->

			<?php if($row['qid'] > $current_qid && $nav_index + 1 == $result -> num_rows):?>

				<li>
					<input class = "button" type = "submit" name = "delete_last" value = "Delete">
				</li>

			<?php endif;?>

		</ul>

		</form>

		<?php endfor; ?>

		<!-- top and previous button at the end of the page -->

		<ul class = 'main_box next_box'>

			<li>
				<a href = "<?php echo "quiz display.php#menu" . ($nav_index - $nav_index % $nav_interval)?>"><button class = 'button'> Previous </button></a>
			</li>

			<li>
				<a href = "quiz display.php#first"><button class = 'button'> Top </button></a>
			</li>

			<li>
				<a href = "<?php echo "quiz display.php#quiz$current_qid"?>"><button class = 'button'> Current Quiz </button></a>
			</li>

		</ul>

		</main>

		<footer id = "last"></footer>

	</body>

</html>