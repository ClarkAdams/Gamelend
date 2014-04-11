<?php

/*
	Functions used to cache XML file downloaded from thegamesdb.net API
*/
include_once 'xml2array.php';
include_once 'db_pdo_connect.php';

// function for creating and saving XML file to server cache directory
function cacheQuery($xml, $id, $pdo) {
	//echo "-------".$id."-------".$platformID."-------".$platformName."-------".$genre."-------".$filepath."-------".$gameTitle;
	if (!checkCacheForEntry($id, $pdo)) {

		$filepath = "api/xmlcache/".$id.".xml";
		$xml->save($filepath);
		$games = XML2Array::createArray($xml);
		
		/* prepares inser variables  */
		if (isset($games["Data"]["Game"]["PlatformId"])) {
			$platformID = $games["Data"]["Game"]["PlatformId"];
		} else {
			$PlatformID = "";
		}
		
		if ($games["Data"]["Game"]["Platform"]) {
			$platformName = $games["Data"]["Game"]["Platform"];
		} else {
			$platformName = "";
		}
		
		if ($games["Data"]["Game"]["GameTitle"]) {
			$gameTitle = $games["Data"]["Game"]["GameTitle"];
		} else {
			$gameTitle = "";
		}
		$genre = "";
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
		
		$stmt = $pdo -> prepare('INSERT INTO queryCache (id, platformID, platformName, genre, filepath, gameTitle) 
										VALUES (:1, :2, :3, :4, :5, :6)');
		$stmt -> bindParam(':1', $id);
		$stmt -> bindParam(':2', $platformID);
		$stmt -> bindParam(':3', $platformName);
		$stmt -> bindParam(':4', $genre);
		$stmt -> bindParam(':5', $filepath);
		$stmt -> bindParam(':6', $gameTitle);
		$stmt -> execute();
		
	
	} else {
		
	}
}

// function chechs for existing XML files. Returns false or true
function checkCacheForEntry($gameID, $pdo) {
	$stmt = $pdo -> prepare('SELECT count(id) as total FROM queryCache WHERE id=:1');
	$stmt -> bindParam(':1', $gameID);
	$stmt -> execute();	
	$count = $stmt->fetch();
	if ($count["total"]>0) {
		return true;
	} else {
		return false;
	}
	
}


?>