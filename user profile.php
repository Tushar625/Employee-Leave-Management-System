<?php

	include "PHP/check_user_session.php";

	include "PHP/config.php";

	$uid = $_SESSION['USER_ID'];

	$current_qid = $_SESSION['QUIZ_ID'];

	// total no. of quizes published == no. of quizes in quiz table where qid <= $current_qid

	$quiz_count = $link -> query("SELECT * FROM quiz WHERE qid <= $current_qid;") -> num_rows;

	// counting success, failure, incomplete and attained

	$success = 0;

	$failed = 0;

	$incomplete = 0;

	$result = $link -> query("SELECT solved, count(*) FROM score WHERE uid = $uid GROUP BY solved;");

	if($result -> num_rows > 0)
	{
		// some entries found
		
		while($tuple = $result -> fetch_array(MYSQLI_NUM))
		{
			switch($tuple[0])
			{
				case -1: $incomplete = $tuple[1]; break;

				case 0: $failed = $tuple[1]; break;

				case 1: $success = $tuple[1]; break;
			}
		}
	}

	$attained = $incomplete + $failed + $success;

	// counting achievements

	$master = 0;

	$champion = 0;

	$elite_4 = 0;

	$gym_leader = 0;

	$trainer = 0;

	$result = $link -> query("SELECT hints_used, count(*) FROM score WHERE solved = 1 AND uid = $uid GROUP BY hints_used;");

	if($result -> num_rows > 0)
	{
		// some entries found
		
		while($tuple = $result -> fetch_array(MYSQLI_NUM))
		{
			switch($tuple[0])
			{
				case 1: $master = $tuple[1]; break;

				case 2: $champion = $tuple[1]; break;

				case 3: $elite_4 = $tuple[1]; break;

				case 4: $gym_leader = $tuple[1]; break;

				case 5: $trainer = $tuple[1]; break;
			}
		}
	}

	$link -> close();

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>user_profile</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

			@import url("CSS/list styles.css");

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

			<?php include "PHP/user_header.php";?>

		</header>

		<main>
		
		<ul class = "main_box nice_shadow">

			<li>
				<div class = "message">
					Total quiz aired: <?php echo $quiz_count?>
				</div>
			</li>
			
			<li>
				<div class = "message">
					<span>Attained</span>
					<span class = "left_bar"><?php echo $attained . " / " . $quiz_count?> <progress max = '<?php echo $quiz_count?>' value = '<?php echo $attained?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">					
					<span class = "rank">Master</span>
					<span class = "left_bar"><?php echo $master . " / " . $success?> <progress max = '<?php echo $success?>' value = '<?php echo $master?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">					
					<span class = "rank">Champion</span>
					<span class = "left_bar"><?php echo $champion . " / " . $success?> <progress max = '<?php echo $success?>' value = '<?php echo $champion?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">					
					<span class = "rank">Elite 4</span>
					<span class = "left_bar"><?php echo $elite_4 . " / " . $success?> <progress max = '<?php echo $success?>' value = '<?php echo $elite_4?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">					
					<span class = "rank">Gym leader</span>
					<span class = "left_bar"><?php echo $gym_leader . " / " . $success?> <progress max = '<?php echo $success?>' value = '<?php echo $gym_leader?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">					
					<span class = "rank">Trainer</span>
					<span class = "left_bar"><?php echo $trainer . " / " . $success?> <progress max = '<?php echo $success?>' value = '<?php echo $trainer?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">
					<span>Success</span>
					<span class = "left_bar"><?php echo $success . " / " . $attained?> <progress max = '<?php echo $attained?>' value = '<?php echo $success?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">
					<span>Failure</span>
					<span class = "left_bar"><?php echo $failed . " / " . $attained?> <progress max = '<?php echo $attained?>' value = '<?php echo $failed?>'></progress></span>
				</div>
			</li>

			<li>
				<div class = "message">					
					<span>Incomplete</span>
					<span class = "left_bar"><?php echo $incomplete . " / " . $attained?> <progress max = '<?php echo $attained?>' value = '<?php echo $incomplete?>'></progress></span>
				</div>
			</li>

			<li>
				<a href = "detailed history.php"><button class = "button">
					Detailed History
				</button></a>
			</li>

			<li>
				<!-- disable play button if user has already played the game of today -->
				
				<a href = "game.php"><button class = "button" <?php if(!$_SESSION['can_play']) echo "disabled"; ?>>
					Play
				</button></a>
			</li>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>