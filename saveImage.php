<?php

/*
	resizing images based on the SimpleImage API and saves them to the server
*/

include_once 'api/SimpleImage.php';
include_once 'api/xml2array.php';
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';
include_once 'api/cacheGameQuery.php';

sec_session_start();

if (login_check($mysqli)) {

	
	// if game is from the lent element which has a different format from other (userID/Lend/GameID)
	$dataArray = explode("lend",$_POST["id"]);

	$id = $_POST["id"];
	if (isset($dataArray[1])) {
		$id = $dataArray[1];
	}
	
	$xml = new DOMDocument();
	
	// checks XML cache for previous query
	// if found, loads internal XML
	if (!checkCacheForEntry($id, $pdo)) {
		$xml->load('http://thegamesdb.net/api/GetGame.php?id='.$id);
		cacheQuery($xml, $id, $pdo);
	} else {
		$xml->load("api/xmlcache/".$id.".xml");
	}
	
	$games = XML2Array::createArray($xml);
	// checks if image already cached
	if (!file_exists('art/boxartthumb/'.$id.'.jpg')) {
		$games['Data']["Game"]["Images"]["noimage"] = "no image";
		$image = new SimpleImage(); 

		//	parses though the array for image entries. If found, saves image.
		if (is_array($games['Data']["Game"]["Images"])) {
			if (isset($games['Data']["Game"]["Images"]["boxart"][0]["@value"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["boxart"][1]["@value"];
				$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);
				
			} elseif(isset($games['Data']["Game"]["Images"]["boxart"]["@value"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["boxart"]["@value"];
				$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);
				
			} elseif(isset($games['Data']["Game"]["Images"]["fanart"][0]["thumb"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["fanart"][0]["thumb"];
				$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);
				
			} elseif(isset($games['Data']["Game"]["Images"]["clearlogo"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["clearlogo"]["@value"];
				$image->load($urlstring);
				$image->resizeToWidth(200); 
				$imageUrlString = 'art/boxartthumb/'.$id.'.jpg';
				$image->save($imageUrlString);
				
			}
		}
		
	} 
}

?>