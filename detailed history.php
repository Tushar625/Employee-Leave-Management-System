<?php

	include "PHP/check_user_session.php";

	include "PHP/game ranking system.php";

	include "PHP/config.php";

	// getting time stamp of start date and current_qid

	include "PHP/start_date_current_qid.php";

	// reading all the entries of score table

	$result = $link -> query("SELECT * FROM score WHERE uid = " . $_SESSION['USER_ID']);

	$link -> close();

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>detailed_history</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

			@import url("CSS/list styles.css");

			.message
			{
				text-align: center;
			}

			.main_box li .hidden
			{
				background-color: rgba(255, 0, 0, 0.1);
			}

		</style>

	</head>
	
	<body>
		
		<header id = 'first'>

			<?php include "PHP/user_header.php";?>

		</header>

		<main>

		<!-- displaying past history of the player for each game he played -->

		<?php $nav_interval = 10; $nav_index = 0;?>

		<?php for($nav_index = 0; $row = $result -> fetch_assoc(); $nav_index++): /* $nav_index for nevigation boxes */?>

		<!-- navigation box to reduce time to nevigate entire quiz list -->

		<?php if(($nav_index % $nav_interval) === 0):?>

			<ul id = <?php echo "menu$nav_index";?> class = "main_box blue_box">

				<!-- for first menu bar these two not necessary -->

				<?php if($nav_index != 0):?>

					<li>
						<a href = "<?php echo "#menu" . ($nav_index - $nav_interval)?>"><button class = 'button'> Previous </button></a>
					</li>

					<li>
						<a href = "#first"><button class = 'button'> Top </button></a>
					</li>

				<?php endif; ?>

				<li>
					<a href = "#last"><button class = 'button'> Bottom </button></a>
				</li>

				<!-- for last menu bar next button is same as Bottom button we put a navigation box at the bottom -->

				<?php if($nav_index + $nav_interval < $result -> num_rows):?>

					<li>
						<a href = "<?php echo "#menu" . ($nav_index + $nav_interval)?>"><button class = 'button'> Next </button></a>
					</li>

				<?php else:?>

					<li>
						<a href = "<?php echo "#last"?>"><button class = 'button'> Next </button></a>
					</li>

				<?php endif; ?>

			</ul>

		<?php endif; ?>

		<!-- Making a form and a box -->

		<form method = "get" action = "outcome init.php">

		<!-- hidden form elements qid sent to outcome init via get method -->

		<input type = 'hidden' name = "qid" value = <?php echo $row['qid'];?>>
		
		<!-- the box to display outcome in short -->

		<?php $qid = $row['qid'];?>
		
		<ul id = <?php echo "quiz$qid"?> class = "main_box red_box">

			<li>
				<div class = "message">
					<?php echo "Game #$qid " . date("Y-m-d", $start_date + (24 * 3600) * ($qid - 1));?>
				</div>
			</li>
			
			<li>
				<div class = "message">
					<?php print_stars($row["hints_used"], $row["solved"]);?>
				</div>
			</li>

			<!-- hidden button that leads to outcome init -->

			<li>
				<input class = "button hidden" type = submit name = "show_outcome" value = "<?php echo get_rank($row["hints_used"], $row["solved"]);?>">
			</li>

		</ul>

		</form>

		<?php endfor; ?>

		<!-- top and previous button at the end of the page -->

		<ul class = 'main_box blue_box'>

			<li>
				<a href = "<?php echo "#menu" . ($nav_index - $nav_index % $nav_interval)?>"><button class = 'button'> Previous </button></a>
			</li>

			<li>
				<a href = "#first"><button class = 'button'> Top </button></a>
			</li>

		</ul>

		</main>

		<footer id = 'last'></footer>

	</body>

</html>