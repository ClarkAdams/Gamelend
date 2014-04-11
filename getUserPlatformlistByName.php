<?php
/*
	returns users registered platform by name
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';

sec_session_start();

if (login_check($mysqli) == true) {

	if (isset($_POST["platforms"])) {
		
		//	make platformsstring into array of consoleID's
		$platforms = $_POST["platforms"];
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

		//	Make array with user platformstring
		$arr[] = array('platforms' => $platformString);
		echo json_encode($arr);
	}
}	

$mysqli = NULL;
?>