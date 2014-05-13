<?php
$feed_url = 'http://carbon.to/'.$_GET['unit'].'.json?co2='.$_GET['carbon'];

$session = curl_init($feed_url);
curl_setopt($session, CURLOPT_RETURNTRANSFER, true);
$data = curl_exec($session);
curl_close($session);

$search_results = json_decode($data);
if ($search_results === NULL) die('Error parsing json');
$items = '';
	
foreach ($search_results as $item) {
	$items .= $item->amount." ";
	$items .= $item->unit;
}
echo $items;
?>