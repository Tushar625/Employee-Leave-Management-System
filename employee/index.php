<?php

	include "../PHP/check_emp_session.php";

	include "../PHP/config.php";

	include "../PHP/leave_days.php";

	$eid = $_SESSION['EMPLOYEE_ID'];

	$result = $link -> query("SELECT lid, name, days FROM leave_rule");

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

			.main_box1 > * .button, .main_box1 > * .message
			{
				width: 23em;
			}

			.message
			{
				display: flex;	/* to properly align the progress bar */
				align-items: center;
				white-space: nowrap;
			}

			.left_bar
			{
				margin-left: auto;
			}

			.rank
			{
				color: brown;
			}

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include "header.php";?>

		</header>

		<main>
		
		<ul class = "main_box main_box1 nice_shadow">

			<!-- getting the tuples in leave rule table -->

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>
			
			<li>
				<?php $used_days = used_leave_days($link, $eid, $row['lid'])?>
				<div class = "<?php echo ($used_days == $row['days']) ? "message redbutton" : "message greenbutton"?>">
					<span><?php echo $row['name']?></span>
					
					<span class = "left_bar"><?php echo $used_days . " / " . $row['days']?> <progress max = '<?php echo $row['days']?>' value = '<?php echo $used_days?>'></progress></span>
				</div>
			</li>

			<?php endfor?>

		</ul>

		<ul class = "main_box main_box1 nice_shadow">

			<!-- view history button -->

			<li>
				<a href = "view.php?type=0"><button class = 'button bluebutton'> Leave History </button></a>
			</li>

			<!-- delete buttom -->

			<li>
				<a href = "choose_leave.php"><button class = 'button bluebutton'> Leave Request </button></a>
			</li>

		</ul>

		<?php

			// pending ones

			$query = "SELECT lid, lrid, name, start_date, end_date, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent is NULL order by lrid desc";
		
			$result = $link -> query($query);

			$nav_index = 0;

		?>

		<!-- Display pending leave requests -->

		<div class = "main_box nice_shadow">

			<?php for(;$row = $result -> fetch_assoc(); $nav_index++): /* index of the record read */?>

			<ul id = "<?php echo "navid$nav_index"?>" class = "main_box">
			
			<li>
				<div class = "message">
					<?php echo $row['name']?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php

						if($row['start_date'] == $row['end_date'])
						{
							echo $row['start_date'];
						}
						else
						{
							echo $row['start_date'] . " &#8594; " . $row['end_date'];
						}
					?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?>
				</div>
			</li>

			<li>
				<button class = "button bluebutton">
					Pending
				</button>
			</li>

			<li>
				<?php if($row['need_doc'] == true):?>
					
					<a href = "choose_leave.php"><button class = 'button bluebutton'> View Document </button></a>
				
				<?php else:?>
					
					<button class = 'button bluebutton' disabled> No Document </button>

				<?php endif?>
			</li>

			<li>
				
				<?php
					$lid = $row['lid'];

					$lrid = $row['lrid'];
				?>
				
				<a href = "<?php echo "delete.php?navid=$nav_index&lid=$lid&lrid=$lrid"?>"><button class = 'button redbutton'> Delete Request </button></a>
			
			</li>

			</ul>

			<?php endfor?>

			<?php if($nav_index === 0):?>

				<ul class = "main_box">

				<li>
					<button class = "button redbutton">No Leave Request Pending</button>
				</li>

			<?php endif?>

			</ul>


		</div>

		<?php

			// last 3 approved ones

			$query = "SELECT name, start_date, end_date, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent = 'A' order by lrid desc limit 3";
		
			$result = $link -> query($query);

		?>

		<!-- Display last 3 approved leave requests -->

		<div class = "main_box nice_shadow">

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>

			<ul class = "main_box">
			
			<li>
				<div class = "message">
					<?php echo $row['name']?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php

						if($row['start_date'] == $row['end_date'])
						{
							echo $row['start_date'];
						}
						else
						{
							echo $row['start_date'] . " &#8594; " . $row['end_date'];
						}
					?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?>
				</div>
			</li>

			<li>
				<button class = "button greenbutton">
					Approved
				</button>
			</li>

			</ul>

			<?php endfor?>

			<ul class = "main_box">

			<li>
				<a href = "view.php?type=1"><button class = "button">View All Approved</button></a>
			</li>

			</ul>

		</div>

		<?php

			// last 3 declined ones

			$query = "SELECT name, start_date, end_date, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent <> 'A' AND mg2_consent is NOT NULL order by lrid desc limit 3";
		
			$result = $link -> query($query);

			$link -> close();

		?>

		<!-- Display last 3 declined leave requests -->

		<div class = "main_box nice_shadow">

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>

			<ul class = "main_box">
			
			<li>
				<div class = "message">
					<?php echo $row['name']?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php

						if($row['start_date'] == $row['end_date'])
						{
							echo $row['start_date'];
						}
						else
						{
							echo $row['start_date'] . " &#8594; " . $row['end_date'];
						}
					?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?>
				</div>
			</li>

			<li>
				<button class = "button redbutton">
					Declined
				</button>
			</li>

			</ul>

			<?php endfor?>

			<ul class = "main_box">

			<li>
				<a href = "view.php?type=2"><button class = "button">View All Declined</button></a>
			</li>

			</ul>

		</div>

		</main>

		<footer></footer>

	</body>

</html>