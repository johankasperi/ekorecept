<?php

include "../database/connect.php";
$iid = $_GET["iid"];
$name = htmlspecialchars_decode($_GET["name"]);
$rid = $_GET["rid"];

if(strpos($name,",") !== False) {
	$name = substr($name, 0, strpos($name, ","));
}

if(strpos($name,"(") !== False) {
	$name = substr($name, 0, strpos($name, "("));
}

$query = "INSERT INTO ingAlt (iid, alt)
		  VALUES (".$iid.", '".$name."')";

sendQuery($query);

header("Location: /recept.php?rid=".$rid."#ingredients");
?>