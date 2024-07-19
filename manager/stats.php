<?php

	/*
		check if it's valid mg session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_mng_session.php";

	if(!isset($_GET["eid"]))
	{
		// if eid URL parameter is not set go to index

		header("location: index.php");
	}

	include "../PHP/config.php";

	include "../PHP/mysql_sanitize_input.php";

	include "../PHP/leave_days.php";

	include "../PHP/std_date_format.php";

	$eid = mysql_sanitize_input($link, $_GET["eid"]);

	$result = $link -> query("SELECT lid, type, days FROM leave_rule");

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>Employee Leave Statistics</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

			@import url("../CSS/list styles.css");

			@import url("../CSS/dashboard styles.css");

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include "header.php";?>

		</header>

		<main>

		<!--
			>>>> here we present a dashboard to display how many leave days has
			been used by the employee, wrt total no. of days available for each
			leave in leave rules table, we use progress bar for it
		-->
		
		<ul class = "main_box nice_shadow">

			<!-- one iteration of the loop creates the entry for one leave in the dashboard -->

			<?php for(;$row = $result -> fetch_assoc();):?>
			
			<li>
				
				<!-- from eid and lid (of a leave) we calculate no. of leave days used by the employee -->

				<?php
					
					$lid = $row['lid'];

					$approved_days = approved_leave_days($link, $eid, $lid);
					
				?>

				<!--
					little bit of color coding used here:
					red shadow -> all leave days are spent
					green shadow -> not all leave days are spent
				-->
				
				<div class = "message dashboard_menu <?php echo ($approved_days == $row['days']) ? "redbutton" : "greenbutton"?>">
					
					<span><?php echo $row['type']?></span>
					
					<span><progress max = '<?php echo $row['days']?>' value = '<?php echo $approved_days?>'></progress></span>

					<span><?php echo $approved_days . "/" . $row['days']?></span>
				
				</div>

			</li>

			<?php endfor?>

		</ul>

		<?php $link -> close();?>

		</main>

		<footer></footer>

	</body>

</html>