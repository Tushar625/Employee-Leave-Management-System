<?php

	function count_leave_days($start_date, $end_date)
	{
		$start_date = strtotime($start_date);

		$end_date = strtotime($end_date);

		return round(($end_date - $start_date) / (60 * 60 * 24)) + 1;
	}

	function total_leave_days($link, $lid)
	{
		// total no. of days for this leave

		$result = $link -> query("SELECT days FROM leave_rule WHERE lid = $lid");

		if($result -> num_rows == 0)
		{
			// leave not exist

			return 0;
		}

		return $result -> fetch_assoc()['days'];
	}

	function used_leave_days($link, $eid, $lid)
	{
		// total no. of days of this leave are used by this employee (approved or requested leaves)

		$query = "SELECT SUM(days) AS total_days FROM (SELECT DATEDIFF(end_date, start_date) + 1 AS days FROM leave_request WHERE eid = $eid AND lid = $lid AND (mg2_consent = 'A' OR mg2_consent is NULL)) AS days_table";

		$result = $link -> query($query);

		$used_days = $result -> fetch_assoc()['total_days'];

		if($result -> num_rows == 0 || $used_days == false)
		{
			// leave not taken

			return 0;
		}

		return $used_days;
	}

	function leave_days_remaining($link, $eid, $lid)
	{
		return total_leave_days($link, $lid) - used_leave_days($link, $eid, $lid);
	}

?>