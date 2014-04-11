<?php
/*
	retunrns json with counts of requests, games, friends, lentgames and borowed games
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

if (login_check($mysqli) == true) {
	$id = $_SESSION['user_id'];

	$stmt = $pdo -> prepare('SELECT count(userid) as total FROM friend WHERE userid=:1 AND status=1 AND requestFromUser!=:1');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$friendrequestcount = $stmt->fetch();

	$stmt = $pdo -> prepare('SELECT count(userID) as total FROM gamelibrary WHERE userID=:1 AND status=1');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$lendrequestcount = $stmt->fetch();

	$stmt = $pdo -> prepare('SELECT count(gameID) as total FROM gamelibrary WHERE userID=:1');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$librarycount = $stmt->fetch();

	$stmt = $pdo -> prepare('SELECT count(gameID) as total FROM gamelibrary WHERE userborrowid=:1 AND status=2');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$borrowcount = $stmt->fetch();

	$stmt = $pdo -> prepare('SELECT count(gameID) as total FROM gamelibrary WHERE userID=:1 AND status=2');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$lentcount = $stmt->fetch();

	$stmt = $pdo -> prepare('SELECT count(friend) as total FROM friend WHERE friend=:1 AND status=2');
	$stmt -> bindParam(':1', $id);
	$stmt -> execute();	
	$friendcount = $stmt->fetch();
	//	adds up requests
	$messagecount = $friendrequestcount["total"]+$lendrequestcount["total"];

	$arr = array('messagecount' => $messagecount, 
		'librarycount' => $librarycount["total"], 
		'borrowcount' => $borrowcount["total"], 
		'friendcount' => $friendcount["total"], 
		'lentcount' => $lentcount["total"]);
	echo json_encode($arr);
}

$mysqli = NULL;
?>