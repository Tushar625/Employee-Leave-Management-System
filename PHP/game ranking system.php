<?php

	function get_rank($hints_used, $solved)
	{
		if($solved == 0)
		{
			return "Failure";
		}
		elseif($solved == -1)
		{
			return "Not Finished";
		}

		switch($hints_used)
		{
			case 1: return "Master"; break;

			case 2: return "Champion"; break;

			case 3: return "Elite 4"; break;

			case 4: return "Gym leader"; break;

			case 5: return "Trainer"; break;
		}
	}

	function print_stars($hints_used, $solved)
	{
		$total_stars = 5;
						
		if($solved == 1)
		{
			$achieved_stars = $total_stars - $hints_used + 1;

			$remaining = $total_stars - $achieved_stars;

			for($i = 1; $i <= $achieved_stars; $i++)
				echo "&#x2605 ";

			for($i = 1; $i <= $remaining; $i++)
				echo "&#x2606 ";
		}
		else
		{
			for($i = 1; $i <= $total_stars; $i++)
				echo "&#x2606 ";
		}
	}

?>