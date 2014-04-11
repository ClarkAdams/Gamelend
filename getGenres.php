<?php
/*
	retuns json with genres from database
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
	$stmt = $mysqli->prepare("SELECT id, genre FROM genres");
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $genre);
	$arr = array();
	while ($row = $stmt->fetch()) {
		$arr[] = array('id' => $id, 'genre' => $genre);
	};

	echo json_encode($arr);
}
$mysqli = NULL;
?>