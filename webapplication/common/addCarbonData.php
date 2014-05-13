<?php

include("../database/connect.php");

$carbon = $_GET["carbon"];
$source = $_GET["source"];
$country = $_GET["country"];
$iid = $_GET["iid"];
$rid = $_GET["rid"];

$query = "INSERT INTO carbon (iid, carbonGram, source, country)
VALUES(".$iid.", ".$carbon.", '".$source."', '".$country."')";
sendQuery($query);

header("Location: /ingredient.php?iid=".$iid."&rid=".$rid);
?>


