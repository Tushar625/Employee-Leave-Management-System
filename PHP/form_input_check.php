<?php

	/*
		this function will check if given elements exists in get or post array
		returns true if all of them exists else return false
	*/
	
	function elements_exist($arr, $element_list)
	{
		for($i = 0; $i < count($element_list); $i++)
		{
			$str = $arr[$element_list[$i]];

			if(!isset($str) || strlen($str) == 0)
			{
				return false;
			}
		}

		return true;
	}

	function length_check($str, $length)
	{
		return strlen($str) == $length;
	}

	function max_length_check($str, $length)
	{
		return strlen($str) <= $length;
	}

	/*
		concate multiple url parameters back to back each other
	*/

	function url_parameters($get, $msg)
	{
		if(isset($get))
		{
			$get = "$get&$msg";
		}
		else
		{
			$get = "$msg";
		}

		return $get;
	}

?>