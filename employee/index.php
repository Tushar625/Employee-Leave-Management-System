<?php

	/*
		check if it's valid emp session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_emp_session.php";

	include "../PHP/config.php";

	include "../PHP/leave_days.php";

	include "../PHP/std_date_format.php";

	$eid = $_SESSION['EMPLOYEE_ID'];

	$result = $link -> query("SELECT lid, type, days FROM leave_rule");

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

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include "header.php";?>

		</header>

		<main>

		<div class = "main_box nice_shadow">

		<!--
			>>>> here we present a dashboard to display how many leave days has
			been used by the employee, wrt total no. of days available for each
			leave in leave rules table, we use progress bar for it
		-->
		
		<ul class = "main_box">

			<!-- one iteration of the loop creates the entry for one leave in the dashboard -->

			<?php for(;$row = $result -> fetch_assoc();):?>
			
			<li>
				
				<!-- from eid and lid (of a leave) we calculate no. of leave days used by the employee -->

				<?php $remaining_days = $row['days'] - used_leave_days($link, $eid, $row['lid'])?>

				<!--
					little bit of color coding used here:
					red shadow -> all leave days are spent
					green shadow -> not all leave days are spent
				-->
				
				<div class = "message dashboard_menu <?php echo ($remaining_days == 0) ? "redbutton" : "greenbutton"?>">
					
					<span><?php echo $row['type']?></span>
					
					<span><progress max = '<?php echo $row['days']?>' value = '<?php echo $remaining_days?>'></progress></span>

					<span><?php echo $remaining_days . "/" . $row['days']?></span>
				
				</div>

			</li>

			<?php endfor?>

		</ul>

		<!-- >>>> two buttons only -->

		<ul class = "main_box">

			<!-- view history button -->

			<li>
				<a href = "view.php?type=0"><button class = 'button d_box bluebutton'> Leave History </button></a>
			</li>

			<!-- delete buttom -->

			<li>
				<a href = "choose_leave.php"><button class = 'button d_box bluebutton'> Leave Request </button></a>
			</li>

		</ul>

		<!--
			>>>> Now we display all the ** waiting leave requests ** and also
			add options to delete them and view supporting doc

			we use blue shadow for waiting list as per our color convension
		-->

		<?php

			// getting waiting ones out of database

			$query = "SELECT lid, lrid, type, start_date, end_date, reason, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent is NULL order by lrid desc";
		
			$result = $link -> query($query);

			$nav_index = 0;

		?>

		<?php for(;$row = $result -> fetch_assoc(); $nav_index++): /* index of the record read */?>

		<?php
			$lid = $row['lid'];

			$lrid = $row['lrid'];
		?>

		<!-- navid is used to fecilitate proper return after deletion -->

		<ul id = "<?php echo "navid$nav_index"?>" class = "main_box blue_box">

			<!-- leave type with delete and view doc button -->
			
			<li>
				<!-- mark latest leave request with red -->
				
				<div class = "<?php echo (($nav_index === 0) ? "message redbutton" : "message")?>">

					<!-- delete button -->

					<a href = "<?php echo "delete.php?navid=$nav_index&lid=$lid&lrid=$lrid"?>">&#10006;</a>
					
					<!-- leave type -->

					<?php echo $row['type']?>

					<!-- create view doc button only if this leave needs doc -->

					<?php if($row['need_doc'] == true):?>
					
						<a href = "<?php echo "support_doc.php?lid=$lid&lrid=$lrid"?>">&#128209;</a>
					
					<?php endif?>

				</div>
			</li>

			<!-- start and end dates of the leave -->

			<li>
				<div class = "message">
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
				</div>
			</li>

			<!-- no. of days and emoji to indicate status -->

			<li>
				<div class = "message">
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?> &#128580;
				</div>
			</li>

			<!-- reason -->

			<li>
				<div class = "message">
					<?php echo $row['reason']?>
				</div>
			</li>

		</ul>

		<?php endfor?>

		<!--
			>>>> Now we display last 3 ** approved leave requests **

			we use green shadow for approved list as per our color convension
		-->

		<?php

			// getting last 3 approved ones out of database

			$query = "SELECT type, start_date, end_date, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent = 'A' order by lrid desc limit 3";
		
			$result = $link -> query($query);

		?>

		<?php for(;$row = $result -> fetch_assoc();):?>

		<ul class = "main_box green_box">

			<!-- leave type -->
			
			<li>
				<div class = "message">
					<?php echo $row['type']?>
				</div>
			</li>

			<!-- dates -->

			<li>
				<div class = "message">
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
				</div>
			</li>

			<!-- days and status emoji -->

			<li>
				<div class = "message">
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?> &#x1F44D;
				</div>
			</li>

		</ul>

		<?php endfor?>

		<?php if($result -> num_rows):?>

			<!-- A link to the list of all approved leaves, don't display it no approved leaves exists -->

			<ul class = "main_box">

				<li>
					<a href = "view.php?type=1"><button class = "button greenbutton"><b>All Approved</b></button></a>
				</li>

			</ul>

		<?php endif?>

		<!--
			>>>> Now we display last 3 ** declined leave requests **

			we use green shadow for approved list as per our color convension
		-->

		<?php

			// getting last 3 declined ones out of database

			$query = "SELECT type, start_date, end_date, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent <> 'A' AND mg2_consent is NOT NULL order by lrid desc limit 3";
		
			$result = $link -> query($query);

			$link -> close();

		?>

		<?php for(;$row = $result -> fetch_assoc();):?>

		<ul class = "main_box red_box">

			<!-- leave type -->
			
			<li>
				<div class = "message">
					<?php echo $row['type']?>
				</div>
			</li>

			<!-- dates -->

			<li>
				<div class = "message">
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
				</div>
			</li>

			<!-- days and status emoji -->

			<li>
				<div class = "message">
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?> &#x1F44E;
				</div>
			</li>

		</ul>

		<?php endfor?>

		<?php if($result -> num_rows):?>

			<!-- A link to the list of all declined leaves, don't display it no declined leaves exists -->

			<ul class = "main_box">

				<li>
					<a href = "view.php?type=2"><button class = "button redbutton"><b>All Declined</b></button></a>
				</li>

			</ul>

		<?php endif?>

		</div>

		</main>

		<footer></footer>

		<script src="../JS/scroll_back.js"></script>

	</body>

</html>