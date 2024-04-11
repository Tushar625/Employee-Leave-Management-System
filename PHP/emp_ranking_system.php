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

?>