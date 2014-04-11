<?php
/*
	returns users registered games from gamelibrary table using logged in userID
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
	$userID = $_SESSION['user_id'];

	$stmt = $mysqli->prepare("SELECT gameID FROM gamelibrary WHERE userID=? ");
	$stmt->bind_param('s', $userID);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id);
	$arr = array();
	//	populates array with results from query
	while ($row = $stmt->fetch()) {
		$arr[] = array('gameID' => $id);
	};

	echo json_encode($arr);
}

$mysqli = NULL;
?>