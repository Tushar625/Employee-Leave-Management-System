<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_emp_session.php";

	include "../PHP/config.php";	// connect to database

	include "../PHP/leave_days.php";

	function get_status($mg2consent)
	{
		switch($mg2consent)
		{
			case'A': return "Approved";
			case'': return "Pending";
			default: return "Declined";
		}
	}

	function get_status_color($mg2consent)
	{
		switch($mg2consent)
		{
			case'A': return "green";
			case'': return "blue";
			default: return "red";
		}
	}

	$eid = $_SESSION['EMPLOYEE_ID'];

	// all quizes in quiz table

	$type = $_GET["type"];

	// deleting emp or leave

	switch($type)
	{
		case 0:	// all leave history desc order
				
				$query = "SELECT lrid, name, start_date, end_date, mg2_consent FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid order by lrid desc";
				
				break;

		case 1:	// all approved leave history desc order
				
				$query = "SELECT lrid, name, start_date, end_date, mg2_consent FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent = 'A' order by lrid desc";
					
				break;
		
		case 2:	// all declined leave history desc order
		
				$query = "SELECT lrid, name, start_date, end_date, mg2_consent FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent <> 'A' AND mg2_consent is NOT NULL order by lrid desc";
			
				break;
	}

	// $query = ($type == true) ? "SELECT * FROM employee" : "SELECT * FROM leave_rule";

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

			<?php include "header.php";?>
			
		</header>

		<main>

		<!-- Displaying the Leaves -->

		<!-- 10 leaves between 2 navigation boxes -->

		<?php $nav_interval = 10; $nav_index = 0;?>

		<!-- getting the tuples in emp rule table -->

		<?php for(;$row = $result -> fetch_assoc(); $nav_index++): /* index of the record read */?>

		<!-- navigation box to reduce time to nevigate entire list -->

		<?php if(($nav_index % $nav_interval) === 0):?>

			<ul id = <?php echo "menu$nav_index";?> class = "main_box current_box">

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
		
			$id = $row['lrid'];
		
		?>

		<div id = <?php echo "navid" . $nav_index;?>></div>

		<ul id = <?php echo "id" . $id;?> class = "main_box next_box">

			<li>
				<div class = "message">
					<?php echo $row['name']?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo $row['start_date'] . " - " . $row['end_date']?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . " Days"?>
				</div>
			</li>

			<li>
				<button class = "<?php echo "button " . get_status_color($row['mg2_consent']) . "button"?>">
					<?php echo get_status($row['mg2_consent'])?>
				</button>
			</li>

		</ul>

		<?php endfor; ?>

		<!-- top and previous button at the end of the page -->

		<ul class = 'main_box previous_box'>

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