
<?php

	include "PHP/check_user_session.php";

	include "PHP/game ranking system.php";

	if(!isset($_SESSION['outcome']))
	{
		// invalid access (session variable outcome not found)

		/*
			if get variable is given we return to proper quiz outcome
		*/

		$id = (isset($_GET["qid"])) ? "#quiz" . $_GET["qid"] : "";

		die("Initializing failure, head back to <a href = 'detailed history.php$id'> History </a>");
	}
	else
	{
		// session variable outcome found and we extract data from it

		$pname = $_SESSION["outcome"]['pname'];

		/*
			I have found that some pokemons have no images so we change
			their name with the closest related pokemon names that has
			image corresponding to them
		*/

		switch($pname)
		{
			case "minior-red-meteor": $pname = "minior"; break;
		}

		$hints_used = $_SESSION["outcome"]['hints_used'];
		$solved = $_SESSION["outcome"]['solved'];

		unset($_SESSION["outcome"]);
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>outcome</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

			@import url("CSS/header styles.css");

			@import url("CSS/list styles.css");

			.main_box li .message
			{
				box-shadow: none;
				text-align: center;
			}

			.unfinished
			{
				filter: blur(20px) grayscale(100%);
				-webkit-filter: blur(20px) grayscale(100%);
			}

			.failure
			{
				filter: grayscale(100%);
				-webkit-filter: grayscale(100%);
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

				<?php if(!($_SESSION['QUIZ_ID'] == $_GET["qid"] && $solved == -1)):?>

					<!-- display the image only if it's not unfinished current quiz -->

					<?php

						// selecting proper filter for the image
					
						$filter = "";
					
						if($solved == -1)
						{
							$filter = "unfinished";
						}
						elseif($solved == 0)
						{
							$filter = "failure";
						}

					?>

					<img class = "<?php echo "$filter message";?>" src = <?php echo "https://img.pokemondb.net/artwork/large/$pname.jpg"; ?> alt = <?php echo $pname?>>

				<?php endif;?>

			</li>

			<li>
				<div class = "message">
					<?php print_stars($hints_used, $solved);?>
				</div>
			</li>

			<li>
				<div class = "message">
					<?php echo get_rank($hints_used, $solved);?>
				</div>
			</li>

			<li>
				<!-- outcome init.php always provides the qid to this file via GET method -->

				<a href = "<?php echo "detailed history.php#quiz" . $_GET["qid"]?>"><button class = "button">
					Detailed History
				</button></a>
			</li>

			<li>
				<a href = "user profile.php"><button class = "button">
					Profile
				</button></a>
			</li>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>