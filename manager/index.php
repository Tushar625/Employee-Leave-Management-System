<?php

	/*
		check if it's valid mg session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_mng_session.php";

	include "../PHP/config.php";

	include "../PHP/leave_days.php";

	include "../PHP/std_date_format.php";

	include "../PHP/emp_ranking_system.php";

	$mrank = $_SESSION["MANAGER_RANK"];

	/*
		manager1 can see the tuples where mg1_consent and mg2_consent both are null

		manager2 can see the tuples where mg1_consent is not null but mg2_consent is null
	*/

	$mg1_consent = ($mrank == 1) ? "IS NULL" : "IS NOT NULL";

	$query = "SELECT eid, lid, lrid, name, type, start_date, end_date, days FROM leave_request NATURAL JOIN leave_rule NATURAL JOIN employee WHERE mg1_consent $mg1_consent AND mg2_consent IS NULL";

	$result = $link -> query($query);

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>Manager Index</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

			@import url("../CSS/list styles.css");

			@import url("../CSS/dashboard styles.css");

			/*
				here span will contain important text information hence
				getting nice border around it
			*/

			.dashboard_menu > span
			{
				border-radius: inherit;
				margin: .2em;
				/* box-shadow: 1px 1px 5px -1px rgba(0, 0, 0, 0.1); */
			}

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

			<!-- checking how many leave requests we have -->

			<?php if($result -> num_rows == 0):?>

				<li>
					<div class = "message redbutton"><center>Empty List &#128526;</center></div>
				</li>

			<?php endif?>

			<!-- one iteration of the loop creates the entry for one leave in the dashboard -->

			<?php for(;$row = $result -> fetch_assoc();):?>
			
			<!-- from eid and lid (of a leave) we calculate no. of leave days (approved) already used by the employee -->

			<?php
				
				$eid = $row['eid'];
				
				$lid = $row['lid'];

				$lrid = $row['lrid'];

				$approved_days = approved_leave_days($link, $eid, $lid);
				
			?>

			<li id = "<?php echo "lrid$lrid"?>">

				<!-- 
					here we show emp details and leave stats
				 -->
				
				<div class = "message dashboard_menu <?php echo ($approved_days == $row['days']) ? "redbutton" : "bluebutton"?>">
					
					<!-- emp name -->

					<span>
						
						<!-- button to display leave history of the employee -->

						<a href = "<?php echo "view.php?eid=$eid"?>">&#x1F50D;</a>

						<?php echo $row['name']?>

					</span>

					<!-- 
						leave stats as a progress bar and a hidden button to display complete
						leave stats of this emp in brief with progress bars
					 -->

					<span><a href = "<?php echo "stats.php?eid=$eid"?>"><progress max = '<?php echo $row['days']?>' value = '<?php echo $approved_days?>'></progress></a></span>

					<!-- no. of leaves already approved to this emp -->

					<span>
						
						<?php echo $approved_days . "/" . $row['days']?> Taken

					</span>
				
				</div>

			</li>

			<!-- 
				leave type, reason and consent or approval
			 -->

			<li>

				<div class = "message dashboard_menu <?php echo ($approved_days == $row['days']) ? "redbutton" : "bluebutton"?>">

					<!-- leave type and no. of days -->

					<span>
						
						<?php echo $row['type']?>
						
						<!-- button to display leave reason and mg1 consent -->

						<a href = "<?php echo "consent.php?lrid=$lrid"?>">&#128209;</a>

						<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?>

					</span>

					<!-- start and end date -->

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

					<!-- approval and consent buttons -->

					<?php if($mrank == 1):?>

						<!-- for mg1 -->
						
						<span>Comment <a href = "<?php echo "comment.php?lrid=$lrid"?>">&#128221;</a> Please</span>

					<?php else:?>

						<!-- for mg2 -->

						<span>
							Approve <a href = "<?php echo "approve.php?lrid=$lrid"?>">&#10004;</a>
							Decline <a href = "<?php echo "comment.php?lrid=$lrid"?>">&#128221;</a>
						</span>

					<?php endif?>
				
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