<?php

	/*
		here a message can be displayed along with a OK button (attached to a url)
		the message content and url will be send via session variables
	*/

	include_once "PHP/check_destroy_secure_session.php";

	if(check_secure_session() == false)
	{
		// no active session exists

		header("location: index.php");

		exit();
	}

	if(isset($_SESSION["msg"]) && isset($_SESSION["url"]) && isset($_SESSION["error"]))
	{
		$msg = $_SESSION["msg"];

		$url = $_SESSION["url"];

		$error = $_SESSION["error"];

		unset($_SESSION["msg"]);
				
		unset($_SESSION["url"]);
				
		unset($_SESSION["error"]);
	}
	else
	{
		// nothing to display as, no parameters given

		$msg = "Hello, no message found.";

		// if no previous page is found empty string is assigned as URL
				
		$url = (isset($_SERVER['HTTP_REFERER'])) ? $_SERVER['HTTP_REFERER'] : "";
		
		$error = false;
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>Consent</title>

		<style>

			@import url("CSS/general styles.css");

			@import url("CSS/form styles.css");

		</style>

	</head>
	
	<body>

		<main>
		
		<ul class = "main_box nice_shadow">
			
			<li>
				<?php $type = ($error) ? "error" : "info";?>
				
				<div class = "<?php echo $type?> message">
					
					<?php echo $msg?>
					
				</div>

			</li>	
			
			<!-- the button is disabled if no URL is found -->

			<li>
				<a href = "<?php echo $url?>"><button class = "button" <?php if($url == '') echo "disabled"?>>OK</button></a>
			</li>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>