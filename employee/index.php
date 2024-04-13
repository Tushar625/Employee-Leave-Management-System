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

		<div class = "main_box nice_shadow">
		
		<ul class = "main_box main_box1">

			<!-- getting the tuples in leave rule table -->

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>
			
			<li>
				<?php $used_days = used_leave_days($link, $eid, $row['lid'])?>
				<div class = "<?php echo ($used_days == $row['days']) ? "message redbutton" : "message greenbutton"?>">
					<span><?php echo $row['name']?></span>
					
					<span class = "left_bar"><?php echo $used_days . "/" . $row['days']?> <progress max = '<?php echo $row['days']?>' value = '<?php echo $used_days?>'></progress></span>
				</div>
			</li>

			<?php endfor?>

		</ul>

		<ul class = "main_box main_box1">

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

		<?php if($result -> num_rows):?>

			<!-- <ul class = "main_box">

				<li>
					<button class = "button bluebutton">
						Waiting
					</button>
				</li>

			</ul> -->

		<?php endif?>

		<!-- Display pending leave requests -->

		<!-- <div class = "main_box nice_shadow"> -->

			<?php for(;$row = $result -> fetch_assoc(); $nav_index++): /* index of the record read */?>

			<?php
				$lid = $row['lid'];

				$lrid = $row['lrid'];
			?>

			<ul id = "<?php echo "navid$nav_index"?>" class = "main_box blue_box">
			
			<li>
				<div class = "message">

					<a href = "<?php echo "delete.php?navid=$nav_index&lid=$lid&lrid=$lrid"?>">&#10006;</a>
					
					<?php echo "&nbsp;" . $row['name'] . "&nbsp;"?>

					<?php if($row['need_doc'] == true):?>
					
						<a href = "<?php echo "support_doc.php?lid=$lid&lrid=$lrid"?>">&#128209;</a>
					
					<?php endif?>

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
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?> &#129320;
				</div>
			</li>

			<!-- <li>
				<button class = "button bluebutton">
					Waiting
				</button>
			</li> -->

			<!-- <li>
				<?php if($row['need_doc'] == true):?>
					
					<a href = "<?php echo "support_doc.php?lid=$lid&lrid=$lrid"?>"><button class = 'button bluebutton'> Document </button></a>
				
				<?php else:?>
					
					<button class = 'button whitebutton' disabled> No Document </button>

				<?php endif?>
			</li> -->

			<!-- <li>
				
				<a href = "<?php echo "delete.php?navid=$nav_index&lid=$lid&lrid=$lrid"?>"><button class = 'button redbutton'> Delete </button></a>
			
			</li> -->

			</ul>

			<?php endfor?>

			<?php if($nav_index === 0):?>

				<!-- <ul class = "main_box">

				<li>
					<button class = "button whitebutton">No Leave Request Pending</button>
				</li> -->

			<?php endif?>

			</ul>


		<!-- </div> -->

		<?php

			// last 3 approved ones

			$query = "SELECT name, start_date, end_date, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent = 'A' order by lrid desc limit 3";
		
			$result = $link -> query($query);

		?>

		<!-- Display last 3 approved leave requests -->

		<!-- <ul class = "main_box">

			<li>
				<button class = "button greenbutton">
					Approved
				</button>
			</li>

		</ul> -->

		<!-- <div class = "main_box nice_shadow"> -->

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>

			<ul class = "main_box green_box">
			
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
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?> &#x1F44D;
				</div>
			</li>

			</ul>

			<?php endfor?>

			<?php if($result -> num_rows):?>

				<ul class = "main_box">

					<li>
						<a href = "view.php?type=1"><button class = "button greenbutton">All Approved</button></a>
					</li>

				</ul>

			<?php endif?>

		<!-- </div> -->

		<?php

			// last 3 declined ones

			$query = "SELECT name, start_date, end_date, need_doc FROM leave_request NATURAL JOIN leave_rule WHERE eid = $eid AND mg2_consent <> 'A' AND mg2_consent is NOT NULL order by lrid desc limit 3";
		
			$result = $link -> query($query);

			$link -> close();

		?>

		<!-- Display last 3 declined leave requests -->

		<!-- <ul class = "main_box">

			<li>
				<button class = "button redbutton">
					Declined
				</button>
			</li>

		</ul> -->

		<!-- <div class = "main_box nice_shadow"> -->

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>

			<ul class = "main_box red_box">
			
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
					<?php echo count_leave_days($row['start_date'], $row['end_date']) . (($row['start_date'] == $row['end_date']) ? " Day" : " Days")?> &#x1F44E;
				</div>
			</li>

			</ul>

			<?php endfor?>

			<?php if($result -> num_rows):?>

				<ul class = "main_box">

					<li>
						<a href = "view.php?type=2"><button class = "button redbutton">All Declined</button></a>
					</li>

				</ul>

			<?php endif?>

		</div>

		</main>

		<footer></footer>

	</body>

</html>