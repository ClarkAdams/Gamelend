<?php
/*
	Returns game and user data based on POST variables
*/

include_once 'api/SimpleImage.php';
include_once 'api/xml2array.php';
include_once 'includes/db_connect.php';
include_once 'includes/functions.php';
include_once 'api/db_pdo_connect.php';
include_once 'api/cacheGameQuery.php';

sec_session_start();

if (login_check($mysqli)) {

	header("Content-type: application/json");
	// if game is from the lent element
	$dataArray = explode("lend",$_POST["id"]);
	$lender = $_POST["lenderUsername"];

	$id = $_POST["id"];
	if (isset($dataArray[1])) {
		$id = $dataArray[1];
	}
	
	$xml = new DOMDocument();
	// checks if XML is cached
	if (!checkCacheForEntry($id, $pdo)) {
		$xml->load('http://thegamesdb.net/api/GetGame.php?id='.$id);
		cacheQuery($xml, $id, $pdo);
	} else {
		$xml->load("api/xmlcache/".$id.".xml");
	}

	$games = XML2Array::createArray($xml);
	
	// sets variables in array to N/A if not exists
	if (isset($games['Data']["Game"]["Co-op"])) {
		$games['Data']["Game"]["Coop"] = $games['Data']["Game"]["Co-op"];
	} else {
		$games['Data']["Game"]["Coop"] = "N/A";
	}
	if (!isset($games['Data']["Game"]["Players"])) {
		$games['Data']["Game"]["Players"] = "N/A";
	}
	$games['Data']["Lender"] = $lender;

	// if game does not have image cached a check though the array is made to find path
	if (!file_exists('art/boxartthumb/'.$id.'.jpg')) {
		$games['Data']["Game"]["Images"]["noimage"] = "no image";
		$image = new SimpleImage(); 
		
		//	parses though the array for image entries. If found, returns Games array
		if (is_array($games['Data']["Game"]["Images"]) && !isset($games['Data']["Game"]["Images"])) {
			if (isset($games['Data']["Game"]["Images"]["boxart"][0]["@value"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["boxart"][1]["@value"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
			} elseif(isset($games['Data']["Game"]["Images"]["boxart"]["@value"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["boxart"]["@value"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
			} elseif(isset($games['Data']["Game"]["Images"]["fanart"][0]["thumb"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["fanart"][0]["thumb"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
			} elseif(isset($games['Data']["Game"]["Images"]["clearlogo"])) {
				$urlstring = $games['Data']["baseImgUrl"].$games['Data']["Game"]["Images"]["clearlogo"]["@value"];
				$games['Data']["Game"]["Images"]["boxart"] = $urlstring;
				echo json_encode($games); 
			}
		//if no image found default "noimage.jpg" is set
		} else {
			$games['Data']["Game"]["Images"]["boxart"] = 'art/boxartthumb/noimage.jpg';
			$games['Data']["Game"]["Images"]["noimage"] = "";
			echo json_encode($games); 
		}
	// if image cached path set
	} elseif(file_exists('art/boxartthumb/'.$id.'.jpg')) {
		$games['Data']["Game"]["Images"]["boxart"] = 'art/boxartthumb/'.$id.'.jpg';
		echo json_encode($games); 
	}
}

?>