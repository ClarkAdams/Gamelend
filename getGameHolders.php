<?php
/*
	returns user data based on gamelibrary entries and logged in user
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$gameID = $_POST["gameID"];
$userID = $_SESSION['user_id'];
$userIDarray = array();
$userDataArray = array();
$status["status"] = "";

try {
	if (login_check($mysqli) == true) {
		if (!empty($gameID)) {
			// gets userID based on game and holder
			$stmt = $pdo -> prepare('SELECT userID FROM gamelibrary WHERE gameID = :1 AND userID != :2');
			$stmt -> bindParam(':1', $gameID);
			$stmt -> bindParam(':2', $userID);
			$stmt -> execute();	
			$userIDarray = $stmt->fetchAll(PDO::FETCH_COLUMN);
			// iterates over returned user ID's
			foreach ($userIDarray as $value) {
				$stmt = $pdo -> prepare('SELECT * FROM usernamesdndid WHERE id = :1');
				$stmt -> bindParam(':1', $value);
				$stmt -> execute();
				array_push($userDataArray, $stmt->fetchAll(PDO::FETCH_CLASS));
			}

			echo json_encode($userDataArray);
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