<?php
/*
	returns username and id (friends) based on logged in user
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {
	$id = $_SESSION['user_id'];
	$stmt = $mysqli->prepare("SELECT friend.friend AS id, members.username AS username FROM friend, members  
		WHERE friend.friend=members.id AND friend.userid=? AND status=2");
	$stmt->bind_param('s', $id);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $username);
	$arr = array();
	while ($row = $stmt->fetch()) {
		$arr[] = array('id' => $id, 'username' => $username);
	};

	echo json_encode($arr);
}

$mysqli = NULL;
?>