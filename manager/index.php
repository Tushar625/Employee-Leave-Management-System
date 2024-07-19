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

	// collecting consent of junior manager and reason for leave

	$data = ($mrank == 1) ? "reason" : "reason, mg1_consent";

	/*
		manager1 can see the tuples where mg1_consent and mg2_consent both are null

		manager2 can see the tuples where mg1_consent is not null but mg2_consent is null
	*/

	$mg1_consent = ($mrank == 1) ? "IS NULL" : "IS NOT NULL";

	$query = "SELECT eid, lid, lrid, name, type, start_date, end_date, $data, days, need_doc FROM leave_request NATURAL JOIN leave_rule NATURAL JOIN employee WHERE mg1_consent $mg1_consent AND mg2_consent IS NULL ORDER BY lrid";

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

			.dashboard_menu
			{
				padding: 1.5em !important;
				height: 100% !important;
			}

			/*
				here span will contain important text information hence
				getting nice border around it
			*/
			
			.dashboard_menu > span
			{
				border-radius: inherit;
				margin: .3em;
				text-align: left;
			}

			.bottom_second
			{
				margin-bottom: .6em !important;
			}

			.bottom_most
			{
				margin-top: auto !important;
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

				$leave_name = $row['type'];

				$days_used = $row['days'];

				$days_requested = count_leave_days($row['start_date'], $row['end_date']);

				$start_date = $row['start_date'];
				
				$end_date = $row['end_date'];

				$approved_days = approved_leave_days($link, $eid, $lid);

				$reason = $row["reason"];

				if($mrank == 2)
				{
					// only mg2 sees mg1 consent

					$consent = $row["mg1_consent"];
				}
				
			?>

			<li id = "<?php echo "lrid$lrid"?>">

				<!-- 
					here we show emp details and leave stats
				 -->
				
				<div class = "message dashboard_menu">
					
					<span class = "heading">

						<?php echo "<h1>$leave_name</h1>"?>
			
					</span>

					<!-- emp name -->

					<span>
						
						<!-- button to display leave history of the employee -->

						Name: <a href = "<?php echo "view.php?eid=$eid"?>"><?php echo $row['name']?></a>

						<!--<?php echo $row['name']?>-->

					</span>

					<!-- start and end date -->

					<span>
						Duration: 
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

					<span>
						
						No of days: 

						<?php echo $days_requested?>

					</span>

					<!-- no. of leaves already approved to this emp -->

					<span>
						
						Used days: 

						<?php echo $approved_days . "/" . $row['days']?>

					</span>

					<span class = "blue_text">
						<b>Reason:</b>
					</span>

					<span>
						
						<div class = "message">
							
							<?php echo $reason?>
							
						</div>

					</span>

					<!-- supporting doc -->

					<?php if($row['need_doc'] == true):?>

						<span><a href = "<?php echo "support_doc.php?lrid=$lrid"?>"><button class = "button bluebutton">Support Document</button></a></span>
						
					<?php endif?>

					<!-- mamager 1 consent -->
					
					<?php if(isset($consent)):?>

						<span class = "red_text">
							<b><?php echo get_rank($mrank - 1) . ":"?></b>
						</span>

						<span>
							<div class = "message"><?php echo $consent?></div>
						</span>

					<?php endif?>

					<span class = "bottom_second"><a href = "<?php echo "stats.php?eid=$eid"?>"><button class = "button bluebutton">Leave Statistics</button></a></span>

					<!-- approval and consent buttons -->

					<?php if($mrank == 1):?>

						<!-- for mg1 -->

						<span class = "bottom_most"><a href = "<?php echo "comment.php?lrid=$lrid"?>"><button class = "button">Comment Please</button></a></span>

					<?php else:?>

						<!-- for mg2 -->

						<span class = "bottom_most"><a href = "<?php echo "approve.php?lrid=$lrid"?>"><button class = "button">Approve</button></a></span>

						<span><a href = "<?php echo "comment.php?lrid=$lrid"?>"><button class = "button">Decline</button></a></span>

					<?php endif?>
				
				</div>

			</li>

			<?php endfor?>

			<!-- print an extra invisible box if odd no. of leave requests found -->

			<?php if($result -> num_rows % 2 != 0):?>
				
				<li><div class = "message dashboard_menu no_shadow"></div></li>
			
			<?php endif?>

		</ul>

		<?php $link -> close();?>

		</main>

		<footer></footer>

		<script src="../JS/scroll_back.js"></script>

	</body>

</html>