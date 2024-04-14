<?php

	function get_rank($ranks)
	{
		switch($ranks)
		{
			case 0: return "Employee"; break;

			case 1: return "Manager1"; break;

			case 2: return "Manager2"; break;

			case 3: return "HR"; break;
		}
	}

	// accepts integer rank and returns name of the folder containing the section

	function get_rank_section($ranks)
	{
		switch($ranks)
		{
			case 0: return "employee"; break;

			case 1:

			case 2: return "manager"; break;

			case 3: return "hr"; break;
		}
	}

	function is_manager($ranks)
	{
		switch($ranks)
		{
			case 0: return false; break;

			case 1:

			case 2: return true; break;

			case 3: return false; break;
		}
	}

?>