<?php

	/*
		check if it's valid emp session or not if not redirect
		to index or home page
	*/

	include "../PHP/check_emp_session.php";
	
	include "../PHP/config.php";

	include "../PHP/mysql_sanitize_input.php";

	include "../PHP/leave_days.php";

	$max_size = 1;	// 1 MB

	$files_accepted = ".jpg, .jpeg, .png, .pdf";

	if(isset($_GET['lid']))
	{
		/*
			this file receives 'lid' of a leave via get method
			and prepares a form accordingly to accept a leave
			request
		*/

		$lid = mysql_sanitize_input($link, $_GET['lid']);

		$eid = $_SESSION['EMPLOYEE_ID'];

		// >>>> check if this leave exists and if we need supporting document for this leave

		$result = $link -> query("SELECT type, need_doc FROM leave_rule WHERE lid = $lid");

		if($result -> num_rows == 0)
		{
			// leave not exist hence, page not found

			header("HTTP/1.0 404 Not Found", true, 404);

			exit();
		}

		$tuple = $result -> fetch_assoc();

		// supporting doc and type of leave

		$need_doc = $tuple['need_doc'];
		
		$leave_name = $tuple['type'];

		// >>>> check if the employee can take this leave or not

		$days_avail = leave_days_remaining($link, $eid, $lid);

		if($days_avail <= 0)
		{
			// cannot take this leave

			die("You can't apply for this leave, head back to <a href = 'index.php'> Employee index </a>");
		}

		$link -> close();

		// now  we prepare the form
	}
	else if(isset($_POST['submit']))
	{
		/*
			After each successful submission we redirect to the same file to display
			the error message.
			
			GET variables are used to convey the error messages.

			Frontend (form) gets modified accordingly to those GET variables to display
			the errors in input data
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
				// if $err exists (error detected) we add it to $get

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

		/*
			>>>> if $get is created there is a problem and we reload the file by
			rediecting to itself, none of the input data are stored
		*/

		if(isset($get))
		{
			$link -> close();

			header("location: leave_request.php?lid=$lid&$get");

			exit();
		}
		
		/*
			>>>> no problem detected, hence we receive rest of the inputs
			and load them into leave request table
		*/

		// >>>> reason for leave

		$reason = mysql_sanitize_input($link, $_POST['reason']);

		// checking if we need to store the document or not

		if(isset($tname))	// $tname -> temporary location of the doc
		{
			// preparing the document for storage

			$fcontent = $link -> real_escape_string(file_get_contents($tname));

			$query = "INSERT INTO leave_request(eid, lid, start_date, end_date, reason, support_doc, ftype) VALUES($eid, $lid, '$start_date', '$end_date', '$reason', '$fcontent', '$ftype')";
		}
		else
		{
			$query = "INSERT INTO leave_request(eid, lid, start_date, end_date, reason) VALUES($eid, $lid, '$start_date', '$end_date', '$reason')";
		}

		// fail check

		if($link -> query($query) === false)
		{
			die("Form submission failure, head back to <a href = 'index.php'> Home </a>");
		}

		$link -> close();

		// success hence redirect to employee index
		
		// new waiting is kept on top, hence, "#navid0"

		header("location: index.php#navid0");		
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

		<title>Leave Request</title>

		<style>

			@import url("../CSS/general styles.css");

			@import url("../CSS/form styles.css");

			@import url("../CSS/header styles.css");

		</style>

	</head>
	
	<body>
		
		<header>
			<?php include "header.php";?>
		</header>

		<main>

		<!-- for forms sending images or documents we need, 'enctype = "multipart/form-data"' -->
			
		<form method = "post" action = "leave_request.php" enctype = "multipart/form-data">

		<!-- this is the only way to let backend know the 'lid' -->

		<input name = "lid" type = hidden value = <?php echo $lid?> required>
		
		<ul class = "main_box nice_shadow">

			<!-- Displaying the leave type and no. of days available (user friendly) -->

			<li>
				<div class = "info message">
					<?php echo "$leave_name, available $days_avail days"?>
				</div>
			</li>

			<li>
				<label> Reason <textarea name = "reason" maxlength = 500 required></textarea></label>
			</li>

			<!-- date inputs -->

			<li>
				<label> Start Date <input name = "start_date" type = date required> </label>
			</li>

			<li>
				<label> End Date <input name = "end_date" type = date required> </label>
			</li>

			<!-- date input errors -->

			<?php if(isset($_GET['days_valid'])) :?>

				<!-- the dates entered are invalid -->

				<li>
					<div class = "error message">
						Invalid dates
					</div>
				</li>

			<?php endif; ?>

			<!-- support doc input (only if required by the leave rule) -->

			<?php if($need_doc):?>

				<!-- leave rule need support doc -->

				<li>
					<label> Supporting Doc
						<div class = "file">
							<input type = "hidden" name = "MAX_FILE_SIZE" value = <?php echo ($max_size * 1024 * 1024)?>>
							<input type = "file" name = "document" accept = "<?php echo $files_accepted?>" required>
						</div>
					</label>
				</li>

				<!-- max size message -->

				<li>
					<div class = "error message">
						<em><?php echo $files_accepted?></em> accepted, <?php echo $max_size?> MB or less
					</div>
				</li>

				<!-- doc input errors -->

				<!-- transmission error (i.e., file not sent properly) -->

				<?php if(isset($_GET['file_transmission_valid'])) :?>

					<li>
						<div class = "error message">
							Can't load the file, it may be too large
						</div>
					</li>

				<?php endif; ?>

				<!-- file type error -->

				<?php if(isset($_GET['file_type_valid'])) :?>

					<li>
						<div class = "error message">
							Invalid file type
						</div>
					</li>

				<?php endif; ?>

				<!-- file size error -->

				<?php if(isset($_GET['file_size_valid'])) :?>

					<li>
						<div class = "error message">
							File too large
						</div>
					</li>

				<?php endif; ?>

			<?php else:?>

				<!-- leave rule doesn't need support doc -->

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