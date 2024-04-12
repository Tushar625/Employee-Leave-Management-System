<?php

	/*
		check if it's valid admin session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_emp_session.php";
	
	include "../PHP/config.php";

	include "../PHP/mysql_sanitize_input.php";

	include "../PHP/leave_days.php";

	$max_size = 1;	// 1 MB

	$files_accepted = ".jpg, .jpeg, .png, .pdf";

	// Empdetails input

	if(isset($_GET['lid']))
	{
		$lid = mysql_sanitize_input($link, $_GET['lid']);

		$eid = $_SESSION['EMPLOYEE_ID'];

		// >>>> check if this leave exists and if we need supporting document for this leave

		$result = $link -> query("SELECT name, need_doc FROM leave_rule WHERE lid = $lid");

		if($result -> num_rows == 0)
		{
			// leave not exist

			die("Invalid leave request, head back to <a href = 'index.php'> Employee index </a>");
		}

		$tuple = $result -> fetch_assoc();

		$need_doc = $tuple['need_doc'];

		$leave_name = $tuple['name'];

		// >>>> check if the employee can take this leave or not

		$days_avail = leave_days_remaining($link, $eid, $lid);

		if($days_avail <= 0)
		{
			// cannot take leave

			die("You can't apply for this leave, head back to <a href = 'index.php'> Employee index </a>");
		}

		$link -> close();
	}
	else if(isset($_POST['submit']))
	{
		// here we use get variables to send error messages while redirecting to itself

		/*
			After each successful submission we redirect to the same form to display
			the error or success message. As the inputs submitted here will be stored
			into the login file, redirection is used to ensure that no resubmission
			error will be generated upon reload or back. (More about this error in the
			documentation)

			While redirecting we use get method to send the error or success message.
		*/

		$lid = mysql_sanitize_input($link, $_POST['lid']);

		$eid = $_SESSION['EMPLOYEE_ID'];

		// >>>> checking the input dates

		$start_date = mysql_sanitize_input($link, $_POST['start_date']);

		$end_date = mysql_sanitize_input($link, $_POST['end_date']);

		$requested_days = count_leave_days($start_date, $end_date);

		// check if start > end date or requested_days are more than remaining days

		if($requested_days <= 0 || $requested_days > leave_days_remaining($link, $eid, $lid))
		{
			// dates are not valid

			$get = "days_valid=false";
		}

		// >>>> checking the doc

		if(isset($_FILES['document']))
		{
			// getting file data

			$fname = $_FILES['document']['name'];
			$fsize = $_FILES['document']['size'];
			$error = $_FILES['document']['error'];
			$tname = $_FILES['document']['tmp_name'];	// imp

			// getting file type (extension)

			$ftype = explode('.', $fname);

			$ftype = strtolower(end($ftype));	// imp

			// checks

			if($error !== 0)
			{
				$err = "file_transmission_valid=false";
			}
			else if(!in_array(".$ftype", explode(', ', $files_accepted)))
			{
				$err = "file_type_valid=false";
			}
			else if($fsize > ($max_size * 1024 * 1024))
			{
				$err = "file_size_valid=false";
			}

			if(isset($err))
			{
				if(isset($get))
				{
					$get = "$get&$err";
				}
				else
				{
					$get = $err;
				}
			}
		}

		// if get is created there is a problem and we reload the file

		if(isset($get))
		{
			$link -> close();

			header("location: leave_request.php?lid=$lid&$get");

			exit();
		}
		
		/*
			no problem detected, hence we receive rest of the inputs
			and load them into leave request table
		*/

		// checking if we need to store the document or not

		if(isset($tname))
		{
			// preparing the document for storage

			$fcontent = $link -> real_escape_string(file_get_contents($tname));

			$query = "INSERT INTO leave_request(eid, lid, start_date, end_date, support_doc, ftype) VALUES($eid, $lid, '$start_date', '$end_date', '$fcontent', '$ftype')";
		}
		else
		{
			$query = "INSERT INTO leave_request(eid, lid, start_date, end_date) VALUES($eid, $lid, '$start_date', '$end_date')";
		}

		// fail check

		if($link -> query($query) === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		// success hence redirect to employee index
		
		header("location: index.php");		
	}
	else
	{
		header("location: index.php");
	}

?>

<!DOCTYPE html>

<html lang = "en">

	<head>

		<meta charset = "UTF-8">

		<title>leave_rules</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

		</style>

	</head>
	
	<body>

		<!--
			We don't keep any return to home button here to discourage
			user from accidentally return from registration form, I want
			him to create an account successfully and then login to his
			profile and play
		-->
		
		<header>
			<?php include "header.php";?>
		</header>

		<main>
			
		<form method = "post" action = "leave_request.php" enctype = "multipart/form-data">

		<input name = "lid" type = hidden value = <?php echo $lid?> required>
		
		<ul class = "main_box nice_shadow">

			<!-- Maxlength is set according to size of uname field in login table -->

			<li>
				<div class = "info message">
					<?php echo "$leave_name, available $days_avail days"?>
				</div>
			</li>

			<li>
				<label> Start Date <input name = "start_date" type = date required> </label>
			</li>

			<li>
				<label> End Date <input name = "end_date" type = date required> </label>
			</li>

			<?php if(isset($_GET['days_valid'])) :?>

				<!-- the max days entered is invalid -->

				<li>
					<div class = "error message">
						Invalid dates
					</div>
				</li>

			<?php endif; ?>

			<!-- doc -->

			<?php if($need_doc):?>

				<li>
					<label> Doc (<?php echo $files_accepted?>) 
						<div class = "file">
							<input type = "hidden" name = "MAX_FILE_SIZE" value = <?php echo ($max_size * 1024 * 1024)?>>
							<input type = "file" name = "document" accept = "<?php echo $files_accepted?>" required>
						</div>
					</label>
				</li>

				<li>
					<div class = "error message">
						Below <?php echo $max_size?> MB
					</div>
				</li>

				<?php if(isset($_GET['file_transmission_valid'])) :?>

					<li>
						<div class = "error message">
							Can't load the file, it may be too large
						</div>
					</li>

				<?php endif; ?>

				<?php if(isset($_GET['file_type_valid'])) :?>

					<li>
						<div class = "error message">
							Invalid file type
						</div>
					</li>

				<?php endif; ?>

				<?php if(isset($_GET['file_size_valid'])) :?>

					<li>
						<div class = "error message">
							File too large
						</div>
					</li>

				<?php endif; ?>

			<?php else:?>

				<li>
					<div class = "info message">
						No supporting document needed
					</div>
				</li>

			<?php endif?>

			<li>
				<input class = "button" type = "submit" name = "submit" value = "Request Leave">
			</li>

		</ul>

		</form>

		</main>

		<footer></footer>

	</body>

</html>