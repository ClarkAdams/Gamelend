<?php
/*
	get borrowed games based on logged in user
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$status["status"] = "";

try {
	if (login_check($mysqli) == true) {

			$stmt = $pdo -> prepare('SELECT gameID, genre, platform, name, lentdate, id, username, firstname, lastname FROM userandgamelibrary WHERE userborrowid = :1 AND status = 2');
			$stmt -> bindParam(':1', $userID);
			$stmt -> execute();	
			$lentArray = $stmt->fetchAll(PDO::FETCH_CLASS);
			echo json_encode($lentArray);
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