<?php
/*
	retunrnes all citie names and id from databse
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

	$stmt = $mysqli->prepare("SELECT id, name FROM cities");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $name);
	$arr = array();
	while ($row = $stmt->fetch()) {
		$arr[] = array('id' => $id, 'name' => $name);
	};
	
	echo json_encode($arr);


$mysqli = NULL;
?>