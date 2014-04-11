<?php

/*
	updates friendship request with 0(no) or 2(yes) based on logged in user and POST variables
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$friendID = str_replace("message", "", $_POST["userid"]);
$response = $_POST["response"];
$status["status"] = "";

try {
	if ($userID!="") {
		if (login_check($mysqli) == true) {

			if ($response=="respondyes") {
				// verifies table for correct row content
				$stmt = $pdo -> prepare('SELECT count(*) as total FROM friend WHERE userid = :1 and friend = :2 and status=1');
				$stmt -> bindParam(':1', $userID);
				$stmt -> bindParam(':2', $friendID);
				$stmt -> execute();	
				$array = $stmt->fetch();
				// if row exists update row 
				if ($array["total"]==1) {

					$stmt = $pdo -> prepare('UPDATE friend SET status=2 WHERE userid=:1 AND friend=:2 AND status=1 AND requestFromUser=:2');
					$stmt -> bindParam(':1', $userID);
					$stmt -> bindParam(':2', $friendID);
					$stmt -> execute();

					$stmt = $pdo -> prepare('UPDATE friend SET status=2 WHERE userid=:1 AND friend=:2 AND status=1 AND requestFromUser=:1');
					$stmt -> bindParam(':1', $friendID);
					$stmt -> bindParam(':2', $userID);
					$stmt -> execute();

					$status["status"]= "success";
					echo json_encode($status);
				} else {
					$status["status"]= "already friends";
					echo json_encode($status);
				}
			} elseif ($response=="respondno") {
				// verifies table for correct row content
				$stmt = $pdo -> prepare('SELECT count(*) as total FROM friend WHERE userid = :1 and friend = :2 and status=1');
				$stmt -> bindParam(':1', $userID);
				$stmt -> bindParam(':2', $friendID);
				$stmt -> execute();	
				$array = $stmt->fetch();
				// if row exists update row 
				if ($array["total"]==1) {

					$stmt = $pdo -> prepare('DELETE FROM friend WHERE userid=:1 AND friend=:2 AND status=1 AND requestFromUser=:2');
					$stmt -> bindParam(':1', $userID);
					$stmt -> bindParam(':2', $friendID);
					$stmt -> execute();

					$stmt = $pdo -> prepare('DELETE FROM friend WHERE userid=:1 AND friend=:2 AND status=1 AND requestFromUser=:1');
					$stmt -> bindParam(':1', $friendID);
					$stmt -> bindParam(':2', $userID);
					$stmt -> execute();

					$status["status"]= "success: respond no";
					echo json_encode($status);
				} else {
					$status["status"]= "no entry of request found";
					echo json_encode($status);
				}
			}
		} else {
			$status["status"]= "not loged in";			
			echo json_encode($status);
		}	
	} else {
		$status["status"]= "no value";
		
		echo json_encode($status);
	}
		
} catch(Exeption $e) {
	$status["status"]= $e;
	echo json_encode($status);
}

$pdo = NULL;
$mysqli = NULL;
?>