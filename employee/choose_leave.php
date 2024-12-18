<?php

	include_once "../PHP/check_emp_session.php";

	include_once "../PHP/config.php";

	include_once "../PHP/leave_days.php";

	$eid = $_SESSION['EMPLOYEE_ID'];

	$result = $link -> query("SELECT lid, type, days FROM leave_rule");

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>Choose Leave</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

			@import url("../CSS/list styles.css");

		</style>

	</head>
	
	<body>
		
		<header>

			<?php include_once "header.php";?>

		</header>

		<main>
		
		<ul class = "main_box nice_shadow">

			<!-- getting the tuples in leave rule table -->

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>
			
			<li>
					
				<?php
					$lid = $row['lid'];
					$remaining_days = $row['days'] - used_leave_days($link, $eid, $lid);
				?>

				<!-- 
					here we calculate the remaining days of each leave and disable the corresponding
					leave request button if no days remains

					blue shadow -> active button

					red shadow -> inactive button
				 -->

				<?php if($remaining_days > 0):?>

					<a href = "<?php echo "leave_request.php?lid=$lid"?>"><button class = 'button bluebutton'> <?php echo $row['type']?> </button></a>

				<?php else:?>

					<button class = 'button redbutton' disabled> <?php echo $row['type']?> </button>

				<?php endif?>
				
			</li>

			<?php endfor?>
				
		</ul>

		<?php $link -> close()?>

		</main>

		<footer></footer>

	</body>

</html>