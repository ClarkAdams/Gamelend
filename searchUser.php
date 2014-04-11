<?php
/* description:  searches users and relation to the logged in user */

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli)) {

	
	if (isset($_POST["searchstring"])) {
		$searchstring = $_POST["searchstring"];	
	} else {
		$searchstring = "";
	}

	//	Set filters if variable is recived otherwise set no value for wildcard query
	if ($_POST["platform"]!="") {
		$platformFilter = "a".$_POST["platform"]."a";
	} else {
		$platformFilter = "";
	}
	if ($_POST["city"]!="0") {
		$cityFilter = $_POST["city"];
	} else {
		$cityFilter = "%";
	}
	// returning all users if no searchstring posted.
	if (empty($_POST["searchstring"])) {
		
		$stmt = $mysqli->prepare('SELECT userdata.id as id, 
								userdata.firstname as firstname, 
								userdata.lastname as lastname, 
								userdata.city as city, 
								userdata.rating as rating, 
								members.username as username,
								userdata.platforms as platforms,
								userdata.img as img,
								members.email as email 
									FROM userdata, members 
										WHERE members.id=userdata.id 
											and members.id!=?
											and userdata.id!=?
											and userdata.platforms LIKE CONCAT("%",?,"%") 
											and userdata.city LIKE ?
											or members.id=userdata.id 
											and members.id!=?
											and userdata.id!=?
											and userdata.platforms LIKE CONCAT("%",?,"%") 
											and userdata.city LIKE ?');
		$stmt->bind_param('ssssssss', $_SESSION['user_id'], $_SESSION['user_id'], $platformFilter, $cityFilter, $_SESSION['user_id'], $_SESSION['user_id'], $platformFilter, $cityFilter);

		// Retuns query results depending on filters and search wildcard string
	} else {

		//	Get user data by firstname and/or city and/or consoles
	$stmt = $mysqli->prepare('SELECT userdata.id as id, 
								userdata.firstname as firstname, 
								userdata.lastname as lastname, 
								userdata.city as city, 
								userdata.rating as rating, 
								members.username as username,
								userdata.platforms as platforms,
								userdata.img as img,
								members.email as email 
									FROM userdata, members 
										WHERE userdata.firstname LIKE CONCAT("%", ?,"%") 
											and members.id=userdata.id 
											and members.id!=?
											and userdata.id!=?
											and userdata.platforms LIKE CONCAT("%",?,"%") 
											and userdata.city LIKE ?
											or members.username LIKE CONCAT("%", ?,"%") 
											and members.id=userdata.id 
											and members.id!=?
											and userdata.id!=?
											and userdata.platforms LIKE CONCAT("%",?,"%") 
											and userdata.city LIKE ?');
	$stmt->bind_param('ssssssssss', $searchstring, $_SESSION['user_id'], $_SESSION['user_id'], $platformFilter, $cityFilter, $searchstring, $_SESSION['user_id'], $_SESSION['user_id'], $platformFilter, $cityFilter);
	}
	
	
	$stmt->execute();
	$stmt->store_result();
	$stmt->bind_result($id, $firstname, $lastname, $city, $rating, $username, $platforms, $img, $email);

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

		$stmt2 = $mysqli->prepare("SELECT status
										FROM friend
											WHERE userid=? AND friend=?");
		$stmt2->bind_param('ss', $id, $_SESSION['user_id']);
		$stmt2->execute();
		$stmt2->store_result();
		$stmt2->bind_result($status);
		$stmt2->fetch();

		//	Populate array with userinfo, users cityname and users consoles
		$arr[] = array('id' => $id, 
			'firstname' => $firstname, 
			'lastname' => $lastname, 
			'city' => trim($cityName), 
			'rating' => $rating, 
			'username' => $username, 
			'platforms' => $platformString, 
			'img' => $img, 
			'status' => $status,
			'email' => $email );
	};
	echo json_encode($arr);
}	
$mysqli = NULL;
?>