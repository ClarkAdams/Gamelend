<?php
/*
	returns all consle name and id from database
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

	$stmt = $mysqli->prepare("SELECT id, console FROM consoles");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $console);
	$arr = array();
	while ($row = $stmt->fetch()) {
		$arr[] = array('id' => $id, 'console' => $console);
	};
	
	echo json_encode($arr);

$mysqli = NULL;
?>