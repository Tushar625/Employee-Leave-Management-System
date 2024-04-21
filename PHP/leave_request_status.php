<?php

	function get_status_emoji($mg2consent)
	{
		switch($mg2consent)
		{
			case'A': return "&#x1F44D;";	//"Approved";
			case'': return "&#128580;";	//"Waiting";
			default: return "&#x1F44E;";	//"Declined";
		}
	}

	function get_status_color($mg2consent)
	{
		switch($mg2consent)
		{
			case'A': return "green";	//"Approved"
			case'': return "blue";	//"Waiting"
			default: return "red";	//"Declined"
		}
	}

?>