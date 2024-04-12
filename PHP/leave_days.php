<?php

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
		// total no. of days this leave used by this employee (approved or requested leaves)

		$result = $link -> query("SELECT count(*) as days FROM leave_request WHERE eid = $eid AND lid = $lid AND (mg2_consent = 'A' OR mg2_consent is NULL)");

		if($result -> num_rows == 0)
		{
			// leave not taken

			return 0;
		}

		return $result -> fetch_assoc()['days'];
	}

	function leave_days_remaining($link, $eid, $lid)
	{
		return total_leave_days($link, $lid) - used_leave_days($link, $eid, $lid);
	}

?>