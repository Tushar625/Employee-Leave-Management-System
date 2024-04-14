<?php

	/*
		check if it's valid emp session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_mng_session.php";

	include "../PHP/config.php";

	include "../PHP/leave_days.php";

	include "../PHP/std_date_format.php";

	$consent = "mg1_consent";

	$query = "SELECT eid, lid, lrid, name, type, start_date, end_date, days, need_doc FROM leave_request NATURAL JOIN leave_rule NATURAL JOIN employee WHERE $consent is NULL";

	$result = $link -> query($query);

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>user_profile</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

			@import url("../CSS/list styles.css");

			@import url("../CSS/dashboard styles.css");

			.dashboard_menu > span
			{
				border-radius: inherit;
				margin: .2em;
				box-shadow: 1px 1px 5px -1px rgba(0, 0, 0, 0.1);
			}

		</style>

	</head>
	
	<body>
		
		<header>

			<!-- <?php include "header.php";?> -->

		</header>

		<main>

		<!-- <div class = "main_box nice_shadow"> -->

		<!--
			>>>> here we present a dashboard to display how many leave days has
			been used by the employee, wrt total no. of days available for each
			leave in leave rules table, we use progress bar for it
		-->
		
		<ul class = "main_box nice_shadow">

			<!-- one iteration of the loop creates the entry for one leave in the dashboard -->

			<?php for(;$row = $result -> fetch_assoc();):?>
			
			<!-- from eid and lid (of a leave) we calculate no. of leave days used by the employee -->

			<?php
				
				$eid = $row['eid'];
				
				$lid = $row['lid'];

				$lrid = $row['lrid'];

				$approved_days = approved_leave_days($link, $eid, $lid);
				
			?>

			<li>

				<!--
					little bit of color coding used here:
					red shadow -> all leave days are spent
					green shadow -> not all leave days are spent
				-->
				
				<div class = "message dashboard_menu <?php echo ($approved_days == $row['days']) ? "redbutton" : "greenbutton"?>">
					
					<span>
						<a href = "<?php echo "view.php?eid=$eid"?>">&#x1F50D;</a>
						<?php echo $row['name']?>
					</span>

					<span>
						
						<?php echo $row['type']?>
						
						<!-- create view doc button only if this leave needs doc -->

						<?php if($row['need_doc'] == true):?>
							
							<a href = "<?php echo "support_doc.php?lrid=$lrid"?>">&#128209;</a>
						
						<?php endif?>

						<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?>

					</span>

					<span>
						<?php

							if($row['start_date'] == $row['end_date'])
							{
								echo std_date_format($row['start_date']);
							}
							else
							{
								echo std_date_format($row['start_date']) . " &#8594; " . std_date_format($row['end_date']);
							}
						?>
					</span>
				
				</div>

			</li>

			<li>

				<div class = "message dashboard_menu <?php echo ($approved_days == $row['days']) ? "redbutton" : "greenbutton"?>">
					
					<span><a href = "<?php echo "stats.php?eid=$eid"?>"><progress max = '<?php echo $row['days']?>' value = '<?php echo $approved_days?>'></progress></a></span>

					<span><?php echo $approved_days . "/" . $row['days']?> Taken</span>

					<span class = "bluebutton">Comment <a href = "<?php echo "comment.php?lrid=$lrid"?>">&#128221;</a> Please</span>
				
				</div>

			</li>

			<?php endfor?>

		</ul>

		<?php $link -> close();?>

		</main>

		<footer></footer>

		<script src="../JS/scroll_back.js"></script>

	</body>

</html>