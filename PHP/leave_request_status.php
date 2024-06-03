<?php

	function get_status_emoji($mg2consent)
	{
		switch($mg2consent)
		{
			case'A': return "<b><span class = 'green_text'>/ Approved</span></b>";	//"Approved";
			case'': return "<b><span class = 'blue_text'>/ Waiting ...</span></b>";	//"Waiting";
			default: return "<b><span class = 'red_text'>/ Declined</span></b>";	//"Declined";
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