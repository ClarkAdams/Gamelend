<?php

/*
	Function for getting games based on userID.
	If no filters are set in form, client side, all games are returned based on friend libraries
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

//preparing variables for queries
$userID = $_SESSION['user_id'];
if ($_POST["friendFilter"]!="Friend") {
	$friendFilter = $_POST["friendFilter"];	
} else {
	$friendFilter = "";
}
if ($_POST["platformFilter"]!="Platform") {
	$platformFilter = $_POST["platformFilter"];
} else {
	$platformFilter = "";
}
if ($_POST["genreFilter"]!="Genre") {
	$genreFilter = $_POST["genreFilter"];	
} else {
	$genreFilter = "";
}
$searchstring = strtolower($_POST["searchstring"]);
$gameArray = array();
$status["status"] = "";

try {
	// search algorithm based on what or which parameters are set
	if (login_check($mysqli) == true) {
		// get list of friend/s depending on friend filter
		//if frind not selected in form all friends are returned
		if ($friendFilter!="") {
			$stmt = $pdo -> prepare('SELECT friend FROM friend WHERE userid = :1 AND friend = :2 AND status = 2');
			$stmt -> bindParam(':1', $userID);
			$stmt -> bindParam(':2', $friendFilter);
			$stmt -> execute();	
			$friendsArray = $stmt->fetchAll();
		} else {
			$stmt = $pdo -> prepare('SELECT friend FROM friend WHERE userid = :1 AND status = 2');
			$stmt -> bindParam(':1', $userID);
			$stmt -> execute();	
			$friendsArray = $stmt->fetchAll();
		}

		//if no filters in the form is set all games are returned
		if ($friendFilter=="" && $genreFilter=="" && $platformFilter=="" && $searchstring=="") {
			foreach ($friendsArray as $value) {
				$stmt = $pdo -> prepare('SELECT gameID FROM gamelibrary WHERE userID = :1');
				$stmt -> bindParam(':1', $value[0]);
				$stmt -> execute();	
				$count=0;
				foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $val) {
					$gameArray[$count]=$val;
					++$count;
				}
			}
		} else {
			// sql query returns all games based on the set filters
			foreach ($friendsArray as $value) {
				$stmt = $pdo -> prepare('SELECT gameID FROM gamelibrary WHERE userID = :1 AND name LIKE CONCAT("%",:4,"%")
																		AND platform LIKE CONCAT("%",:3,"%")
																		AND genre LIKE CONCAT("%",:2,"%")');
				$stmt -> bindParam(':1', $value[0]);
				$stmt -> bindParam(':2', $genreFilter);
				$stmt -> bindParam(':3', $platformFilter);
				$stmt -> bindParam(':4', $searchstring);
				$stmt -> execute();	
				$count=0;
				foreach ($stmt->fetchAll(PDO::FETCH_COLUMN) as $val) {
					$gameArray[$count]=$val;
					++$count;
				}
			}		
		}
		//remove all duplicate elements in array
		$gameArray = array_unique($gameArray);
		echo json_encode($gameArray);

	// return messages if error during execution or not loged in
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