<?php

/*
	calls SQL procedure to insert game into gamelibrary table based on logged in user and POST variables
*/

header("Content-type: application/json");
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/xml2array.php';
include_once 'api/db_pdo_connect.php';

sec_session_start();

$userID = $_SESSION['user_id'];
$gameID = $_POST["gameID"];
$status["status"] = "";

try {
	if (isset($gameID)) {
		if (login_check($mysqli) == true) {
			// checks if row with correct data exists 
			$stmt = $pdo -> prepare('SELECT count(*) as total FROM gamelibrary WHERE userID = :1 AND gameID = :2');
			$stmt -> bindParam(':1', $userID);
			$stmt -> bindParam(':2', $gameID);
			$stmt -> execute();	
			$count = $stmt->fetch();
			// if row exists 
			if(!$count['total']>0) {

				$xml = file_get_contents('http://thegamesdb.net/api/GetGame.php?id='.$gameID); 
			
				$games = XML2Array::createArray($xml);
				$genre = "";
				// set gametitle, genre and platform varibales
				$name = (isset($games["Data"]["Game"]["GameTitle"])) ? $games["Data"]["Game"]["GameTitle"] : "";
				$platform = (isset($games["Data"]["Game"]["Platform"])) ? $games["Data"]["Game"]["Platform"] : "";
				if (isset($games["Data"]["Game"]["Genres"]["genre"])) {
					if (is_array($games["Data"]["Game"]["Genres"]["genre"])) {
						foreach ($games["Data"]["Game"]["Genres"]["genre"] as $value) {
							$genre .= "/".$value;	
						}
					} else {
						$genre .= "/".$games["Data"]["Game"]["Genres"]["genre"];
					}
					$genre .= "/";
				} else {
					$genre = "";
				}
				// call SQL procedure with arguments
				/*
					DELIMITER $$
					CREATE PROCEDURE addgametolibrary(gid int, uid int, genre varchar(30), platform varchar(30), name varchar(50))
					BEGIN
					  IF (SELECT COUNT(*) FROM gamelibrary WHERE gameID=gid AND userID=uid)=0 THEN
					  INSERT INTO gamelibrary (gameID, userID, genre, platform, name) VALUES ( gid, uid ,genre, platform, name); 
					  END IF;
					END;
					$$
					DELIMITER ;
				*/
				$stmt = $pdo -> prepare('CALL addgametolibrary(:1, :2, :3, :4, :5)');
				$stmt -> bindParam(':1', $gameID);
				$stmt -> bindParam(':2', $userID);
				$stmt -> bindParam(':3', $genre);
				$stmt -> bindParam(':4', $platform);
				$stmt -> bindParam(':5', $name);
				$stmt -> execute();
				
				$status["status"]= "success";
				echo json_encode($status);
			} else {
				$status["status"]= "already registered";
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