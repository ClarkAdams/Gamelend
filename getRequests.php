<?php
/*
	Returns lend and friendship requests based on logged in user
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

if (login_check($mysqli) == true) {
	$id = $_SESSION['user_id'];

	$stmt = $pdo -> prepare('SELECT friend as requestid FROM friend WHERE userid=:1 AND status=1 AND requestFromUser!=:1');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$array["friendrequest"] = $stmt->fetchAll();

	$stmt = $pdo -> prepare('SELECT id, gameID, name, userborrowid, platform FROM gamelibrary WHERE userID=:1 AND status=1');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$array["lendrequest"] = $stmt->fetchAll();

	echo json_encode($array);
}

$pdo = NULL;
$mysqli = NULL;
?>