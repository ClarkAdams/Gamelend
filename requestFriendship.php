<?php
/*
	creates 2 entries in friend table with status 1(friendship requested)
	based on loged in user and posted userID(friendID)
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$friendID = $_POST["friendID"];

$status["status"] = "";

try {
	if ($userID!="") {
		if (login_check($mysqli) == true) {
			// query to se if request or friendship already exists
			$stmt = $pdo -> prepare('SELECT count(*) as total FROM friend WHERE userid = :1 and friend = :2');
			$stmt -> bindParam(':1', $userID);
			$stmt -> bindParam(':2', $friendID);
			$stmt -> execute();	
			$array = $stmt->fetch();
			// if no etry found 2 entries are created
			if ($array["total"]==0) {
				$stmt = $pdo -> prepare('INSERT INTO friend(userid, friend, status, requestFromUser) VALUES (:1, :2, "1", :1)');
				$stmt -> bindParam(':1', $userID);
				$stmt -> bindParam(':2', $friendID);
				$stmt -> execute();

				$stmt = $pdo -> prepare('INSERT INTO friend(userid, friend, status, requestFromUser) VALUES (:1, :2, "1", :2)');
				$stmt -> bindParam(':1', $friendID);
				$stmt -> bindParam(':2', $userID);
				$stmt -> execute();

				$status["status"]= "success";
				echo json_encode($status);
			} else {
				$status["status"]= "Friendsrequest already sent";
				echo json_encode($status);
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