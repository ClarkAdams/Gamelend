<?php
/*
	inserts row on bugreport table with POST variables and enviroment variables
*/

include_once '../includes/db_connect.php';
include_once '../includes/functions.php';
include_once 'db_pdo_connect.php';

sec_session_start();
$shortdescription = "empty";
$description = "empty";
print_r($_POST);

try {
	if (login_check($mysqli) == true) {
		
		$shortdescription = $_POST["shortdescription"];
		$description = $_POST["description"];
		$uri = $_POST["url"];

		$stmt = $pdo -> prepare("INSERT INTO bugreport (timestamp, 
														ip, 
														iphost, 
														HTTP_USER_AGENT, 
														userID, 
														email, 
														shortdecription, 
														description, 
														REQUEST_URI,
														status,
														type) 
											VALUES (now(),
													:1, :2, :3, :4, :5, :6, :7, :8, 1, 0)");
		$stmt -> bindParam(':1', $_SERVER["REMOTE_ADDR"]);
		$stmt -> bindParam(':2', gethostbyaddr($_SERVER["REMOTE_ADDR"]));
		$stmt -> bindParam(':3', $_SERVER["HTTP_USER_AGENT"]);
		$stmt -> bindParam(':4', $_SESSION["user_id"]);
		$stmt -> bindParam(':5', $_SESSION['email']);
		$stmt -> bindParam(':6', $shortdescription);
		$stmt -> bindParam(':7', $description);
		$stmt -> bindParam(':8', $uri);
		$stmt -> execute();	

		echo "success";
		header( 'Location: '.$uri );
	} else {
		echo "not loged in";
	}
		
} catch(Exeption $e) {
	echo "something went wrong: ".$e;
}

$pdo = NULL;
$mysqli = NULL;

?>