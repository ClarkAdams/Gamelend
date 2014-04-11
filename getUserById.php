<?php
/*
	gets data from userdata and mebers tables based on POST variable
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

if (login_check($mysqli)) {	
	$userid = $_POST["userid"];

	$stmt = $mysqli->prepare('SELECT userdata.id as id, 
								userdata.firstname as firstname, 
								userdata.lastname as lastname, 
								userdata.city as city, 
								userdata.rating as rating, 
								members.username as username,
								userdata.platforms as platforms,
								members.email as email 
									FROM userdata, members 
										WHERE members.id=?
											and userdata.id=?');
	$stmt->bind_param('ss', $userid, $userid);
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $firstname, $lastname, $city, $rating, $username, $platforms, $email);

	$arr = array();
	

	//	Whileloop for each result from query
	while ($row = $stmt->fetch()) {
	
		// Get users citname with cityID
		$stmt2 = $mysqli->prepare("SELECT name
										FROM cities
											WHERE id=?");
		$stmt2->bind_param('s', $city);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($cityName);
		$stmt2->fetch();

		//	make platformsstring into array of consoleID's
		$platforms = explode("a", $platforms);	
		$tempString	= "";
		$platformString = "";

		//	Iterate over consoleID-array for names with consoleID's
		foreach ($platforms as $value) {
			$stmt3 = $mysqli->prepare("SELECT console 
										FROM consoles 
											WHERE id=?");
			$stmt3->bind_param('s', $value);
			$stmt3->execute();
			$stmt3->store_result();
			$stmt3->bind_result($tempString);
			$stmt3->fetch();
			//	Populate users platformstring with consolen names
			$platformString .= (string)$tempString.", ";
			$stmt3->free_result();
		}
		if (strlen($platformString)>2) {
			$platformString = substr($platformString, 2, -2);
		} else {
			$platformString = "User has not yet registerd any platforms";
		}

		$stmt = $pdo -> prepare('SELECT gameID FROM gamelibrary WHERE userID = :1');
		$stmt -> bindParam(':1', $userid);
		$stmt -> execute();	
		$gameIDArray = $stmt->fetchAll(PDO::FETCH_ASSOC);

		//	Make array with userinfo, users cityname and users consoles in string format
		$arr[] = array('id' => $id, 
			'firstname' => $firstname, 
			'lastname' => $lastname, 
			'city' => trim($cityName), 
			'rating' => $rating, 
			'username' => $username, 
			'platforms' => $platformString, 
			'gameIDArray' => $gameIDArray,
			'email' => $email );
	};
	echo json_encode($arr);
}
$mysqli = NULL;
?>