<?php

	/*
		check if it's valid hr session or not if not redirect
		to index or home page
	*/

	include_once "../PHP/check_hr_session.php";

	include_once "../PHP/config.php";	// connect to database

	// all entries in employee or leave_rule table

	$type = $_GET["type"];

	// deleting emp or leave

	$query = ($type == true) ? "SELECT * FROM employee" : "SELECT * FROM leave_rule";

	$result = $link -> query($query);

	$link -> close();

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>view</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

			@import url("../CSS/list styles.css");

		</style>

	</head>
	
	<body>
		
		<header id = 'first'>

			<?php include_once "header.php";?>
			
		</header>

		<main>

		<!-- Displaying the entries -->

		<!-- 10 entries between 2 navigation boxes -->

		<?php $nav_interval = 10; $nav_index = 0;?>

		<!-- getting the tuples in emp or leave_rule table -->

		<?php for(;$row = $result -> fetch_assoc(); $nav_index++): /* index of the record read */?>

		<!-- navigation box to reduce time to nevigate entire list -->

		<?php if(($nav_index % $nav_interval) === 0):?>

			<ul id = <?php echo "menu$nav_index";?> class = "main_box green_box">

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

		<!-- the record box -->

		<?php
		
			$id = ($type == true) ? $row['eid'] : $row['lid'];
		
		?>

		<div id = <?php echo "navid" . $nav_index;?>></div>

		<ul id = <?php echo "id" . $id;?> class = "main_box blue_box">

			<?php if($type == true):?>

				<!-- the emp attributes -->

				<li>
					<div class = "message">
						<?php echo $row["name"]?>
					</div>
				</li>

				<li>
					<div class = "message">
						<a href = "<?php echo "mailto:" . $row["email"]?>"><?php echo $row["email"]?></a>
					</div>
				</li>

				<li>
					<div class = "message">
						<?php echo $row["phone"]?>
					</div>
				</li>

				<li>
					<div class = "message">
						<?php
							
							include_once "../PHP/emp_ranking_system.php";

							echo get_rank($row['ranks']);

						?>
					</div>
				</li>

			<?php else:?>

				<!-- the leave attributes -->

				<li>
					<div class = "message">
						<?php echo $row["type"]?>
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
			
			<?php endif?>

			<!-- update buttom -->

			<li>
				<a href = "<?php echo "update.php?id=$id&type=$type"?>"><button class = 'button greenbutton'> Update </button></a>
			</li>

			<!-- delete buttom -->

			<li>
				<a href = "<?php echo "delete.php?id=$id&navid=$nav_index&type=$type"?>"><button class = 'button redbutton'> Delete </button></a>
			</li>

		</ul>

		<?php endfor; ?>

		<!-- top and previous button at the end of the page -->

		<ul class = 'main_box red_box'>

			<li>
				<a href = "<?php echo "#menu" . ($nav_index - $nav_index % $nav_interval)?>"><button class = 'button'> Previous </button></a>
			</li>

			<li>
				<a href = "#first"><button class = 'button'> Top </button></a>
			</li>

		</ul>

		</main>

		<footer id = "last"></footer>

		<script src="../JS/scroll_back.js"></script>

	</body>

</html>