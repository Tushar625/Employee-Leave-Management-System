<?php

	// getting time stamp of start date (day on which first quiz goes live)

	// $start_date = mktime(0, 0, 0, 3, 31, 2024);

	// $start_date = mktime(0, 0, 0, 2, 20, 2024);

	$start_date = mktime(0, 0, 0, 4, 6, 2024);

	$days_passed = (int)((time() - $start_date) / (24 * 3600));

	// id of current quiz is days passed after starting date + 1

	$current_qid = $days_passed + 1;

?>