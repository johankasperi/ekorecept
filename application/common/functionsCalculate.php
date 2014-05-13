<?php

function addRecipe() {
	$query = "INSERT INTO recipes (url, name, ingredients, servings)
    	VALUES ('".urldecode($_GET['source'])."', '".htmlspecialchars_decode(urldecode($_GET['name']))."', '".htmlspecialchars_decode(urldecode($_GET["ing"]))."', ".$_GET["port"].") 
		ON DUPLICATE KEY
		UPDATE name = VALUES (name), 
        ingredients = VALUES (ingredients),
        servings = VALUES (servings)";
    
   	sendQuery($query); 
}

function getRid() {
	$query = "SELECT rid FROM recipes WHERE url = '".$_GET['source']."'";
	$result = sendQuery($query);
	while ($line = $result->fetch_object()) {
		$rid = $line->rid;
	}
	return $rid;
}
?>