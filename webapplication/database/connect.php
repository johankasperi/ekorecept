<?php

function sendQuery($query) {	
    $GLOBALS["connection"] = mysqli_connect();
	$connection = $GLOBALS["connection"];
    if (mysqli_connect_errno()) {
        printf("Connect failed: %s\n", mysqli_connect_error());
        exit();
    }
    mysqli_set_charset($connection, 'utf8');
	if (($result = mysqli_query($connection, $query)) === false) {
		printf("Query failed: %s<br />\n%s", $query, mysqli_error($link));
		exit();
	}
		
	return $result;
	mysqli_free_result($result);
	mysqli_close($connection);
}
?>