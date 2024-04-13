<?php

	function std_date_format($str_date)
	{
		return date("d/m/Y", strtotime($str_date));
	}

?>