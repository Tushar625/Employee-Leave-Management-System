<?php

	include "../PHP/check_emp_session.php";

	include "../PHP/config.php";

	include "../PHP/leave_days.php";

	$eid = $_SESSION['EMPLOYEE_ID'];

	$result = $link -> query("SELECT lid, name, days FROM leave_rule");

	// $link -> close();

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

			.main_box > * .button, .main_box > * .message
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
		
		<ul class = "main_box nice_shadow">

			<!-- getting the tuples in leave rule table -->

			<?php for(;$row = $result -> fetch_assoc();): /* index of the record read */?>
			
			<li>
				<?php $used_days = used_leave_days($link, $eid, $row['lid'])?>
				<div class = "<?php echo ($used_days == $row['days']) ? "message redbutton" : "message"?>">
					<span><?php echo $row['name']?></span>
					
					<span class = "left_bar"><?php echo $used_days . " / " . $row['days']?> <progress max = '<?php echo $row['days']?>' value = '<?php echo $used_days?>'></progress></span>
				</div>
			</li>

			<?php endfor?>

		</ul>

		<ul class = "main_box nice_shadow">

			<li>
				<a href = "<?php echo "update.php?id=$id&type=$type"?>"><button class = 'button bluebutton'> Leave History </button></a>
			</li>

			<!-- delete buttom -->

			<li>
				<a href = "choose_leave.php"><button class = 'button bluebutton'> Leave Request </button></a>
			</li>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>