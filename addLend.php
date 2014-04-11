<?php

/*
	updates lend request with 0(no) or 2(yes) based on logged in user and POST variables
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';
include_once 'api/redirectFunctions.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$response = $_POST["response"];
$dataArray = explode("lend",$_POST["gameIDfriendID"]);
$friendID = $dataArray[0];
$gameID = $dataArray[1];
$status["status"] = "";

try {
	if (login_check($mysqli) == true) {
		if (!empty($gameID) && !empty($friendID)) {
			if ($response=="respondyes") {
				// verifies table for correct row content
				$stmt = $pdo -> prepare('SELECT count(*) as total FROM gamelibrary WHERE userID = :1 AND userborrowid = :2 AND gameid = :3 AND status = 1');
				$stmt -> bindParam(':1', $userID);
				$stmt -> bindParam(':2', $friendID);
				$stmt -> bindParam(':3', $gameID);
				$stmt -> execute();	
				$array = $stmt->fetch();
				// if row exists update row 
				if ($array["total"]==1) {
					$stmt = $pdo -> prepare('UPDATE `gamelibrary` SET `status`=2,`userborrowid`=:1, lentdate=now() WHERE gameID = :3 AND userID = :2 AND status = 1');
					$stmt -> bindParam(':1', $friendID);
					$stmt -> bindParam(':2', $userID);
					$stmt -> bindParam(':3', $gameID);
					$stmt -> execute();

					$status["status"]= "success";
					echo json_encode($status);
					
				} else {
					$status["status"]= "Lendconfirm false for some reason";
					echo json_encode($status);
				}

			} elseif ($response=="respondno") {
				// verifies table for correct row content
				$stmt = $pdo -> prepare('SELECT count(*) as total FROM gamelibrary WHERE userID = :1 AND userborrowid = :2 AND gameid = :3 AND status = 1');
				$stmt -> bindParam(':1', $userID);
				$stmt -> bindParam(':2', $friendID);
				$stmt -> bindParam(':3', $gameID);
				$stmt -> execute();	
				$array = $stmt->fetch();
				// if row exists update row 
				if ($array["total"]==1) {
					$stmt = $pdo -> prepare('UPDATE `gamelibrary` SET `status`=0,`userborrowid`=0 WHERE gameID = :3 AND userID = :2 AND status = 1');
					$stmt -> bindParam(':2', $userID);
					$stmt -> bindParam(':3', $gameID);
					$stmt -> execute();

					$status["status"]= "success : responded no";
					echo json_encode($status);
					

				} else {
					$status["status"]= "respond no for some reason";
					echo json_encode($status);
				}

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