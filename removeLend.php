<?php
/*
	updates library entity to status and userborrowid to 0
	based on logged in user and posted data
*/
header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$dataArray = explode("lend",$_POST["gameIDfriendID"]);
$friendID = $dataArray[0];
$gameID = $dataArray[1];
$status["status"] = "";

try {
	if (login_check($mysqli) == true) {
		if (!empty($gameID) && !empty($friendID)) {
			// queries if game, users and status represents a lend
			// if a lend is registered 1 is returned
			$stmt = $pdo -> prepare('SELECT count(*) as total FROM gamelibrary WHERE userID = :2 AND userborrowid = :1 AND gameid = :3 AND status = 2');
			$stmt -> bindParam(':1', $friendID);
			$stmt -> bindParam(':2', $userID);
			$stmt -> bindParam(':3', $gameID);
			$stmt -> execute();	
			$array = $stmt->fetch();

			// updates gamelibrary row to status and userborrowid 
			if ($array["total"]==1) {
				$stmt = $pdo -> prepare('UPDATE `gamelibrary` SET `status`=0,`userborrowid`=0 WHERE gameID = :2 AND userID = :1');
				$stmt -> bindParam(':1', $userID);
				$stmt -> bindParam(':2', $gameID);
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