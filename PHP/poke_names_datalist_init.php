<?php

	$page = "<datalist id = 'poke_names'>\n";

	// API endpoint to get all Pokemon
	
	$url = "https://pokeapi.co/api/v2/pokemon/?offset=0&limit=1025";
	
	// Get the JSON data from the API
	
	$response = file_get_contents($url);
	
	$data = json_decode($response, true);

	// Extract Pokemon names from the results
	
	foreach ($data['results'] as $result)
	{
		$page = $page . "<option value = '" . $result['name'] . "' >\n";
	}

	$page = $page . "</datalist>";

	file_put_contents("PHP/poke_names_datalist.php", $page);

?>