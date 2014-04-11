<?php
/*
	returns name of city based on POST variable
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
	$stmt = $mysqli->prepare("SELECT name FROM cities WHERE id =? LIMIT 1");
	$stmt->bind_param('s', $_POST["cityid"]);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($name);
	$arr = array();
	while ($row = $stmt->fetch()) {
		$arr[] = array('cityname' => trim($name));
	};

	echo json_encode($arr);
}

$mysqli = NULL;
?>