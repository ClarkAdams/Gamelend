<?php
/*
	registers a request to borrow a game in the database based on the loged in user and gameID
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$gameID = $_POST["gameID"];
$friendID = $_POST["friendID"];

$status["status"] = "";

try {
	if (login_check($mysqli) == true) {
		if (!empty($gameID) && !empty($friendID)) {
			// checks so no request is already made
			$stmt = $pdo -> prepare('SELECT count(*) as total FROM gamelibrary WHERE userID = :1 AND userborrowid = :2 AND gameid = :3 AND status != 2 AND status != 1');
			$stmt -> bindParam(':1', $friendID);
			$stmt -> bindParam(':2', $userID);
			$stmt -> bindParam(':3', $gameID);
			$stmt -> execute();	
			$array = $stmt->fetch();
			// if no request array["total"] is 0
			if ($array["total"]==0) {
				$stmt = $pdo -> prepare('UPDATE `gamelibrary` SET `status`=1,`userborrowid`=:1 WHERE gameID = :3 AND userID = :2');
				$stmt -> bindParam(':1', $userID);
				$stmt -> bindParam(':2', $friendID);
				$stmt -> bindParam(':3', $gameID);
				$stmt -> execute();

				$status["status"]= "success";
				echo json_encode($status);
			} else {
				$status["status"]= "Lendrequest already sent";
				echo json_encode($status);
			}
		} else {
			$status["status"]= "no value";
			echo json_encode($status);
		}	
	} else {
		$status["status"]= "not loged in";
		echo json_encode($status);
	}
} catch(Exeption $e) {
	$status["status"]= $e;
	echo json_encode($status);
}

$pdo = NULL;
$mysqli = NULL;
?>