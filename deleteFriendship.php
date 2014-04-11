<?php
/*
	deletes rows from friend table based on logged in user and POST variable
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
	if (!empty($friendID)) {
		if (login_check($mysqli) == true) {

			$stmt = $pdo -> prepare('DELETE FROM friend WHERE userID=:1 AND friend= :2 OR userID=:2 AND friend= :1');
			$stmt -> bindParam(':1', $userID);
			$stmt -> bindParam(':2', $friendID);
			$stmt -> execute();
			$status["status"]= "friendship deleted";
			echo json_encode($status);

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