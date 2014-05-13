<?php
include "../database/connect.php";
$q = $_GET["q"];
$name = urldecode($_GET["name"]);
$rid = $_GET["rid"];
$query = "SELECT iid, name FROM ingredients WHERE name LIKE '%".$q."%' LIMIT 10";

$result = sendQuery($query);
$hint = "";
while ($line = $result->fetch_object()) {
	$hint .= "<li><a href='/common/add.php?iid=".$line->iid."&name=".urlencode($name)."&rid=".$rid."'>".$line->name.'</a></li>';
}

if ($hint=="") {
  $response="<div class='hint-title'>Inga träffar</div>";
}
else {
  $response="<div class='hint-title'>Välj ingrediens i listan nedan</div><ul id='livesearch'>".$hint."</ul></div>";
}

//output the response
echo $response;
?>