<?php

	/*
		here a message can be displayed along with a OK button (attached to a url)
		the message content and url will be send via session variables
	*/

	include "PHP/check_destroy_secure_session.php";

	if(check_secure_session() == false)
	{
		// no active session exists

		header("location: ../index.php");

		exit();
	}

	if(!(isset($_SESSION["msg"]) && isset($_SESSION["url"]) && isset($_SESSION["error"])))
	{
		// nothing to display hence, display empty message

		$_SESSION["msg"] = "Hello, no message found, click <b>OK</b>.";
				
		$_SESSION["url"] = $_SERVER['HTTP_REFERER'];
		
		$_SESSION["error"] = false;
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
				<?php $type = ($_SESSION["error"]) ? "error" : "info";?>
				
				<div class = "<?php echo $type?> message">
					
					<?php echo $_SESSION["msg"]?>
					
				</div>

			</li>	
			
			<li>
				<a href = "<?php echo $_SESSION["url"]?>"><button class = "button">OK</button></a>
			</li>

			<?php
				unset($_SESSION["msg"]);
				
				unset($_SESSION["url"]);
				
				unset($_SESSION["error"]);
			?>

		</ul>

		</main>

		<footer></footer>

	</body>

</html>