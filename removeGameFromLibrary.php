<?php
/*
	deletes row from gamelibrary table
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$gameID = $_POST["gameID"];
$status["status"] = "";

try {
	if (isset($gameID)) {
		if (login_check($mysqli) == true) {
			// delets row with userID and GameID
			$stmt = $pdo -> prepare('DELETE FROM gamelibrary WHERE gameID=:1 AND userID= :2');
			$stmt -> bindParam(':1', $gameID);
			$stmt -> bindParam(':2', $userID);
			$stmt -> execute();

			$status["status"]= "game removed";
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