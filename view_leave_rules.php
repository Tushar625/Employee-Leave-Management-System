<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	// include "PHP/check_admin_session.php";

	include "PHP/config.php";	// connect to database

	// all quizes in quiz table

	$result = $link -> query("SELECT * FROM leave_rule;");

	$link -> close();

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>view_leave_rules</title>

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

			<?php //include "PHP/admin_header.php";?>
			
		</header>

		<main>

		<!-- Displaying the Leaves -->

		<!-- 10 leaves between 2 navigation boxes -->

		<?php $nav_interval = 10; $nav_index = 0;?>

		<!-- getting the tuples in leave rule table -->

		<?php for(;$row = $result -> fetch_assoc(); $nav_index++): /* index of the quiz read */?>

		<!-- navigation box to reduce time to nevigate entire quiz list -->

		<?php if(($nav_index % $nav_interval) === 0):?>

			<ul id = <?php echo "menu$nav_index";?> class = "main_box next_box">

				<!-- for first menu bar these two not necessary -->

				<?php if($nav_index != 0):?>

					<!-- go to previous navigation box -->

					<li>
						<a href = "<?php echo "#menu" . ($nav_index - $nav_interval)?>"><button class = 'button'> Previous </button></a>
					</li>

					<!-- go to top of the page -->

					<li>
						<a href = "#first"><button class = 'button'> Top </button></a>
					</li>

				<?php endif; ?>

				<!-- go to bottom of the page -->

				<li>
					<a href = "#last"><button class = 'button'> Bottom </button></a>
				</li>

				<!-- go to next navigation box -->

				<?php if($nav_index + $nav_interval < $result -> num_rows):?>

					<li>
						<a href = "<?php echo "#menu" . ($nav_index + $nav_interval)?>"><button class = 'button'> Next </button></a>
					</li>

				<?php else:?>

					<!--
						for last navigation box next button is same as Bottom button
						as we keep a last navigation box at the bottom of the page
					-->

					<li>
						<a href = "<?php echo "#last"?>"><button class = 'button'> Next </button></a>
					</li>

				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<!-- Making a form (necessary for update) and a box -->

		<form method = "post" action = "quiz update delete.php">

		<!-- hidden form elements lid for update and delete -->

		<input type = 'hidden' name = "lid" value = <?php echo $row['lid'];?>>

		<!-- the leave box -->

		<ul id = <?php echo "leave" . $row['lid'];?> class = "main_box next_box">

			<!-- the lid of leave -->
			
			<li>
				<div class = "message">
					<?php echo "Leave #" . $row['lid'];?>
				</div>
			</li>

			<!-- the leave attributes -->

			<li>
				<div class = "message">
					<?php echo $row["name"]?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo "Maximum ". $row["days"] . " Days"?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo (($row["need_doc"]) ? "Need" : "Need no ") . " Supporting Document"?>
				</div>
			</li>

			<!-- update buttom -->

			<li>
				<input class = "button" type = "submit" name = "update_request" value = "Update">
			</li>

			<!-- delete buttom -->

			<li>
				<input class = "button" type = "submit" name = "delete_request" value = "Delete">
			</li>

		</ul>

		</form>

		<?php endfor; ?>

		<!-- top and previous button at the end of the page -->

		<ul class = 'main_box next_box'>

			<li>
				<a href = "<?php echo "#menu" . ($nav_index - $nav_index % $nav_interval)?>"><button class = 'button'> Previous </button></a>
			</li>

			<li>
				<a href = "#first"><button class = 'button'> Top </button></a>
			</li>

		</ul>

		</main>

		<footer id = "last"></footer>

	</body>

</html>